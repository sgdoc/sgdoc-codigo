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

namespace Documento\Imagem\Formato;

/**
 * @todo Métodos __toString() show() e getData() estão com comportamento
 * diferente da classe FormatoPDF. Alinhar comportamentos
 */

class FormatoPNG implements IFormato
{    
    protected $_absoluteFileName = '';
    
    protected $_resourcePNG = null; 
    
    public function __construct( $parAbsoluteFileName='' ) 
    {
       
        if (!extension_loaded('gd')) {
            throw new \Exception("FormatoPNG::__construct() - Extensão GD não foi instalada");
        }
        
        if(!count(trim( $parAbsoluteFileName ))){
            throw new \Exception("FormatoPNG::__construct() - Imagem com endereço inválido");
        }
        $this->_absoluteFileName = $parAbsoluteFileName;            
        $this->_fileExists();
    }
    
    /**
     * Escreve somente endereço absoluto do arquivo
     */
    public function __toString() 
    {
        return file_get_contents( $this->_absoluteFileName );
    }
    
    /**
     * Verifica existência do arquivo informado no endereço absoluto
     * @return boolean
     * @throws Exception
     */
    protected function _fileExists()
    {
        if(!file_exists($this->_absoluteFileName)){
            throw new \Exception("FormatoPNG::_fileExists() - Arquivo inexistente! " . $this->_absoluteFileName );
        }
        return true;        
    }
    
    public function show()
    {
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past   
        header('Content-Type: image/png');
        fpassthru( fopen( $this->_absoluteFileName, 'r' ) );
    }
    
    /**
     * Realiza rotação da imagem original para formato Retrato com mesmas dimensões
     * @param FormatoPNG::Constante $parFormato Description
     * @throws \Exception
     */
    public function rotaciona( $parFormato=self::FORMATO_RETRATO )
    {
        $this->_resourcePNG = imagecreatefrompng($this->_absoluteFileName) or die('Error opening file '.$this->_absoluteFileName);
        
        imagealphablending($this->_resourcePNG, false);
//        imagesavealpha($this->_resourcePNG, true);
        imagesavealpha($this->_resourcePNG, false);
    
        $altura = imagesy( $this->_resourcePNG );
        $largura = imagesx( $this->_resourcePNG );
        
        $graus = 0.0;
        
        if( ($parFormato == self::FORMATO_RETRATO) && ( $largura > $altura ) ){
            $graus = 90.0;
        }        
        if( ($parFormato == self::FORMATO_PAISAGEM) && ( $altura > $largura ) ){
            $graus = 270.0;
        }
        
        if($graus){
            $this->_gira( $graus );
        }
        
        imagedestroy( $this->_resourcePNG );
    }
    
    /**
     * Gira a imagem e libera o recurso
     * 
     * @param Resource $parImgPNG
     * @param Double $parGraus
     * @throws \Exception
     */
    protected function _gira( $parGraus=0.0 )
    {
        if($parGraus == 0){
            return;
        }
        
        if( !( $this->_resourcePNG = imagerotate($this->_resourcePNG, $parGraus, imageColorAllocateAlpha($this->_resourcePNG, 0, 0, 0, 127)) ) ){
            echo "<pre>";
            throw new \Exception("FormatoPNG::_gira() - Não foi possivel rotacionar a imagem");
        }

//        imagealphablending($this->_resourcePNG, false);
//        imagesavealpha($this->_resourcePNG, true);
        if(!imagealphablending($this->_resourcePNG, false)){
            throw new \Exception("FormatoPNG::_gira() - Não foi possível aplicar imagealphablending");            
        }
        if(!imagesavealpha($this->_resourcePNG, false)){
            throw new \Exception("FormatoPNG::_gira() - Não foi possível aplicar imagesavealpha");
        }
        
        if( !(imagepng($this->_resourcePNG, $this->_absoluteFileName, 9)) ){
            echo "<pre>";
            throw new \Exception("FormatoPNG::_gira() - Não foi possível salvar a imagem após rotacioná-la");                
        }
    }
    
    public function getData(){}
    
}
