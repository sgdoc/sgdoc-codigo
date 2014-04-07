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

$auth = Zend_Auth::getInstance()->getStorage()->read();
$login = Login::factory();

switch ($_REQUEST['action']) {
    case 1: // Action 1: Criar url para renovar senha...
        $cpf = $_REQUEST['cpf_usuario'];

        $resul = $login->validarCPF($cpf);
        if (is_null($resul)) {
            $out = array('success' => 'false', 'msg' => 'CPF não cadastrado no sistema!, verifique se foi digitado corretamente.', 'session' => false);
        } else {
            $url = $login->makeUrl($resul['ID']);
            if ($login->sendEmail($resul['EMAIL'], $resul['NOME'], $url)) {
                $out = array('success' => 'true', 'msg' => 'Uma mensagem foi enviada ao seu email, verifique as instruções para recuperar sua senha.', 'session' => false);
            } else {
                $out = array('success' => 'true', 'msg' => 'Ocorreu um erro na sua solicitação, contate o administrador do sistema.', 'session' => false);
            }
        }
        break;

    case 2: // Action 2: Validar hash da url e trocar senha do usuario...

        if ($_REQUEST['hash'] != '') {
            $resul = $login->validateHash($_REQUEST['hash']);
            if ($resul == null || $resul['STATUS'] == 1 || $login->diffDate($resul['DT_SOLICITADA'])) {
                $out = array('success' => 'false', 'msg' => 'Atenção: A chave de ativação com as quais você solicitou esta página e inválida ou expirou. Clique no botão OK para solicitar uma nova chave.', 'session' => false);
            } else {
                if ($login->updatePass($_REQUEST['senha'], $resul['ID_USUARIO'])) {
                    $resul = $login->getCpf($resul['ID_USUARIO']);
                    $out = array('success' => 'true', 'msg' => "Senha alterada com sucesso, um ou mais usuários com CPF {$resul['CPF']} foram alterados. Clique no botão OK para efetuar login.", 'session' => false);
                    $login->updateHash($_REQUEST['hash']);
                }
            }
        } elseif ($auth->ID) {
            if ($_REQUEST['acesso'] != '') {
                $login->logAcesso($auth->ID);
            }
            $login->updatePass($_REQUEST['senha'], $auth->ID);
            $resul = $login->getCpf($auth->ID);
            $out = array('success' => 'true', 'msg' => "Senha alterada com sucesso, um ou mais usuários com CPF {$resul['CPF']} foram alterados. Clique no botão OK para efetuar login.", 'session' => false);
        } else {
            $out = array('success' => 'false', 'msg' => 'Atenção: Ação inválida!', 'session' => false);
        }
        break;

    default:
        $out = array('success' => 'false', 'msg' => 'Ocorreu um erro na sua solicitação, contate o administrador do sistema.', 'session' => false);
        break;
}

print(json_encode($out));