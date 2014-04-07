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

if ($_POST) {
    $out = "";
    try {

        switch ($_POST['acao']) {
            case 'get':
                try {
                    /* TMP */
                    $out = DaoCaixa::getCaixa($_POST['valor']);
                    $out['success'] = 'true';
                } catch (Exception $e) {
                    $out = array('success' => 'false', 'error' => $e->getMessage());
                }
                break;
            case 'alterar-status':
                $out = DaoCaixa::deleteCaixa($_POST['id'], $_POST['status'])->toArray();
                break;
            case 'alterar-finalizacao':
                $out = DaoCaixa::alterarFinalizada($_POST['id'], $_POST['st_finalizada'])->toArray();
                break;
            case 'cadastrar':
                try {
                    $caixa = new Caixa($_POST);
                    $out = DaoCaixa::inserirCaixa($caixa)->toArray();
                } catch (Exception $e) {
                    $out = array('success' => 'false', 'error' => $e->getMessage());
                }
                break;
            case 'alterar':
                $caixa = new Caixa($_POST);
                $out = DaoCaixa::alterarCaixa($caixa)->toArray();
                break;
            case 'unique':
                $caixa = new Caixa($_POST);
                $out = DaoCaixa::uniqueCaixa($caixa)->toArray();
                break;
            case 'adicionar-documento':
                // inserir documento na caixa
                $id_caixa = $_POST['id_caixa'];
                $id_documento = $_POST['id_documento'];
                $out = DaoCaixa::colocarDocumento($id_caixa, $id_documento)->toArray();
                break;
            case 'retirar-documento':
                // inserir documento na caixa
                $id = $_POST['id'];
                $out = DaoCaixa::retirarDocumento($id)->toArray();
                break;
            case 'pesquisar':
                try {
                    unset($_SESSION['PESQUISAR_CAIXAS']);
                    foreach ($_POST as $key => $value) {
                        if ($key != 'acao' && ($value !== FALSE && $value != 'null')) {
                            $_SESSION['PESQUISAR_CAIXAS'][$key] = $value;
                        }
                    }
                    $out = array('success' => 'true');
                } catch (Exception $e) {
                    $out = array('success' => 'false', 'error' => $e->getMessage());
                }
                break;
            case 'pesquisar-documentos':
                try {
                    unset($_SESSION['PESQUISAR_DOCS_CAIXAS']);
                    foreach ($_POST as $key => $value) {
                        if ($key != 'acao' && ($value !== FALSE && $value != 'null')) {
                            $_SESSION['PESQUISAR_DOCS_CAIXAS'][$key] = $value;
                        }
                    }
                    $out = array('success' => 'true');
                } catch (Exception $e) {
                    $out = array('success' => 'false', 'error' => $e->getMessage());
                }
                break;
            default:
                $out = array('success' => 'false', 'error' => 'Opcao Invalida!');
                break;
        }

        print(json_encode($out));
    } catch (Exception $e) {
        LogError::sendReport($e);
        $out = array('success' => 'false', 'error' => $e->getMessage());
        print(json_encode($out));
    }
}