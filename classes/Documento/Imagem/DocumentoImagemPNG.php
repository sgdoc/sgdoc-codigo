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

namespace Documento\Imagem;

use 
    Documento\Imagem\Behavior\GeraCachePNGBehavior,
    Documento\Imagem\Behavior\ListaArquivosPNGBehavior,
    Documento\Imagem\DocumentoImagemAbstract
;

class DocumentoImagemPNG extends DocumentoImagemAbstract
{
    const TYPE_FILE = "image/png";
    const FILE_EXTENSION = "png";
    const IMG_TYPE = 8; // O tipo PNG será identificado com este número.
    
    protected $_totalPages = 0;

    public function __construct() 
    {
        parent::__construct();
        $this->_geraCacheBehavior = new Behavior\GeraCachePNGBehavior( $this );
        $this->_listaArquivosBehavior = new Behavior\ListaArquivosPNGBehavior( $this );
    }

    protected function _getRightExtension() 
    {
        return DocumentoImagemPNG::FILE_EXTENSION;
    }
    
    protected function _exportToThumbs()
    {
        $this->_geraCacheBehavior->geraThumbs();
    }
    
    protected function _getFileInfo( $parForce = false )
    {
        $this->_listaArquivosBehavior->getQuantidadePaginas( $parForce );
    }
    
    /* ORDEM, DIGITAL, MD5, FLG_PUBLICO, IMG_WIDTH, IMG_HEIGHT, DAT_INCLUSAO, IMG_TYPE */
    public function getImagesToRead( $parPages=array() )
    {
        return $this->_geraCacheBehavior->geraLeitura( $parPages );
    }
    
    protected function _deleteImagem()
    {//REFATORAR
        //Efetua exclusão lógica no BD em FLG_PUBLICO
        //0 CONFIDENCIAL
        //1 PUBLICO
        //2 EXCLUIDO        
        $stmt = $this->_conn->prepare("
            UPDATE TB_DOCUMENTOS_IMAGEM 
            SET FLG_PUBLICO = 2
            WHERE
            DIGITAL = ?
        ");
        $stmt->bindParam(1, $this->_digital, \PDO::PARAM_STR);
        $stmt->execute();
        
        //Remove Cache da Digital
        $pattern = $this->_cachePath . '*.png';
        $arrPngFiles = glob( $pattern );
        foreach ($arrPngFiles as $key => $absoluteFileName) {
            @unlink( $absoluteFileName );
        }
    }

    //Não efetua upload de imagens PNG
    protected function _saveImageData(){}
    
    //REVISAR
    public function setRowSet( $parRowSet=array() )
    {
        if(!count($parRowSet)){
            throw new \Exception("DocumentoImagemPNG::loadByRowSet - Parâmetro inválido");
        }
        $this->_rowset = $parRowSet;
        
        $this->_digital = $parRowSet[0]['DIGITAL'];
        $this->_setUploadPath();
        $this->_setCachePath();
        
        $this->_img_name = $parRowSet[0]['MD5'];        
        
        //Recupera o Array de Thumbs - deve ser sempre gerado
        $arrThumbs = $this->getThumbList();
        
        //Gera todos os Thumbs, caso algum não exista
        foreach ($arrThumbs as $thumb) {
            /**
             * @todo Verificar implementação de geração por demanda e verificação
             * de existência por filtro GLOB e utilização de Pattern
             * Pode causar sobrecarga em storage.
             */
            //Se não existe algum arquivo Thumb
            if(!file_exists($thumb)){
                //Gera todo o cache dessa digital, caso não exista algum arquivo
                $this->_exportToThumbs();
                return $this;
            }
        }
        return $this;
    }
    
    public function getThumbList()
    {
        return $this->_listaArquivosBehavior->getListaThumbs();
    }
    
    public function getImageList()
    {
        return $this->_listaArquivosBehavior->getListaLeitura();
    }

    /**
     * @todo Realizar verificação de permissão e parametrizar
     */
    public function getPDF()
    {
        include_once 'fpdf/fpdf.php';
        include_once 'pdfbookmark/PDF_Bookmark.php';

        $pdf = new \PDF_Bookmark();
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->author = utf8_decode(__CABECALHO_ORGAO__);
        $pdf->title = 'Sistema Gerenciador de Documentos - v' . __VERSAO__;
        $pdf->creator = $pdf->title . ' (' . utf8_decode(\Controlador::getInstance()->usuario->NOME) . ')';

        $key = 0;
        if($this->_isConfidentialAuthorized()){

            foreach ($this->_rowset as $key => $page) {
                $arrPages = array();
                $arrPages[] = $page['MD5'];
                $this->getImagesToRead( $arrPages );
                
                $imagePath = $this->_cachePath . $page['MD5'] . '.png';                
                
                if (!is_file($imagePath)) {
                    $imagePath = __BASE_PATH__ . '/' . self::INEXISTENTE_IMAGE_RELATIVE_FILENAME;
                }

                $pdf->AddPage("P", "A4");
                $pdf->Image($imagePath, 0, 0, 210, 297, "PNG");

                $pdf->Bookmark('Pág. ' . $key + 1);
            }
            
        }else{
            
            $imagePath = __BASE_PATH__ . '/' . self::CONFIDENCIAL_IMAGE_RELATIVE_FILENAME;
            for($i=0; $i<$this->_listaArquivosBehavior->getQuantidadePaginas(); $i++){
                
                $pdf->AddPage("P", "A4");                
                $pdf->Image($imagePath, 0, 0, 210, 297, "JPG");
                
                $pdf->Bookmark('Pág. ' . $key + 1);
            }
            
        }
        
        $pdfData = $pdf->Output("", 'S');

        return new Formato\FormatoPDF("", "{$this->_rowset[0]['DIGITAL']}", $pdfData);
    }

}


