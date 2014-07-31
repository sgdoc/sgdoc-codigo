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

if ($_REQUEST) {

    try {

        switch ($_REQUEST['acao']) {
            case 'get':
                try {
                    /* TMP */
                    $out = DaoUnidade::getUnidade($_REQUEST['valor'], $_REQUEST['campo']);
                    if (!$_REQUEST['campo'] || $_REQUEST['campo'] == '*') {
                        $out['success'] = 'true';
                    }
                } catch (Exception $e) {
                    $out = array('success' => 'false', 'error' => $e->getMessage());
                }
                break;

            case 'alterar-status':
                $out = DaoUnidade::deleteUnidade($_REQUEST['id'], $_REQUEST['status'])->toArray();
                break;

            case 'alterar-visibilidade':
                try {
                    $out = Tramite::alterarVisibilidadeTramite($_REQUEST['id_unidade'], $_REQUEST['id_referencia'], $_REQUEST['status']);
                } catch (Exception $e) {
                    $out = array('success' => 'false', 'error' => $e->getMessage());
                }
                break;

            case 'cadastrar':
                $unidade = new Unidade($_REQUEST);
                $out = DaoUnidade::inserirUnidade($unidade)->toArray();
                break;

            case 'alterar':
                $unidade = new Unidade($_REQUEST);

                $out = DaoUnidade::alterarUnidade($unidade)->toArray();

                if ($_REQUEST['tipo'] != $_REQUEST['clear']) {
                    DaoTramite::clearAllTramitesByIdUnidade($_REQUEST['id']);
                }
                break;

            case 'pesquisar':
                try {
                    unset($_SESSION['PESQUISAR_UNIDADES']);
                    foreach ($_REQUEST as $key => $value) {
                        if ($key != 'acao' && $value && strtolower($value) != 'null') {
                            $_SESSION['PESQUISAR_UNIDADES'][$key] = $value;
                        }
                    }
                    $out = array('success' => 'true');
                } catch (Exception $e) {
                    $out = array('success' => 'false', 'error' => $e->getMessage());
                }
                break;

            case 'regras-tramite':
                try {
                    $tramite = new Tramite();

                    $defaultList = array();
                    $default = $tramite->getListTramites($_REQUEST['id_unidade']);


                    /**
                     * Indexar
                     */
                    if (count($default) > 0) {
                        foreach ($default as $value) {
                            $defaultList[$value['id']] = $value;
                        }
                    }

                    /**
                     * GetKeys
                     */
                    $roles = array_keys($defaultList);

                    $out = array('success' => 'true', 'roles' => $roles);
                } catch (Exception $e) {
                    $out = array('success' => 'false', 'error' => $e->getMessage());
                }

                break;

            default:
                $out = array('success' => 'false', 'error' => 'Opção Inválida!');
                break;
        }
    } catch (Exception $e) {
        $out = array('success' => 'false', 'error' => $e->getMessage());
    }
    print(json_encode($out));
}    