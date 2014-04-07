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

class FormatoPDF implements IFormato
{    
    protected $_nickname = '';
    protected $_absoluteFileName = '';
    
    protected $_data = null; 
    
    public function __construct( $parAbsoluteFileName='', $parNickname='', $parData=null ) 
    {
        if( strlen( trim ($parAbsoluteFileName) ) ){
            $this->_absoluteFileName = $parAbsoluteFileName;
//            if( $this->_fileExists() ){
//                $this->_data = file_get_contents($this->_absoluteFileName);
//            }
        } elseif ( $parData ) {
            $this->_data = $parData;
        }

        $this->_nickname = strlen(trim($parNickname))? trim($parNickname) : 'filename';
    }
    
    /**
     * Reescrita do método __toString para utilizar o conteúdo do arquivo
     * guardado em disco ou na variável $this->_data
     * @throws Exception Emite erro, identificando o arquivo sem conteúdo
     */
    public function __toString() 
    {
        if( !$this->_data ){
            if ( $this->_fileExists() ) {
                return file_get_contents($this->_absoluteFileName);                
            }else{
                return "";//Verificar tratamento para inexistencia de arquivo e conteudo
            }
        }else{
            return $this->_data;
        }
    }
    
    /**
     * Verifica existência do arquivo informado no endereço absoluto
     * @return boolean
     * @throws Exception
     */
    protected function _fileExists()
    {
        if(!file_exists($this->_absoluteFileName)){
            throw new \Exception("FormatoPDF::_fileExists() - Arquivo inexistente! " . $this->_absoluteFileName );
        }
        return true;        
    }
    
    /**
     * Lança cabeçalhos HTML de conteúdo PDF, informa que não deve ser 
     * guardado em cache e imprime conteúdo do arquivo.
     */
    public function show()
    {
        header("Content-type:application/pdf");
        header("Content-Disposition:attachment;filename=PDF-DOC-{$this->_nickname}.pdf");
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
        $content = $this->__toString();
        echo $content;
//        fpassthru( fopen( $this->_absoluteFileName, 'r' ) );
    }
    
    /**
     * Não faz nada, para formato PDF
     */
    public function rotaciona( $parFormato=self::FORMATO_RETRATO ){}

    public function getData()
    {
        if( !$this->_data ){
            if ( $this->_fileExists() ) {
                return file_get_contents($this->_absoluteFileName);                
            }else{
                throw new \Exception("FormatoPDF::getData() - Conteúdo do arquivo {$this->_absoluteFileName} é vazio.");
            }
        }else{            
            return $this->_data;
        }
    }
}