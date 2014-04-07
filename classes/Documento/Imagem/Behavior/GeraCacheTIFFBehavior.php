<?php
/*
 * Copyright 2008 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuíção e/ou modifição dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuíção na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 * */

/**
 * @author Rogerio Alves <ralves.moura@gmail.com>
 */

namespace Documento\Imagem\Behavior;

use
    Documento\Imagem\IDocumentoImagem,
    Documento\Imagem\Formato
;

class GeraCacheTIFFBehavior implements IGeraCacheBehavior  
{
    protected $_documentoImagem;
    
    public function __construct( IDocumentoImagem &$parDocumentoImagem )
    {
        $this->_documentoImagem = $parDocumentoImagem;
    }

    public function geraThumbs()
    {
        //Remove limite de tempo para execução do script
        set_time_limit(0);
        
        $dirFrom = $this->_documentoImagem->getUploadPath();
        $dirTo = $this->_documentoImagem->getCachePath();
        
        foreach ($this->_documentoImagem->getRowSet() as $row) {
            $imageName = $row['MD5'];
            //Define origem da imagem
            $absoluteSource = $dirFrom . $imageName . '.tif';
            //Define destino da imagem
            $absoluteDest = $dirTo . $imageName . '_thumb.png';

            //Verifica se existe o arquivo de origem
            if (!is_file($absoluteSource)) {
                throw new \Exception('GeraCacheTIFFBehavior::geraThumbs() -  Arquivo TIFF original não existe!');
            }
            
            //Caso não exista o Thumbnail, gera
            if(!file_exists($absoluteDest)){
                /**
                 * Tenta criar um arquivo de thumbnail TIFF para convertê-lo para PNG (processo mais veloz, mas mais passível de erros)
                 * Desta forma, fez-se necessário realizar a verificação da integridade do arquivo
                 * para caso não exista, faz pelo método convencional, utilizando ImageMagick-convert direto do TIFF de guarda
                 */
                $absoluteThumbTIFF = $dirTo . $imageName . '_thumb.tif';

                //Tamanho A7, Resolução 50ppp, Mapa de cores GrayScale - redução de 2066x2924 para 413x585
                $largura_thumb = 210;
                $altura_thumb = 297;

                if( !$this->_isPortrait($absoluteSource) ){
                    $largura_thumb = 297;
                    $altura_thumb = 210;
                }
                
                //Tenta converter para thumb TIFF
                $cmd01 = "thumbnail -h {$altura_thumb} -w {$largura_thumb} -c exp90 {$absoluteSource} {$absoluteThumbTIFF}";
                shell_exec($cmd01);
                
                $convertCommand = "/usr/local/bin/convert -type Grayscale";
                
                // Tenta converter O thumb TIFF em thumb PNG
                $cmd02 = "{$convertCommand} {$absoluteThumbTIFF} {$absoluteDest}";
                shell_exec( $cmd02 );
                
                // Apaga o arquivo de thumbnail TIFF
                @unlink($absoluteThumbTIFF);

                //Se não houve erros, o thumb PNG foi gerado, senão converte direto da fonte
                if (!is_file($absoluteDest)) {
                    $cmd03 = "{$convertCommand} -thumbnail {$largura_thumb}x{$altura_thumb}\! -colorspace GRAY -alpha Off {$absoluteSource}[0] {$absoluteDest}";
                    $retorno = shell_exec( $cmd03 );
                }

                //Usa objeto IFormato para rotacionar a imagem, caso seja necessário
                $formatoPNG = new Formato\FormatoPNG($absoluteDest);
                $formatoPNG->rotaciona();
            }            
        }
    }
    
    public function geraLeitura( array $parPages )
    {
        $dirFrom = $this->_documentoImagem->getUploadPath();
        $dirTo = $this->_documentoImagem->getCachePath();
        
        $arrPaginasGeradas = array();
        
        foreach ($parPages as $page) {
            $absoluteSourceFileName = "{$dirFrom}{$page}.tif";
            $absoluteDestFileName = "{$dirTo}{$page}.png";
            
            //Realiza a geração da imagem em formato leitura somente caso ela não exista em disco
            if( !file_exists($absoluteSourceFileName) ){
                throw new \Exception('GeraCachePNGBehavior::geraLeitura() - Arquivo solicitado não existe-' . $absoluteSourceFileName );
            }
            
            //Se arquivo de cache não existe, gera a partir do source
            if( !file_exists($absoluteDestFileName) ){
                shell_exec("/usr/local/bin/convert -rotate '-90>' -quality 200 -resize 595x842 {$absoluteSourceFileName}[0] {$absoluteDestFileName}");
            }

            $formatoPNG = new Formato\FormatoPNG($absoluteDestFileName);
            $formatoPNG->rotaciona();
            $arrPaginasGeradas[] = $formatoPNG;
        }
        
        return $arrPaginasGeradas;
    }
    
    /**
     * Captura tamanho do arquivo e 
     * @param type $parAbsolutesource
     * @return boolean Retorna TRUE caso Altura for maior que Largura
     */
    protected function _isPortrait( $parAbsolutesource = '' )
    {        
        $retorno = shell_exec("tiffinfo {$parAbsolutesource}");
        $pattern = "/Image Width:\s(?P<width>\d+)\sImage Length:\s(?P<length>\d+)/";
        
        $arrRetorno = array();
        preg_match_all($pattern, $retorno, $arrRetorno, PREG_PATTERN_ORDER);
        
        if( ((int)$arrRetorno['length'][0]) >= ((int)$arrRetorno['width'][0]) ) {
            return true;
        }
        return false;
    }   

}
