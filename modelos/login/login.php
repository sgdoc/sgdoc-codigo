<?php

/**
 * 
 * Copyright 2011 ICMBio
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
 *
 */
if ($_REQUEST) {

    $response = array();

    /**
     * @example C001    !falha ao atualizar informações do usuário na base local!
     * @example C002    !falha ao criar usuário na base local!
     * @example C003.1  !falha ao autenticar na base local quando o adapter de sincronismo de usuários esta ativado!
     * @example C003.2  !falha ao autenticar na base local quando o adaptar de sincronismo de usuários esta desativado!
     * @example C004.1  !falha ao autenticar na base externa quando o adaptar de sincronismo de usuários esta ativado!
     * @example C004.2  !falha ao autenticar na base externa quando o adaptar de sincronismo de usuários esta desativado!
     * @example C005    !falha nos meta dados do usuário da base externa!
     * @example C006    !usuário não existe na base externa!
     * @example C007    !erro genérico lançado por alguma excessão!
     * @example C008    !falha na autenticar quando o adapter de autenticação esta ativo!
     * @example C009    !usuário inativo!
     */
    //verificar se o captchar esta setado ou se esta correto...
    if (!Authentication::validateCaptcha($_REQUEST['CAPTCHA'])) {
        $response = array('success' => false, 'error' => 'Código inválido!');
    }

    $adapterAuthName = __ADAPTER_AUTENTICACAO__;

    //verificar se a autenticacao eh local ou externa...
    if ($adapterAuthName != '') {

        try {

            $adapterAuth = new $adapterAuthName();

            //verificar se o usuario existe!
            if (Authentication::factory($adapterAuth)->isUser($_REQUEST['USUARIO'])) {

                $adapterSyncUserName = __ADAPTER_SINCRONIZACAO_USUARIO__;

                if ($adapterSyncUserName != '') {

                    $synchronizerUser = SynchronizerUser::factory(new $adapterSyncUserName());

                    $user = $synchronizerUser->loadUser($_REQUEST['USUARIO']);

                    //@todo remover este comando apos CMB prover esta informacao... 
                    if (!$user->CPF) {
                        $user->CPF = '84475721539';
                    }

                    //verifica se o usuario esta sincronizado com o webservice externo!
                    if ($synchronizerUser->isValid($user)) {
                        if ($user->STATUS == 0) {
                            $response = array('success' => false, 'error' => 'Usuário inativo! [C009]');
                        } else {

                            //verificar se o usuario necessita ser atualizado!
                            if ($synchronizerUser->checkUser($user)) {

                                //se o usuario estiver desatualizado entao atualiza
                                if (!$synchronizerUser->updateUser($user)) {
                                    $response = array('success' => false, 'error' => 'Falha ao efetuar a autenticacao! [C001]');
                                }
                            } else {
                                if (!$synchronizerUser->createUser($user)) {
                                    $response = array('success' => false, 'error' => 'Falha ao efetuar a autenticacao! [C002]');
                                }
                            }

                            //validar e conceder acesso!
                            if (Authentication::factory($adapterAuth)->validateUserExternal($_REQUEST['USUARIO'], $_REQUEST['SENHA'])) {
                                if (Authentication::factory($adapterAuth)->validateUserLocal($_REQUEST['USUARIO'], $_REQUEST['SENHA'], Config::factory()->buildDBConfig()->getZendDbTable(), true)) {

                                    if (__ADAPTER_SINCRONIZACAO_PERMISSAO__ != '') {
                                        $adapterSyncPermissionName = __ADAPTER_SINCRONIZACAO_PERMISSAO__;
                                        SynchronizerPermission::factory(new $adapterSyncPermissionName())->reload($_REQUEST['USUARIO']);
                                    }

                                    $response = array('success' => true, 'url' => '');
                                } else {
                                    $response = array('success' => false, 'error' => 'Falha ao efetuar a autenticacao! [C003.1]');
                                }
                            } else {
                                $response = array('success' => false, 'error' => 'Falha ao efetuar a autenticacao! [C004.1]');
                            }
                        }
                    } else {
                        $response = array('success' => false, 'error' => 'Falha ao efetuar a autenticacao! [C005]');
                    }
                } else {

                    //validar e conceder acesso!
                    if (Authentication::factory($adapterAuth)->validateUserExternal($_REQUEST['USUARIO'], $_REQUEST['SENHA'])) {
                        if (Authentication::factory($adapterAuth)->validateUserLocal($_REQUEST['USUARIO'], $_REQUEST['SENHA'], Config::factory()->buildDBConfig()->getZendDbTable(), true)) {

                            if (__ADAPTER_SINCRONIZACAO_PERMISSAO__ != '') {
                                $adapterSyncPermissionName = __ADAPTER_SINCRONIZACAO_PERMISSAO__;
                                SynchronizerPermission::factory(new $adapterSyncPermissionName())->reload($_REQUEST['USUARIO']);
                            }

                            $response = array('success' => true, 'url' => '');
                        } else {
                            $response = array('success' => false, 'error' => 'Falha ao efetuar a autenticacao! [C003.2]');
                        }
                    } else {
                        $response = array('success' => false, 'error' => 'Falha ao efetuar a autenticacao! [C004.2]');
                    }
                }
            } else {
                $response = array('success' => false, 'error' => 'Falha ao efetuar a autenticacao! [C006]');
            }
        } catch (Exception $e) {
            $response = array('success' => false, 'error' => 'Falha ao efetuar a autenticacao! [C007]');
        }
    } else {
        if (Authentication::factory()->validateUserLocal($_REQUEST['USUARIO'], $_REQUEST['SENHA'], Config::factory()->buildDBConfig()->getZendDbTable())) {
            $response = array('success' => true, 'url' => '');
        } else {
            $response = array('success' => false, 'error' => 'Falha ao efetuar a autenticacao! [C008]');
        }
    }

    print json_encode($response);
}