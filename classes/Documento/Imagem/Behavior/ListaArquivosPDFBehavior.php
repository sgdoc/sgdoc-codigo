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

use Documento\Imagem\IDocumentoImagem;

class ListaArquivosPDFBehavior implements IListaArquivosBehavior 
{
    protected $_documentoImagem;
    
    protected $_qtdPaginas = 0;
    
    protected $_boolFileInfo = false;    
    
    protected $_thumbs = array();
    protected $_imagens = array();
    
    public function __construct( IDocumentoImagem &$parDocumentoImagem )
    {
        $this->_documentoImagem = $parDocumentoImagem;
    }
    
    public function getListaLeitura()
    {
        $rowset = $this->_documentoImagem->getRowSet();

        $this->_imagens = array();
        
        $arrPadrao = array(
            'ORDEM'         =>  $rowset[0]['ORDEM'],
            'DIGITAL'       =>  $rowset[0]['DIGITAL'],
            'MD5'           =>  "",
            'FLG_PUBLICO'   =>  $rowset[0]['FLG_PUBLICO'],
            'IMG_WIDTH'     =>  $rowset[0]['IMG_WIDTH'],
            'IMG_HEIGHT'    =>  $rowset[0]['IMG_HEIGHT'],
            'DAT_INCLUSAO'  =>  $rowset[0]['DAT_INCLUSAO'],
            'IMG_TYPE'      =>  $rowset[0]['IMG_TYPE']
        );
        
        $qtdPaginas = $this->getQuantidadePaginas();
        $imageName = $this->_documentoImagem->getImageName();
        //Preenche array com Imagens que serão geradas em cache
        for( $i=1; $i<=$qtdPaginas; $i++ ){
            $arrPadrao['MD5'] = sprintf("{$imageName}_%04d", $i);
            $this->_imagens[] = $arrPadrao;
        }
        return $this->_imagens;
    }

    public function getListaThumbs()
    {
        $dirTo = $this->_documentoImagem->getCachePath();
        
        $this->_thumbs = array();
        $qtdPaginas = $this->getQuantidadePaginas();
        $imageName = $this->_documentoImagem->getImageName();
        //Preenche array com Thumbs gerados em cache
        for( $i=1; $i <= $qtdPaginas; $i++ ){
            $this->_thumbs[] = sprintf("{$dirTo}{$imageName}_%04d_thumb.png", $i);
        }
        return $this->_thumbs;
    }

    public function getQuantidadePaginas( $parForce=false )
    {        
        if( $parForce ){
//            Tentativa de recuperar quantidade de páginas por tags específicas do PDF - SEM SUCESSO
//            $pdf_text = file_get_contents($this->_documentoImagem->getUploadPath().$this->_documentoImagem->getImageName().'.pdf');
//            $number_of_pages = preg_match_all("//Count/", $pdf_text, $dummy);
//            $result = preg_match_all("/Count/", $pdf_text, $dummy);//resultado geralmente é incorreto
            $result = exec("/usr/local/bin/identify -format %n {$this->_documentoImagem->getUploadPath()}{$this->_documentoImagem->getImageName()}.pdf");
            $this->_boolFileInfo = true;
            $this->_qtdPaginas = (is_numeric($result))? $result : 0;
            return $this->_qtdPaginas;
        }
        
        //Se já foi consultado, retorna o nr de páginas
        if( $this->_boolFileInfo ){
            return $this->_qtdPaginas;
        }else{
            $this->_qtdPaginas = 0;
            //Se tem rowset, tenta recuperar dele
            if( count($this->_documentoImagem->getRowSet()) ){
                $rowset = $this->_documentoImagem->getRowSet();
                $this->_boolFileInfo = true;
                $this->_qtdPaginas = (int) $rowset[0]['TOTAL_PAGINAS'];
            }
            if( $this->_qtdPaginas === 0){
                $result = exec("/usr/local/bin/identify -format %n {$this->_documentoImagem->getUploadPath()}{$this->_documentoImagem->getImageName()}.pdf");
                $this->_boolFileInfo = true;
                $this->_qtdPaginas = (is_numeric($result))? $result : 0;
            }
        }

        return $this->_qtdPaginas;
    }
}
