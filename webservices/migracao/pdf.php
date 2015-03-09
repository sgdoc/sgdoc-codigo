<?php

include 'pdf-watermarker-master/pdfwatermarker/pdfwatermark.php';
include 'pdf-watermarker-master/pdfwatermarker/pdfwatermarker.php';


include 'pdf-watermarker-master/fpdf/fpdf.php';
include 'pdf-watermarker-master/fpdi/fpdi.php';


//Specify path to image
$watermark = new PDFWatermark('/var/www/html/sgdoc/webservices/migracao/temp/' . $_REQUEST['digital'] . '.png');

//Specify the path to the existing pdf, the path to the new pdf file, and the watermark object
$watermarker = new PDFWatermarker('/var/www/html/sgdoc/cache/import/LOTE2/0028233.pdf', '/var/www/html/sgdoc/cache/import/LOTE2/0028233-marked.pdf', $watermark);

//Set the position
$watermarker->setWatermarkPosition('topright');

//Save the new PDF to its specified location
$watermarker->watermarkPdf();



