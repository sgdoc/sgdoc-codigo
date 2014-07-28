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

namespace Documento;

/**
 * Entidade representa um Bloco Documental Físico ou Lógico
 * Cria e carrega Digitais que serão trabalhadas
 * Devem ser carregadas todas as Imagens, Anexos e Apensos
 * possibilitando a Ordenação pelo físico (Histórico), 
 * Visualização de Imagens de Documentos
 */
class BlocoDocumental
{
    protected $_digital = null;
    
    protected $_arrDigitaisRelacionadas = array();
    
    protected $_arrDocImagem = null;
    
    protected $_des_hash = null;
    
    protected $_boolSortFisico = false;

    /**
     * Realiza carregamento do Bloco Documental de determinada Digital,
     * considerando os documentos atrelados a ele (Anexos e Apensos), de cima
     * pra baixo.
     * @param string $parDigital
     * @return \Documento\BlocoDocumental
     * @throws Exception
     */
    public function loadDigital( $parDigital = null )
    {
        if(!$parDigital){
            throw new \Exception('BlocoDocumental::loadDigital() - Número da Digital não informado para carregamento do Bloco Documental');
        }

        $this->_digital = $parDigital;

        $this->_loadDocumentosRelacionados( $parDigital );

        return $this;
    }
    
    /**
     * Carrega Documentos Imagem de acordo com informações da Digital Principal 
     * e dos Vínculos existentes
     */
    protected function _loadDocumentoImagem()
    {
        if(!$this->_digital){
            throw new \Exception('BlocoDocumental::_loadDocumentoImagem() - Número da Digital não informado para carregamento dos Documentos Imagem');
        }

        $arrDocumentoImagem = array();

        $documentoImagemAgg = null;
        $documentoImagemAgg = Imagem\DocumentoImagemFactory::factory( $this->_digital );

        $this->_arrDocImagem = array();
        $this->_arrDocImagem[] = $documentoImagemAgg;
        foreach ( $this->_arrDigitaisRelacionadas as $digitalRelacionada ) {
            $documentoImagemAgg = null;
            $documentoImagemAgg = Imagem\DocumentoImagemFactory::factory( $digitalRelacionada );
            $this->_arrDocImagem[] = $documentoImagemAgg;
        }
    }
    
    /**
     * Retorna array com Lista de Imagens de todos os arquivos do Bloco Documental
     * @return array (rowset)
     */
    public function getImageList()
    {
        $this->_loadDocumentoImagem();
        
        $images = array();
        foreach ( $this->_arrDocImagem as $docImagem ) {
            $images = array_merge($images, $docImagem->getImageList());
        }
        if($this->_boolSortFisico){
            $images = $this->_sortImageListByFisico( $images );
        }

        return $images;
    }
    
    /**
     * Realiza ordenação da lista de Imagens do Bloco Documental, como 
     * se é visto no físico, isto é, por ordem de entrada no sistema
     * @param array $_parImageList
     * @return array Retorna lista ordenada por entrada
     */
    protected function _sortImageListByFisico( array $_parImageList = array() )
    {
        $imageListOriginal = $_parImageList;
        $imageListResult = array();
        
        $imageAnterior = null;
        foreach ($imageListOriginal as $imageAtual) {
            if( !$imageAnterior ){
                $imageAnterior = $imageAtual;
            }
            
            //Adiciona as imagens da Digital Principal com mesma data de inclusão
            if( $imageAtual['DIGITAL']      === $this->_digital &&
                $imageAtual['DAT_INCLUSAO'] === $imageAnterior['DAT_INCLUSAO'] ){
                $imageListResult[] = $imageAtual;
                array_shift( $imageListOriginal );//??? BUG
            }
            $imageAnterior = $imageAtual;
        }
        
        //Bubble Sort (ordenação)
        $imageListOrdered = $imageListOriginal;
        
        $x = 0;
        $y = 0;
        $arrTamanho = count( $imageListOrdered );
        while( $x < $arrTamanho ) {
            $y = $x+1;
            while( $y < $arrTamanho ) {
                $dtHrTmpAtual       = new \Zend_Date( $imageListOrdered[$x]['DAT_INCLUSAO'] );
                $dtHrTmpComparado   = new \Zend_Date( $imageListOrdered[$y]['DAT_INCLUSAO'] );
                if( $dtHrTmpAtual->getTimestamp() > $dtHrTmpComparado->getTimestamp() ){
                    $rowTmp = $imageListOrdered[$y];
                    $imageListOrdered[$y] = $imageListOrdered[$x];
                    $imageListOrdered[$x] = $rowTmp;
                }
                $y++;
            }
            $x++;
        }

        $imageListResult = array_merge( $imageListResult, $imageListOrdered );
        
        return $imageListResult;
    }
    
    /**
     * Retorna array contendo todos os Documento Imagens do Bloco Documental
     * @param type $parMD5Files
     * @return type
     */
    public function getDocumentoImagens( $parMD5Files = array() )
    {
        //Verifica se é para realizar o sort
        return $this->_arrDocImagem;
    }
    
    /**
     * Seta Flag de Ordenação por Booleano com 
     * @param type $parBool
     */
    public function sortByFisico( $parBool = true )
    {
        $this->_boolSortFisico = $parBool;
        return $this;
    }
    
    /**
     * Carrega Documentos Relacionados
     * @param string $parDigital
     * @throws Exception
     */
    protected function _loadDocumentosRelacionados( $parDigital = '' )
    {
        if( !$parDigital ){
            throw new \Exception('BlocoDocumental::_loadDocumentosRelacionados() - Número da Digital não informado para carregamento dos Documentos Relacionados');
        }

        $vinculacao = new \Vinculacao();
        $documentosRelacionados = $vinculacao->getDocumentosRelacionados( $parDigital );

        $this->_arrDigitaisRelacionadas = array();
        if( is_array($documentosRelacionados) && count($documentosRelacionados) ){
            $arrDocumentosRelacionados = $documentosRelacionados[ $parDigital ];
            foreach ( $arrDocumentosRelacionados as $chave => $valor) {
                if($chave !== 'this'){
                    $this->_arrDigitaisRelacionadas[] = $valor;
                }
            }            
        }
    }
    
}


