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
    Documento\Imagem\Behavior\GeraCachePDFBehavior,
    Documento\Imagem\Behavior\ListaArquivosPDFBehavior,
    Documento\Imagem\DocumentoImagemAbstract
;

class DocumentoImagemPDF extends DocumentoImagemAbstract
{
    const TYPE_FILE = "application/pdf";
    const FILE_EXTENSION = "pdf";
    const IMG_TYPE = 9; // O tipo PDF será identificado com este número.
    
    protected $_totalPages = 0;

    public function __construct() 
    {
        parent::__construct();
        $this->_geraCacheBehavior = new GeraCachePDFBehavior( $this );
        $this->_listaArquivosBehavior = new ListaArquivosPDFBehavior( $this );
    }

    protected function _getRightExtension() 
    {
        return DocumentoImagemPDF::FILE_EXTENSION;
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
    {
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

    /**
     * Retorna DocumentoImagem Anterior ou Null
     * @return DocumentoImagemAbstract
     */
    protected function _hasPreviousFile()
    {
        try {
            return DocumentoImagemFactory::factory( $this->_digital );
        } catch (\Exception $exc) {
            return null;
        }        
    }
    
    /**
     * Realiza a junção entre PDFs. Não se deve realizar mais a junção dos PDFs.
     * @deprecated since version 4.2.14
     * @param array $parArrayImagens
     */
    protected function _getAppendFile( array $parArrayImagens )
    {
        $appendedPDFAbsoluteFileName = $this->_uploadPath .
                'append_' .
                preg_replace( '/[., ]/', '',$this->_digital . '_' . microtime() ).
                '.pdf';
        
        $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile={$appendedPDFAbsoluteFileName} ";
        foreach ($parArrayImagens as $pdfFilename) {
            $cmd .= $pdfFilename." ";
        }
        shell_exec($cmd);
        
        if( is_file($appendedPDFAbsoluteFileName) ){
            return $appendedPDFAbsoluteFileName;            
        }else{
            throw new \Exception("Erro ao tentar Unir arquivos PDFs");
        }
    }
    
    /**
     * Realiza a inserçao de nova imagem, por meio de merge ou transformação de
     * TIFFs do passivo em PDF. Como são situações não mais utilizadas, torna-se 
     * depreciado e não recomendado o uso.
     * 
     * @deprecated since version 4.2.14
     * @param string $parDigital
     * @param string $parPDFAbsoluteFilename
     * @param integer $parFlgPublico
     */
    public function newImage($parDigital, $parPDFAbsoluteFilename, $parFlgPublico=1)
    {
        $controller = \Controlador::getInstance();
        $this->_usuario = $controller->usuario->ID;
        $this->_unidade = $controller->usuario->ID_UNIDADE;

        $this->_digital = $parDigital;
        $this->_img_name     = preg_replace( '/[., ]/', '', $this->_digital . '_' . microtime() );
        $this->_originalName = $parPDFAbsoluteFilename;
        $this->_tmpName      = $parPDFAbsoluteFilename;
        
        $this->_deleteImagem();//exclusão lógica na imagem anterior, caso exista

        $this->_setUploadPath();
        $this->_setCachePath();

        $qtdPaginas = exec("/usr/local/bin/identify -format %n {$parPDFAbsoluteFilename}");
        $qtdPaginas= (is_numeric($qtdPaginas))? $qtdPaginas : 0;

        //cria um md5 para nomear o arquivo da imagem (pdf)
        $md5 = md5($parPDFAbsoluteFilename);            

        $absoluteNewFileName = $this->_uploadPath . $md5 . '.pdf';
        $this->_img_name = $md5;

        rename($parPDFAbsoluteFilename, $absoluteNewFileName);

        $stmt = $this->_conn->prepare("
            INSERT INTO TB_DOCUMENTOS_IMAGEM 
            ( DIGITAL, MD5, ORDEM, DES_HASH_FILE, IMG_WIDTH, IMG_HEIGHT, IMG_TYPE, 
            ID_USUARIO, ID_UNIDADE, FLG_PUBLICO, TOTAL_PAGINAS, IMG_BYTES ) 
            VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )
        ");

        $ordem = 0;
        $nrBytes = (int)filesize($absoluteNewFileName);

        $stmt->bindParam(1, $this->_digital, \PDO::PARAM_STR);
        $stmt->bindParam(2, $this->_img_name, \PDO::PARAM_STR);
        $stmt->bindParam(3, $ordem, \PDO::PARAM_INT);
        $stmt->bindParam(4, $this->_generateHashSha2FromFile($absoluteNewFileName), \PDO::PARAM_INT);
        $stmt->bindValue(5, self::IMG_WIDTH, \PDO::PARAM_INT);
        $stmt->bindValue(6, self::IMG_HEIGHT, \PDO::PARAM_INT);
        $stmt->bindValue(7, self::IMG_TYPE, \PDO::PARAM_INT);
        $stmt->bindParam(8, $this->_usuario, \PDO::PARAM_INT);
        $stmt->bindParam(9, $this->_unidade, \PDO::PARAM_INT);
        
        /**
         * Ao unir 2 PDFs a FLAG PUBLICO deve ser a do ultimo arquivo?
         * Isso pode liberar informações confidenciais
         */
        $stmt->bindParam(10, $parFlgPublico, \PDO::PARAM_INT);//PROBLEMA
        $stmt->bindParam(11, $qtdPaginas, \PDO::PARAM_INT);
        $stmt->bindParam(12, $nrBytes, \PDO::PARAM_INT);

        $stmt->execute();
    }

    protected function _saveImageData()
    {
        $session = \Session::get('_upload');
        
        /**
         * Verifica se a operação é de exclusão e se tiver permissão, executa exclusão
         * Insere novo arquivo forçando a captura das informações do novo arquivo.
         */
        if( $session['fg_operacao'] !== 'add' ){//Unir?
            $isAdmin = \AclFactory::checaPermissao( 
                    \Controlador::getInstance()->acl, 
                    \Controlador::getInstance()->usuario, 
                    \DaoRecurso::getRecursoById(998)
            );
            //Se não for administrador, não permite a substituição
            if(!$isAdmin){
                throw new \Exception("Não é permitido substituir o documento");
            }
            $this->_deleteImagem();//exclusão lógica na imagem anterior, caso exista
        }
        
        $absoluteFileName = '';
        if ($absoluteFileName == ''){
            $absoluteFileName = $this->_uploadPath . $this->_img_name . '.pdf';
        }

        //cria um md5 para nomear o arquivo da imagem (pdf)
        $md5 = md5($absoluteFileName);

        $absoluteNewFileName = $this->_uploadPath . $md5 . '.pdf';
        $this->_img_name = $md5;

        rename($absoluteFileName, $absoluteNewFileName);

        $stmt = $this->_conn->prepare("
            INSERT INTO TB_DOCUMENTOS_IMAGEM 
            ( DIGITAL, MD5, ORDEM, DES_HASH_FILE, IMG_WIDTH, IMG_HEIGHT, IMG_TYPE, 
            ID_USUARIO, ID_UNIDADE, FLG_PUBLICO, TOTAL_PAGINAS, IMG_BYTES ) 
            VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )
        ");

        $ordem = 0;
        $nrBytes = (int)filesize($absoluteNewFileName);

        $stmt->bindParam(1, $this->_digital, \PDO::PARAM_STR);
        $stmt->bindParam(2, $this->_img_name, \PDO::PARAM_STR);
        $stmt->bindParam(3, $ordem, \PDO::PARAM_INT);
        $stmt->bindParam(4, $this->_generateHashSha2FromFile($absoluteNewFileName), \PDO::PARAM_INT);
        $stmt->bindValue(5, self::IMG_WIDTH, \PDO::PARAM_INT);
        $stmt->bindValue(6, self::IMG_HEIGHT, \PDO::PARAM_INT);
        $stmt->bindValue(7, self::IMG_TYPE, \PDO::PARAM_INT);
        $stmt->bindParam(8, $this->_usuario, \PDO::PARAM_INT);
        $stmt->bindParam(9, $this->_unidade, \PDO::PARAM_INT);
        $stmt->bindParam(10, $session['fg_publico'], \PDO::PARAM_INT);
        $stmt->bindParam(11, $this->_listaArquivosBehavior->getQuantidadePaginas() , \PDO::PARAM_INT);
        $stmt->bindParam(12, $nrBytes, \PDO::PARAM_INT);

        $stmt->execute();
    }
    
    public function setRowSet( $parRowSet=array() )
    {
        if(!count($parRowSet)){
            throw new \Exception("DocumentoImagemPDF::loadByRowSet - Parâmetro inválido");
        }
        $this->_rowset = $parRowSet;
        
        $this->_digital = $parRowSet[0]['DIGITAL'];
        $this->_setUploadPath();
        $this->_setCachePath();        
        
        $this->_img_name = $parRowSet[0]['MD5'];

        return $this;
    }
    
    public function getThumbList()
    {
        $arrThumbs = $this->_listaArquivosBehavior->getListaThumbs();
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
            }
        }
        
        return $arrThumbs;
    }
    
