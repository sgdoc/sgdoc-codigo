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

class GeraCachePDFBehavior implements IGeraCacheBehavior  
{
    protected $_documentoImagem;
    
    public function __construct( IDocumentoImagem &$parDocumentoImagem )
    {
        $this->_documentoImagem = $parDocumentoImagem;
    }

    public function geraThumbs()
    {
        $dirFrom = $this->_documentoImagem->getUploadPath();
        $dirTo = $this->_documentoImagem->getCachePath();
        $imageName = $this->_documentoImagem->getImageName();
        
        //Opção comum
        $gsOptions = "-quiet -dSAFER -dBATCH -dNOPAUSE -dNumRenderingThreads=2";
        //Nomenclatura do arquivo thumb
        $sOutputFile = "{$dirTo}{$imageName}_%04d_thumb.png";        

        /* @todo pamametrizar resolução */
        $msg = shell_exec("gs -sDEVICE=pnggray -r50 -sPAPERSIZE=a10 {$gsOptions} -sOutputFile={$sOutputFile} {$dirFrom}{$imageName}.pdf");
        
        // verifica se houve erro na conversão do pdf
        $this->_errorExists($msg);

        //Rotaciona todas as imagens
        $pattern = $dirTo . $imageName . '*_thumb.png';
        $arrPngFiles = glob( $pattern );
        
        foreach ($arrPngFiles as $key => $absoluteFileName) {
            $formatoPNG = new Formato\FormatoPNG($absoluteFileName);
            $formatoPNG->rotaciona();
        }
    }
    
    public function geraLeitura( array $parPages )
    {
        $dirFrom = $this->_documentoImagem->getUploadPath();
        $dirTo = $this->_documentoImagem->getCachePath();
        $imageName = $this->_documentoImagem->getImageName();
        
        $arrPaginasGeradas = array();
        
        //Opção comum
        $gsOptions = "-quiet -dSAFER -dBATCH -dNOPAUSE -dNumRenderingThreads=2";
        
        foreach ($parPages as $page) {
            $page = (int) str_replace( $imageName.'_', '', $page );

            //Verifica se o parametro é numérico
            if( (!is_numeric($page)) || ($page<=0) ){
                $rowset = $this->_documentoImagem->getRowSet();
                $digital = $rowset[0]['DIGITAL'];
                throw new \Exception("DocumentoImagemPDF::geraLeitura() - Página [{$page}] da Digital {$digital} não é numérica!");
            }
            
            //Gerar uma página por vez
            $paramPage = "-dFirstPage={$page} -dLastPage={$page}";

            //Nomenclartura do arquivo formato Leitura
            $absoluteFileName = sprintf("{$dirTo}{$imageName}_%04d.png", $page);
            
            /**
             * Realiza a geração da imagem em formato leitura somente caso ela não exista em disco
             * Como a criação efetiva do arquivo não é um processo imediato por depender de uma chamada ao sistema,
             * pode haver um problema no carregamento do Cache gerado, operação realizada em seguida.
             * @todo Pensar em estratégia para resolver essa assincronia
             */
            if( !file_exists($absoluteFileName) ){
                /* @todo pamametrizar resolução */
                $msg = shell_exec("gs -sDEVICE=png16m -r250 -sPAPERSIZE=a4 {$gsOptions} {$paramPage} -sOutputFile={$absoluteFileName} {$dirFrom}{$imageName}.pdf");

                // Verifica se houve erro na conversão do pdf
                $this->_errorExists($msg);
            }
            $formatoPNG = new Formato\FormatoPNG($absoluteFileName);
            $formatoPNG->rotaciona();
            $arrPaginasGeradas[] = $formatoPNG;
        }
        
        return $arrPaginasGeradas;
    }
    
    /**
     * Lança uma Exception caso encontre a palavra "error"
     * 
     * @param string $statement
     * @throws Exception
     */
    protected function _errorExists($statement) 
    {
        if (preg_match('/error/i', $statement)) {
            throw new \Exception('Ocorreu um erro! ' . $statement);
        }
    }

}
