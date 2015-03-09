<?php

include 'app/QR.php';
include 'app/App.php'; {
    $output = '';

    try {
        App::factory($_REQUEST['digital'], __BASE_PATH__ . '/cache/import')->run(function($response) use (&$output) {
            $output = $response;
        });
    } catch (Exception $e) {
        $output = array('success' => false, 'error' => $e->getMessage());
    }

    $url = 'https://dsv.sgdoc.sisicmbio.icmbio.gov.br/webservices/migracao/checksum.php?digital=' . $_REQUEST['digital'] . '&checksum=' . $output['checksum']; //json_encode($output);
}





$qr = new QR;








//set it to writable location, a place for temp generated PNG files
$PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;

//html PNG location prefix
$PNG_WEB_DIR = 'temp/';

include "phpqrcode/qrlib.php";

//ofcourse we need rights to create temp dir
if (!file_exists($PNG_TEMP_DIR))
    mkdir($PNG_TEMP_DIR);


$filename = $PNG_TEMP_DIR . '/' . $_REQUEST['digital'] . '.png';


$errorCorrectionLevel = 'L';


$matrixPointSize = 1;

QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

exit;


include 'pdf-watermarker-master/pdfwatermarker/pdfwatermark.php';
include 'pdf-watermarker-master/pdfwatermarker/pdfwatermarker.php';


include 'pdf-watermarker-master/fpdf/fpdf.php';
include 'pdf-watermarker-master/fpdi/fpdi.php';


//Specify path to image
$watermark = new PDFWatermark('/var/www/html/sgdoc/webservices/migracao/temp/' . $_REQUEST['digital'] . '.png');

//Specify the path to the existing pdf, the path to the new pdf file, and the watermark object
$watermarker = new PDFWatermarker($output['path'], $output['path'] . '-marked.pdf', $watermark);

//Set the position
$watermarker->setWatermarkPosition('buttomright');

//Save the new PDF to its specified location
$watermarker->watermarkPdf();



