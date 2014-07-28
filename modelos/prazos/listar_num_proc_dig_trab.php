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


$DIRETORIA = DaoUnidade::getUnidade(null, 'id');
$REQUEST = isset($_GET['request']) ? $_GET['request'] : NULL;

$out = null;

if ($REQUEST == 'D') {
    
    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT DIGITAL FROM TB_DOCUMENTOS_CADASTRO WHERE ID_UNID_AREA_TRABALHO = ? ORDER BY DIGITAL;");
    $stmt->bindParam(1, $DIRETORIA, PDO::PARAM_INT);
    $stmt->execute();

    $resul = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($resul)) {
        $out = array('success' => 'false');
    } else {
        foreach ($resul as $value) {
            $doc = array();
            $doc['numero'] = $value['DIGITAL'];
            $array[] = $doc;
        };

        $out = array('success' => 'true', 'numeros' => $array);
    }
}

if ($REQUEST == 'P') {
    
    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT NUMERO_PROCESSO FROM TB_PROCESSOS_CADASTRO WHERE ID_UNID_AREA_TRABALHO = ? ORDER BY NUMERO_PROCESSO");
    $stmt->bindParam(1, $DIRETORIA, PDO::PARAM_INT);
    $stmt->execute();

    $resul = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($resul)) {
        $out = array('success' => 'false');
    } else {
        foreach ($resul as $value) {
            $doc = array();
            $doc['numero'] = $value['NUMERO_PROCESSO'];
            $array[] = $doc;
        };

        $out = array('success' => 'true', 'numeros' => $array);
    }
}

if (isset($_REQUEST['callback'])) {
    $callback = $_REQUEST['callback'];
}
//start output
if (isset($callback)) {
    //header('Content-Type: text/javascript');
    echo $callback . '(' . json_encode($out) . ');';
} else {
    //header('Content-Type: application/x-json');
    echo json_encode($out);
}