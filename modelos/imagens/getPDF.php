<?php

use 
    Documento\Imagem,
    Documento\Imagem\IDocumentoImagem
;

if(isset($_REQUEST['digital']) && strlen($_REQUEST['digital']) > 0){
    $documentoImagemAgg = Imagem\DocumentoImagemFactory::factory( $_REQUEST['digital'] );
    $documentoImagemAgg->getPDF()->show();
}

//Se for um processo, precisamos realizar um merge dos PDFs
//@todo refazer em classe apropriada
if(isset($_REQUEST['numero_processo']) && strlen($_REQUEST['numero_processo']) > 0){
    $images = Imagens::factory()->recoverInformationDBByProcess($_REQUEST['numero_processo']);

    $cfg = Config::factory();
    $pdfMergedAbsoluteFilename = $cfg->getParam('config.basepath') . '/cache/TEMP_PDF_PROCESSO_' . md5( $_REQUEST['numero_processo'] . microtime());
    
    $arrPDFs = array();
    $documentoImagem = null;
    $i=0;

    $digital = '';
    foreach ( $images as $image ){
        if( $digital != $image['DIGITAL'] ){
            $digital = $image['DIGITAL'];
            
            $documentoImagem = Imagem\DocumentoImagemFactory::factory( $digital );
            $pdfDocAbsoluteFilename = sprintf("{$pdfMergedAbsoluteFilename}_%04d.pdf", ++$i );

            $arrPDFs[] = $pdfDocAbsoluteFilename;
            
            //Salva em arquivos PDFs temporários
            file_put_contents( $pdfDocAbsoluteFilename, $documentoImagem->getPDF()->getData() );         
        }
    }
    
    $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile={$pdfMergedAbsoluteFilename}.pdf ";
    foreach ($arrPDFs as $pdfFilename) {
        $cmd .= $pdfFilename." ";
    }
    shell_exec($cmd);
    
    foreach ($arrPDFs as $pdfFilename) {
        unlink($pdfFilename);
    }
    
    header("Content-type:application/pdf");
    header("Content-Disposition:attachment;filename=PDF-DOC-{$_REQUEST['numero_processo']}.pdf");
    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

    fpassthru( fopen( "{$pdfMergedAbsoluteFilename}.pdf", 'r' ) );
    unlink("{$pdfMergedAbsoluteFilename}.pdf");
}