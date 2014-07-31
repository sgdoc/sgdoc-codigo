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

include('function/funcao_vincular_processos.php');

/**
 * Verificar se o processo esta na area de trabalho
 */
if ($_GET) {
    if ($_GET['acao'] != 'vinculados') {
        if (!Processo::validarProcessoAreaDeTrabalho($_POST['numero_processo'])) {
            print(json_encode(array('success' => 'false', 'message' => Util::fixErrorString('Este processo não está na sua área de trabalho!'))));
            exit();
        }
    }
}

/**
 * Mensagens
 */
$stringOut = array();
$stringOut['AP_ERROR_ANEXO_PROC'] = "Atenção! Não foi possivel concluir sua solicitação. Um ou mais processos não podem ser anexados, os mesmos estão anexados em outro processo.";
$stringOut['AP_ERROR_CAD_PROC'] = "Erro: Um ou mais processos são inválidos ou não estão cadastrado no sistema.";
$stringOut['AP_ERROR_APENSO_PROC'] = "Atenção! Não foi possivel concluir sua solicitação. Um ou mais processos não podem ser apensados, os mesmos estão apensados em outro processo.";


if ($_POST) {

    $error = array();

    /* Instanciar sempre o objeto Base para ultilizar os metodos staticos das classes */
    new Base();

    /* Lista de processos envolvimentos */
    $verificar = array_unique(explode(',', $_POST['processos'] . ',' . $_POST['numero_processo']));

    /* Interromper a operacoes se pelo menos um processo nao constar na area de trabalho do usuario */
    foreach ($verificar as $processo) {
        if (!Processo::validarProcessoAreaDeTrabalho($processo)) {
            $error = array($processo);
        }
    }

    /* Interromper a execucao e printar a lista de processo fora  da area de trabalho do usuario */
    if (count($error) > 0) {
        print(json_encode(array('success' => 'false', 'message' => 'O(s) Processo(s) em vermelho não estão na Área de Trabalho!', 'processo' => $error)));
        exit(0);
    }


    $out = array();
    $processos = array_unique(explode(',', $_POST['processos']));

    try {

        /* verifica se o processo esta cadastrado no sistema */
        $resul = valCadProc($_POST['numero_processo']);

        if (empty($resul)) {
            $out['message'] = $stringOut["AP_ERROR_CAD_PROC"];
        } else {
            /* verifica se os processos á apensar ou desapensar estão cadastrado no sistema */
            $resul = valCadProc($processos);

            if (empty($resul)) {
                $out = $processos;
            } else {
                $iterator = new ArrayIterator($processos);
                while ($iterator->valid()) {
                    if (!in_array($iterator->current(), $resul)) {
                        $out[] = $iterator->current();
                    }
                    $iterator->next();
                }
            }
            if (!empty($out)) {
                if ($_POST['acao'] == "apensar") {
                    $out['message'] = ($stringOut['AP_ERROR_CAD_PROC']);
                } else if ($_POST['acao'] == "desapensar") {
                    $out['message'] = ($stringOut['AP_ERROR_CAD_PROC']);
                }
            }
            /* fim */
        }

        if (!empty($out)) {
            $out['success'] = 'false';
        } else {
            /* acao - apensar processos */
            if ($_POST['acao'] == "apensar") {
                /* verifica se os processos á apensar já estão apensos em outros processos */
                $iterator = new ArrayIterator($processos);
                while ($iterator->valid()) {
                    $current = getIdProcesso($iterator->current());
                    $resul = validarProcesso($current);
                    if (!empty($resul)) {
                        if (in_array($current, $resul)) {
                            $out[] = $iterator->current();
                        }
                    }
                    $iterator->next();
                }
                /* fim */
                if (!empty($out)) {
                    $out['success'] = 'false';
                    $out['message'] = ($stringOut['AP_ERROR_APENSO_PROC']);
                } else {
                    vincularProcessos($_POST);
                    $out = array('success' => 'true', 'processo' => $_POST['numero_processo'] . "&tipo_acao=" . $_POST['acao']);
                }
                /* acao - desapensar processos */
            } else if ($_POST['acao'] == "desapensar") {
                desvincularProcesso($_POST);
                $out = array('success' => 'true', 'processo' => $_POST['numero_processo']);
            } else if ($_POST['acao'] == "anexar") {
                /* verifica se os processos á anexar já esta anexado em outro processo */
                $iterator = new ArrayIterator($processos);
                while ($iterator->valid()) {
                    $current = getIdProcesso($iterator->current());
                    $resul = validarProcesso($current);
                    if (!empty($resul)) {
                        if (in_array($current, $resul)) {
                            $out[] = $iterator->current();
                        }
                    }
                    $iterator->next();
                }
                /* fim */

                if (!empty($out)) {
                    $out['success'] = 'false';
                    $out['message'] = ($stringOut['AP_ERROR_ANEXO_PROC']);
                } else {
                    vincularProcessos($_POST);
                    $out = array('success' => 'true', 'processo' => $_POST['numero_processo'] . "&tipo_acao=" . $_POST['acao']);
                }
            }
        }
    } catch (Exception $e) {
        $out = array('success' => 'false', 'message' => ($e->getMessage()));
    }

    header('Content-type: application/json; charset=UTF-8');
    print(json_encode($out));
}



/* * * PEGA LISTA DE PROCESSOS APENSADOS * */
if ($_GET) {
    if (isset($_GET['acao'])) {
        if ($_GET['acao'] == 'vinculados') {
            $out = Vinculacao::getProcessosVicunlados($_GET['numero_processo'], 2);
            header('Content-type: application/json; charset=UTF-8');
            print(json_encode($out));
        }
    }
}