    public function getImageList()
    {
        $arrThumbs = $this->getThumbList();
        return $this->_listaArquivosBehavior->getListaLeitura();
    }
    
    /**
     * @todo Realizar verificação de permissão e parametrizar
     */
    public function getPDF()
    {
        include_once 'fpdf/fpdf.php';
        include_once 'pdfbookmark/PDF_Bookmark.php';
        
        if($this->_isConfidentialAuthorized()){
            $absoluteFileName = $this->_uploadPath . $this->_img_name . '.pdf';
            return new Formato\FormatoPDF( $absoluteFileName, $this->_rowset[0]['DIGITAL'] );
        }else{
            $pdf = new \PDF_Bookmark();
            $pdf->SetMargins(0, 0, 0);
            $pdf->SetFont('Arial', '', 12);
            $pdf->author = utf8_decode(__CABECALHO_ORGAO__);
            $pdf->title = 'Sistema Gerenciador de Documentos - v' . __VERSAO__;
            $pdf->creator = $pdf->title . ' (' . utf8_decode(\Controlador::getInstance()->usuario->NOME) . ')';
            
            $key = 0;
            $imagePath = __BASE_PATH__ . '/' . self::CONFIDENCIAL_IMAGE_RELATIVE_FILENAME;
            for($i=0; $i<$this->_listaArquivosBehavior->getQuantidadePaginas(); $i++){
                $pdf->AddPage("P", "A4");
                $pdf->Image($imagePath, 0, 0, 210, 297, "JPG");
                $pdf->Bookmark('Pág. ' . $key + 1);
            }
            $pdfData = $pdf->Output("", 'S');
            return new Formato\FormatoPDF("", "{$this->_rowset[0]['DIGITAL']}", $pdfData);
        }
    }

}


