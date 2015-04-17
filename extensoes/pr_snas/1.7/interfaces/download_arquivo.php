<?php
$nomeArq = $_REQUEST['arquivo'];

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Content-type: application/force-download");
header('Content-Disposition: attachment; filename="'.$nomeArq.'"');

$pastaTmp = __CAM_UPLOAD__ . '/TMP/expdoccfg/'.session_id().'_';
fpassthru( fopen( ($pastaTmp . $nomeArq), 'r' ) );
