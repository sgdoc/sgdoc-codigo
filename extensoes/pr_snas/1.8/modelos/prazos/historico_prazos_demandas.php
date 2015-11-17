<?php

$aColumns = array('ID', 'DT_PRAZO', 'MOVIMENTO', 'DEMANDA', 'DT_RESPOSTA', 'RESPOSTA', 'ORGAO');
$aColumnsFTS = array(
    'MOVIMENTO',
    'DEMANDA',
    'RESPOSTA',
    'ORGAO',
);
$sIndexColumn = "ID";
$sTable = "ext__snas__vw_historico_prazos_demandas";

print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, " DIGITAL_PAI='{$_REQUEST['pai']}' AND DIGITAL_FILHO='{$_REQUEST['digital']}'", $aColumnsFTS));