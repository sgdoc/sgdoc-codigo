<?php

namespace Documento\Imagem;

use 
        Documento\Imagem\IDocumentoImagem,
        Documento\Imagem\Formato\IFormato,
        Documento\Imagem\Behavior\IGeraCacheBehavior,
        Documento\Imagem\Behavior\IListaArquivosBehavior
;

final class DocumentoImagemFactory
{
    /**
     * Retorna Objeto DocumentoImagemAggregator que reune todos os Documento
     * Imagens dos diversos tipos que compõem as imagens do Documento
     * @param string $parDigital
     * @return \Documento\DocumentoImagemAggregator
     * @throws Exception
     */
    public static function factory ( $parDigital='' )
    {
        $rowset = self::_getRowSet($parDigital);
        
        $arrDocumentoImagem = array();
        
        $documentoImagem = null;
        foreach ($rowset as $rowSetDocumentoImagem){
            $img_type = null;
            switch ( $rowSetDocumentoImagem[0]['IMG_TYPE'] ) {
                case '7':
                    $documentoImagem = new DocumentoImagemTIFF();
                    break;
                case '8':
                    $documentoImagem = new DocumentoImagemPNG();
                    break;
                case '9':
                    $documentoImagem = new DocumentoImagemPDF();
                    break;
            }
            
            if(!is_object($documentoImagem)){
                throw new \Exception('DocumentoImagemFactory::factory() - Não foi possível criar o objeto DocumentoImagem');
            }

            $documentoImagem->setRowSet( $rowSetDocumentoImagem );

            $arrDocumentoImagem[] = $documentoImagem;
        }

        $arrDocumentoImagemAgg = new DocumentoImagemAggregator();
        $arrDocumentoImagemAgg->setDigital($parDigital);
        $arrDocumentoImagemAgg->setArrDocumentoImagem( $arrDocumentoImagem );

        return $arrDocumentoImagemAgg;
    }
    
    /**
     * Realiza a conversão para DocumentoImagemPDF de um DocumentoImagem de 
     * outro tipo.
     * A utilização de DocumentoImagemAggregator realiza a formação do documento
     * por meio dos vários Documentos Imagens subidos dos diversos tipos e 
     * portanto essa versão se tornou depreciada e é desencorajada.
     * 
     * @todo Remover daqui. Não é função de Fábrica.
     * @deprecated since version 4.2.14
     * @param \Documento\Imagem\DocumentoImagemAbstract $parDocumentoImagem
     * @return \Documento\DocumentoImagemAggregator
     */
    private static function _convertToPDF( DocumentoImagemAbstract $parDocumentoImagem )
    {
        $rowset = $parDocumentoImagem->getRowSet();
        
        //Se qualquer página anterior estiver setada como confidencial o documento será todo confidencial
        $flg_publico = 1;//Publica        
        foreach ($rowset as $row) {
            if($row['FLG_PUBLICO'] != 1){
                $flg_publico = $row['FLG_PUBLICO'];
            }
        }
        
        $fileName = md5(preg_replace( '/[., ]/', '', microtime() ));
        $absoluteFileName = $parDocumentoImagem->getUploadPath() . $fileName;        
        $retorno = file_put_contents( $absoluteFileName, $parDocumentoImagem->getPDF()->getData() );
        
        $digital = $rowset[0]['DIGITAL'];
        $documentoImagemPDF = new \Documento\Imagem\DocumentoImagemPDF();
        $documentoImagemPDF->newImage($digital, $absoluteFileName, $flg_publico);
        
        return self::factory( $digital );
    }

