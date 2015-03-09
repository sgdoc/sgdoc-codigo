<?php

require './classes/Write.php';
require './classes/Bootstrap.php';

$out = NULL;

try {
    if (Write::factory()->write($_REQUEST['path'], $_REQUEST['content'])) {
        $out = array('success' => 'true', 'message' => 'teste convertido com sucesso...');
    } else {
        $out = array('success' => 'false', 'message' => 'ocorreu um erro ao tentar converter o teste selenium...');
    }
} catch (Exception $e) {
    $out = array('success' => 'false', 'message' => $e->getMessage());
}

print json_encode($out);
