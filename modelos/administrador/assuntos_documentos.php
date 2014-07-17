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
    try {
        switch ($_POST['acao']) {
            case 'get':
                try {
                    /* TMP */
                    $out = DaoAssuntoDocumento::getAssunto($_POST['valor']);
                    $out['success'] = 'true';
                } catch (Exception $e) {
                    $out = array('success' => "false", 'error' => $e->getMessage());
                }
                break;
            case 'unique':
                $assunto = new AssuntoDocumento($_POST);
                $out = DaoAssuntoDocumento::uniqueAssunto($assunto)->toArray();
                break;

            case 'cadastrar':
                $assunto = new AssuntoDocumento($_POST);
                $out = DaoAssuntoDocumento::inserirAssunto($assunto)->toArray();
                break;

            case 'alterar':
                $assunto = new AssuntoDocumento($_POST);
                $out = DaoAssuntoDocumento::alterarAssunto($assunto)->toArray();
                break;

            case 'alterar-homologacao':
                try {
                    $id = $_POST['id'];
                    $status = $_POST['status'];
                    $out = DaoAssuntoDocumento::alterarHomologacao($id, $status)->toArray();
                } catch (Exception $e) {
                    $out = array('success' => 'false', 'error' => $e->getMessage());
                }
                break;

            case 'alterar-real':
                try {
                    $ids = $_POST['assunto_d'];
                    $real = $_POST['id_assunto_real'];
                    $out = DaoAssuntoDocumento::alterarAssuntoReal($ids, $real)->toArray();
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