    /**
     * Realiza merge entre mais de um Documento Imagem que compõe as Imagens do
     * Documento. Esta versão é anterior à implementação do DocumentoImagemAggregator
     * e por este motivo está depreciada. Sua utilização não é recomendada.
     * 
     * @todo Remover daqui. Não é função de Fábrica.
     * @deprecated since version 4.2.14
     * @param string $parDigital
     * @param array $parDocumentoImagens
     * @return \Documento\DocumentoImagemAggregator
     */
    private static function _appendImages($parDigital, $parDocumentoImagens = array())
    {
        $cfg = \Config::factory();
//        $pdfMergedAbsoluteDest = $cfg->getParam('config.basepath') . '/cache/TEMP_PDF_DOCUMENTO_' . preg_replace( '/[., ]/', '', $parDigital . '_' . microtime() );
        $pdfMergedAbsoluteDest = $cfg->getParam('config.basepath') . '/cache/TESTE';
        if(!is_dir($pdfMergedAbsoluteDest)){
            @mkdir($pdfMergedAbsoluteDest, 0777, true);
        }

        $arrPDFs = array();
        $i=0;
        foreach ( $parDocumentoImagens as $parDocumentoImagem ){
            $pdfPartialDocAbsoluteFilename = sprintf("{$pdfMergedAbsoluteDest}/%04d.pdf", ++$i );
            $arrPDFs[] = $pdfPartialDocAbsoluteFilename;//Reune nomes dos arquivos PDFs
            //Salva em arquivos PDFs temporários
            $retorno  = file_put_contents( $pdfPartialDocAbsoluteFilename, $parDocumentoImagem->getPDF()->getData() );
        }
        $finalFileName = "{$pdfMergedAbsoluteDest}/final.pdf";
        
        $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile={$finalFileName} ";
        foreach ($arrPDFs as $pdfFilename) {
            $cmd .= $pdfFilename." ";
        }
        shell_exec($cmd);

        foreach ($arrPDFs as $pdfFilename) {
            @unlink($pdfFilename);
        }

        //Recupera FLG_PUBLICO do ultimo documento enviado
        $rowLastImage = $parDocumentoImagens[count($parDocumentoImagens)-1]->getRowSet();
        /**
         * @TODO: REFAZER
         */
        $documentoImagemPDF = new \Documento\Imagem\DocumentoImagemPDF();
        $documentoImagemPDF->newImage($parDigital, $finalFileName, $rowLastImage[0]['FLG_PUBLICO']);

//        @rmdir($pdfMergedAbsoluteDest);
//        unlink($pdfMersgedAbsoluteFilename);//apaga o diretório

        return self::factory($parDigital);
    }

    /**
     * Realiza o carregamento dos Rowsets relativos às imagens (PDF, TIFF, PNG...)
     * para realização do carregamento de imagens. Em caso de TIFFs, realiza um tipo
     * de consulta que considera a coluna ORDEM
     * 
     * @param string $parDigital
     * @return array (Rowset)
     * @throws \Exception
     */
    private static function _getRowSet( $parDigital )
    {
        $status = ($all == false) ? 2 : -1;//não é setado em nenhum local, passar por parametro

        $sql = "
            SELECT ORDEM, DIGITAL, MD5, FLG_PUBLICO, IMG_WIDTH, IMG_HEIGHT, 
                DAT_INCLUSAO, IMG_TYPE, TOTAL_PAGINAS
            FROM TB_DOCUMENTOS_IMAGEM 
            WHERE DIGITAL = ? AND FLG_PUBLICO != ?
        ";

        $sqlOutros = $sql . " AND IMG_TYPE != 9 ORDER BY ORDEM, ID";
        $sqlPDF = $sql . " AND IMG_TYPE = 9 ORDER BY ID";
        
        $arrRetorno = array();
        try {
            $stmt1 = \Controlador::getInstance()->getConnection()->connection->prepare($sqlOutros);
            $stmt1->bindParam(1, $parDigital, \PDO::PARAM_STR);
            $stmt1->bindParam(2, $status, \PDO::PARAM_INT);
            $stmt1->execute();
            $objAssoc1 = $stmt1->fetchAll(\PDO::FETCH_ASSOC);
            
            $stmt2 = \Controlador::getInstance()->getConnection()->connection->prepare($sqlPDF);
            $stmt2->bindParam(1, $parDigital, \PDO::PARAM_STR);
            $stmt2->bindParam(2, $status, \PDO::PARAM_INT);
            $stmt2->execute();
            $objAssoc2 = $stmt2->fetchAll(\PDO::FETCH_ASSOC);
            
            if(!empty( $objAssoc1 )){                
                $arrRetorno[] = $objAssoc1;
            }
            foreach ($objAssoc2 as $row) {
                $arrRetorno[][] = $row;
            }
            
            return $arrRetorno;
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
}