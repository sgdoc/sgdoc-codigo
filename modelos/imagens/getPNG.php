<?php

use 
    Documento\Imagem,
    Documento\Imagem\IDocumentoImagem
;

$documentoImagemAgg = Imagem\DocumentoImagemFactory::factory( $_REQUEST['digital'] );
$imagesToRead = $documentoImagemAgg->getImagesToRead( array($_REQUEST['page']) );

$imagesToRead[0]->show();

