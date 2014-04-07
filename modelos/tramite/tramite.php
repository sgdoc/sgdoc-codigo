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

if (isset($_POST)) {

    $manterTramite = array();
    $out = array();

    try {
        switch ($_POST['acao']) {
            /**
             * 
             */
            case 'get-unidades-tipo':
                $rsUnidadeTipo = DaoUnidadeTipo::listUnidadesTipo($_REQUEST['uop']);
                $out = toUtf8($rsUnidadeTipo->resultado);
                break;
            /**
             * 
             */
            case 'get-unidades-disponiveis':
                $manterTramite['TIPO'] = (integer) $_POST['tipo'];
                $manterTramite['ID'] = (integer) $_POST['idUnidade'];
                if ($_POST['listarTodos'] == 'ALL') {
                    $manterTramite['ALL'] = true;
                }
                $rsUnidades = DaoTramite::listTramiteDisponiveis($manterTramite, $_REQUEST['uop']);
                $out = toUtf8($rsUnidades->resultado);
                break;
            /**
             * 
             */
            case 'get-unidades-ativas':
                $manterTramite['ID'] = (integer) $_POST['idUnidade'];
                $rsUnidades = DaoTramite::listTramiteAtivo($manterTramite);
                $out = toUtf8($rsUnidades->resultado);
                break;
            /**
             * 
             */
            case 'get-unidade':
                $idUnidade = (integer) $_POST['idUnidade'];
                $nomeUnidade = DaoUnidade::getUnidade($idUnidade, 'NOME');
                if (isset($nomeUnidade) && !empty($nomeUnidade)) {
                    $out = array('success' => true, 'nome' => $nomeUnidade);
                } else {
                    throw new Exception('Ocorreu algum erro, tente novamente!');
                }
                break;
            /**
             * 
             */
            case 'salvar-tramite':
                $manterTramite['ID_REFERENCIA'] = (integer) $_POST['idReferencia'];
                $manterTramite['ID_UNIDADE'] = (integer) $_POST['idUnidade'];
                $rsTramite = DaoTramite::getTramite($manterTramite);
                if ($rsTramite->resultado == true) {
                    $manterTramite['ID'] = (integer) $rsTramite->resultado[0]['ID'];
                }
                if ($_POST['tratamento'] == 'unidadesAtivas') {
                    //adicionar
                    $out = DaoTramite::salvar($manterTramite);
                } else {
                    //remover
                    $out = DaoTramite::deletarTramite($manterTramite);
                }
                break;
            /**
             * 
             */
            case 'clonar-tramite':
                //
                $manterTramite['ID_REFERENCIA'] = (integer) $_POST['idClone'];
                $manterTramite['ID_UNIDADE'] = (integer) $_POST['idUnidade'];
                $rsTramite = DaoTramite::getTramite($manterTramite);
                if ($rsTramite->resultado == false) {
                    $salvar = DaoTramite::salvar($manterTramite);
                }
                $manterTramite = array();
                $manterTramite['ID_UNIDADE'] = (integer) $_POST['idClone'];
                $rsTramite = DaoTramite::getTramite($manterTramite);
                if ($rsTramite->resultado == true) {
                    $manterTramite = array();
                    $manterTramite['ID_UNIDADE'] = (integer) $_POST['idUnidade'];
                    foreach ($rsTramite->resultado as $value) {
                        $manterTramite['ID_REFERENCIA'] = $value['ID_REFERENCIA'];
                        $rsTramite = DaoTramite::getTramite($manterTramite);
                        if ($rsTramite->resultado == false) {
                            DaoTramite::salvar($manterTramite);
                        }
                    }
                }
                $out = array('success' => 'true', 'message' => 'Tramite Clonado com sucesso!');
                break;
            /**
             * 
             */
            case 'get-hierarquia':
                $idUnidade = (integer) $_POST['idUnidade'];

                $hierarquia = new TramiteHierarquia($idUnidade);
                $html = $hierarquia->make($_POST['idUnidade']);

                $out = array('success' => true, 'html' => $html);
                break;
            /**
             * 
             */
            case 'salvar-todos-hierarquia':

                $checked = $_POST['checked'];
                $manterTramite['ID_UNIDADE'] = (integer) $_POST['idUnidade'];
                if ($checked == 'false') {
                    $out = DaoTramite::deletarTramitePorUnidade($manterTramite);
                } else {
                    $idReferencias = $_POST['idReferencias'];
                    if (count($idReferencias) > 0) {
                        foreach ($idReferencias as $idReferencia) {
                            $manterTramite['ID_REFERENCIA'] = (integer) $idReferencia;
                            $rsTramite = DaoTramite::getTramite($manterTramite);
                            if ($rsTramite->resultado == false) {
                                $out = DaoTramite::salvar($manterTramite);
                            } else {
                                $out = array('success' => 'true', 'message' => 'Trâmite cadastrado com sucesso!');
                            }
                        }
                    }
                }
                break;
            default:

                break;
        }
    } catch (Exception $e) {
        LogError::sendReport($e);
        //Código 0 não vai sair o dialog.
        $out = array('success' => 'false', 'error' => $e->getMessage(), 'code' => $e->getCode());
    }
    print json_encode($out);
}

/**
 * 
 */
function toUtf8(&$array) {
    if (is_array($array) && count($array) > 0) {
        foreach ($array as $key => $value) {
            $array[$key]['ID'] = (integer) $value['ID'];
            $array[$key]['NOME'] = Util::fixErrorString($value['NOME']);
        }
    } else {
        $array = array();
    }
    return $array;
}