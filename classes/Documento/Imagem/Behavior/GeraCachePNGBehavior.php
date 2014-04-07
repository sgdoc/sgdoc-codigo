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

class GeraCachePNGBehavior implements IGeraCacheBehavior  
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
        
//$time_start = microtime(true);
        foreach ($this->_documentoImagem->getRowSet() as $row) {
            $imageName = $row['MD5'];
            //Define origem da imagem
            $absoluteSource = $dirFrom . $imageName . '.png';
            //Define destino da imagem
            $absoluteDest = $dirTo . $imageName . '_thumb.png';

            //Se não existe o thumbnail
            if(!file_exists($absoluteDest)){                
                //Cria imagem a partir do endereço absoluto
                $pngFileResource = null;
                if( ! ($pngFileResource = imagecreatefrompng( $absoluteSource )) ){
                    throw new \Exception('GeraCachePNGBehavior::geraThumbs() - Não foi possível resource da imagem de origem-'.$absoluteSource);
                }

                imagealphablending( $pngFileResource, false );
                imagesavealpha( $pngFileResource, true );

                $altura = imagesy( $pngFileResource );
                $largura = imagesx( $pngFileResource );

                //Tamanho A10, Resolução 50ppp, Mapa de cores GrayScale - redução de 2066x2924 para 413x585
                $altura_thumb = 585;
                $largura_thumb = 413;
                if($altura < $largura){ //paisagem
                    $altura_thumb = 413;
                    $largura_thumb = 585;
                }

                $pngThumbResource = imagecreatetruecolor( $largura_thumb, $altura_thumb );

                //Aplica filtro Grayscale
                imagefilter($pngFileResource, IMG_FILTER_GRAYSCALE);
        //        imagefilter($pngFileResource, IMG_FILTER_SMOOTH);
        //        imagefilter($pngFileResource, IMG_GD2_COMPRESSED);

                //Redimensiona utilizando imagecopyresized pois imagecopyresampled é muito mais demorado
                if( !imagecopyresized($pngThumbResource, $pngFileResource, 0, 0, 0, 0, $largura_thumb, $altura_thumb, $largura, $altura) ){
                    throw new \Exception('GeraCachePNGBehavior::geraThumbs() - Não foi possível realizar o redimensionamento');
                }

                //Persiste thumb em cache
                if( !(imagepng( $pngThumbResource, $absoluteDest, 9, IMG_FILTER_GRAYSCALE )) ){
                    throw new \Exception("GeraCachePNGBehavior::geraThumbs() - Não foi possível salvar o thumbnail");                
                }

                //Libera recursos
                imagedestroy( $pngFileResource );
                imagedestroy( $pngThumbResource );
                
                //Rotaciona o thumb
                $formatoPNG = new Formato\FormatoPNG($absoluteDest);
                $formatoPNG->rotaciona();
            }
        }
        
//$time_end = microtime(true);
//$time = $time_end - $time_start;
//die("Esta conversão durou {$time} seg");
    }
    
    public function geraLeitura( array $parPages )
    {
        $dirFrom = $this->_documentoImagem->getUploadPath();
        $dirTo = $this->_documentoImagem->getCachePath();
        
        $arrPaginasGeradas = array();
        
        foreach ($parPages as $page) {
            $absoluteSourceFileName = "{$dirFrom}{$page}.png";
            $absoluteDestFileName = "{$dirTo}{$page}.png";
            
            //Realiza a geração da imagem em formato leitura somente caso ela não exista em disco
            if( !file_exists($absoluteSourceFileName) ){
                throw new \Exception('GeraCachePNGBehavior::geraLeitura() - Arquivo solicitado não existe-' . $absoluteSourceFileName );
            }
            
            //Se arquivo de cache não existe, copia do source
            if( !file_exists($absoluteDestFileName) ){
                //Preserva a imagem original, copiando a mesma para a pasta cache, onde poderá ser rotacionada e etc
                shell_exec("cp {$absoluteSourceFileName} {$absoluteDestFileName}");
            }

            $formatoPNG = new Formato\FormatoPNG($absoluteDestFileName);
            $formatoPNG->rotaciona();
            $arrPaginasGeradas[] = $formatoPNG;
        }
        
        return $arrPaginasGeradas;
    }
}
