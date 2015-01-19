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

            case 'detalhar':

                $usuario = current(CFModelUsuario::factory()->find($_REQUEST['id']));
                $unidades = CFModelUsuarioUnidade::factory()->retrieveUnitsAvailableByIdUser($_REQUEST['id']);

                $usuario->UNIDADES = $unidades;

                unset($usuario->SENHA);

                $out = array('success' => 'true', 'usuario' => $usuario);

                break;

            case 'salvar':

                //@todo Parametrizar...
                if (stripos($_REQUEST['USUARIO']['EMAIL'], '.gov.br') === false) {
                    $out = array('success' => 'false', 'error' => 'O email precisa ter necessariamente final .gov.br');
                    break;
                }

                $idUsuario = $_REQUEST['USUARIO']['ID'];

                $_REQUEST['USUARIO']['TELEFONE'] = preg_replace('/[^\d]/', '', $_REQUEST['USUARIO']['TELEFONE']);
                $_REQUEST['USUARIO']['CPF'] = preg_replace('/[^\d]/', '', $_REQUEST['USUARIO']['CPF']);

                try {

                    CFModelUsuario::factory()->beginTransaction();

                    if ($idUsuario) {
                        CFModelUsuario::factory()->update($_REQUEST['USUARIO']);
                    } else {
                        unset($_REQUEST['USUARIO']['ID']);
                        $idUsuario = CFModelUsuario::factory()->insert($_REQUEST['USUARIO']);
                    }

                    //desabilitar todos os vinculos de usuario com unidades...
                    CFModelUsuarioUnidade::factory()->disassociateAllByUserId($idUsuario);

                    //tratar unidades associadas ao usuario...
                    if (isset($_REQUEST['USUARIO']['UNIDADE'])) {
                        foreach ($_REQUEST['USUARIO']['UNIDADE'] as $idUnidade) {
                            if (CFModelUsuarioUnidade::factory()->isExists($idUsuario, $idUnidade)) {
                                CFModelUsuarioUnidade::factory()->updateUserAssociationWithUnit($idUsuario, $idUnidade, 1);
                            } else {
                                CFModelUsuarioUnidade::factory()->createUserAssociationWithUnit($idUsuario, $idUnidade);
                            }
                        }
                    }

                    CFModelUsuario::factory()->commit();

                    Controlador::getInstance()->cache->remove("acl_{$idUsuario}");
                    Controlador::getInstance()->cache->clean('matchingAnyTag', array("acl_usuario_{$idUsuario}"));

                    $out = array('success' => 'true', 'message' => 'Operação realizada com sucesso!');
                } catch (Exception $e) {
                    CFModelUsuario::factory()->rollback();

                    $error = 'Ocorreu um erro ao tentar salvar as informações do usuário!';

                    if (strpos($e->getMessage(), 'already exists')) {
                        $error = 'Verifique o se o USUÁRIO DO SISTEMA ou CPF já não estão cadastrados!';
                    }

                    $out = array('success' => 'false', 'error' => $error);
                }

                break;

            case 'alterar-status':
                $usuario = DaoUsuario::getUsuario((int) $_REQUEST['id'], '*', true);
                $usuario->status = $_REQUEST['status'];
                $out = DaoUsuario::alterarStatus($usuario)->toArray();
                break;

            default:
                $out = array('success' => 'false', 'error' => 'Ocorreu um erro na operação desejada!');
                break;
        }

        print(json_encode($out));
    } catch (Exception $e) {
        
    }
}