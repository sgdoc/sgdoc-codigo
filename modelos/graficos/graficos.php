<?php


/**
 * 
 */
if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0) {
    $grafico = new graficos;
    if (isset($_POST['grafico_por_periodo'])) {
        $grafico->grafico_por_periodo();
    }
    if (isset($_POST['grafico_diario'])) {
        $grafico->grafico_diario();
    }
}