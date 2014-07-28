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

//include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.0/classes/DaoDocumentoDemanda.php');
//include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.0/classes/CFModelDocumentoDemanda.php');
include_once(__BASE_PATH__ . '/extensoes/serpro/1.0/classes/CFModelDocumentoSerpro.php');
include_once(__BASE_PATH__ . '/extensoes/serpro/1.0/classes/DaoDocumentoSerpro.php');

if ($_POST) {

    try {
        $out = array();

        switch ($_POST['acao']) {
            case 'carregar':
                $documento = DaoDocumentoSerpro::getDocumento($_REQUEST['digital']);

                $output = new Output($documento);
                
                if (!empty($output)) {
                    $out = array('success' => 'true', 'documento' => $output->toArray());
                } else {
                    $out = array('success' => 'false');
                }

                break;

            case 'alterar':
                $documento = new Documento($_POST);
                $out = DaoDocumento::alterarDocumento($documento)->toArray();

                if (is_array($documento)) {
                    $out['documento'] = $documento;
                }

                break;

            case 'unique':
                $documento = new Documento($_POST);
                $out = DaoDocumento::uniqueDocumento($documento)->toArray();
                break;
            case 'adicionar-comentario':
                $comentario = new Comentario(array('digital' => $_POST['digital'], 'texto' => $_POST['texto']));
                $out = DaoComentario::inserirComentarioDocumento($comentario)->toArray();
                break;

            case 'adicionar-despacho':
                $despacho = new Despacho(array('digital' => $_POST['digital'], 'assinatura' => $_POST['assinatura'], 'texto' => $_POST['texto'], 'complemento' => $_POST['complemento'], 'data_despacho' => $_POST['data_despacho']));
                $out = DaoDespacho::inserirDespachoDocumento($despacho)->toArray();
                break;

            case 'pesquisar':
                unset($_SESSION['PESQUISAR_DOCUMENTOS']);

                $_SESSION['PESQUISAR_DOCUMENTOS_QUERY_PEDIODO']['dt_inicial'] = '0001-01-01';
                $_SESSION['PESQUISAR_DOCUMENTOS_QUERY_PEDIODO']['dt_final'] = date('Y-m-d');
                $_SESSION['PESQUISAR_DOCUMENTOS_QUERY_PEDIODO']['tp_periodo'] = 'DT_CADASTRO';

                if (substr($_POST['numero'], 0, 1) == '0') {
                    $_POST['numero'] = substr($_POST['numero'], 1);
                }

                foreach ($_POST as $key => $value) {
                    if ($key != 'acao' && ($value && $value != 'null') && $key != 'dt_inicial' && $key != 'dt_final' && $key != 'tp_periodo' && $key != 'tp_pesquisa') {
                        $_SESSION['PESQUISAR_DOCUMENTOS'][$key] = $value;
                    } else {
                        if ($value != '') {
                            $_SESSION['PESQUISAR_DOCUMENTOS_QUERY_PEDIODO'][$key] = $value;
                        }
                    }
                }
                $out = array('success' => 'true');
                break;

            default:
                $out = array('success' => 'false', 'error' => 'Opção Inválida!');
                break;
        }

        print(json_encode($out));
    } catch (Exception $e) {
        print(json_encode(array('success' => 'false', 'error' => $e->getMessage())));
    }
}