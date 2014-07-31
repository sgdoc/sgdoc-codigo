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

/**
 * @todo Encapsular...
 */
if ($_REQUEST) {

    try {

        switch ($_REQUEST['acao']) {

            case 'quantidade':
                $quantidade = Documento::getQuantidadeImagemDocumento($_REQUEST['digital']);
                $out = array('success' => 'true', 'quantidade' => $quantidade);
                break;

            case 'upload':

                Session::set('_upload', array( 
                    'digital' => $_REQUEST['nu_digital'], 
                    'fg_publico' => $_REQUEST['fg_publico'],
                    'fg_operacao' => $_REQUEST['fg_operacao']
                ));

                if (is_array(Session::get('_upload'))) {
                    $out = array('success' => 'true');
                } else {
                    $out = array('success' => 'false');
                }

                break;

            case 'alterar-status-documento-imagem':
                /* Valida se a operacao esta autorizada */
                if ( AclFactory::checaPermissao( 
                        Controlador::getInstance()->acl, 
                        Controlador::getInstance()->usuario, 
                        DaoRecurso::getRecursoById(998) ))
                {
                    if (isset($_POST)) {
                        $status = $_POST['status'];
                        $digitais = $_POST['digitais'];
                        foreach ($digitais as $digital) {                            
                            $out = DaoDocumento::updateStatusDocumentosImagens( $digital, null, $status );
                        }
                    } else {
                        $out = array('success' => 'false', 'error' => Util::fixErrorString('Informações importantes estão ausentes!'));
                    }
                } else {
                    $out = array('success' => 'false', 'error' => Util::fixErrorString('Acesso negado!'));
                }
                break;

            /**
             * @deprecated
             * Uma vez que os arquivos PDF serão tratados como documento, ou o documento é confidencial ou é público
             */
            case 'alterar-status-imagem':
                /* Validar se a operacao esta autorizada */
                if (AclFactory::checaPermissao(
                                Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(998)
                        )) {
                    if (isset($_REQUEST)) {

                        $hashs = explode(',', $_REQUEST['hash']);

                        foreach ($hashs as $hash) {
                            $hash = explode('|', $hash);
                            $out = DaoDocumento::updateStatusDocumentosImagens($hash[1], $hash[0], $_REQUEST['status']);
                        }
                    } else {
                        $out = array('success' => 'false', 'error' => Util::fixErrorString('Informações importantes estão ausentes!'));
                    }
                } else {
                    $out = array('success' => 'false', 'error' => Util::fixErrorString('Acesso negado!'));
                }
                break;

            case 'atualiza-ordem':
                /* Validar se a operacao esta autorizada */
                if (AclFactory::checaPermissao(
                                Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(998)
                        )) {

                    if (isset($_REQUEST)) {

                        $extraConditional = '';
                        $temporaryOrdemValue = 2222;
                        $fieldValue = '';
                        try {
                            // Atribui o valor 2222 para o campo ORDEM (temporariamente) à imagem que vai mudar de lugar.
                            Imagens::updateImagensFieldByDigital('ORDEM', $temporaryOrdemValue, $_REQUEST['digital'], " AND ORDEM = {$_REQUEST['oldPos']} ");

                            if (!empty($_REQUEST['newPosPrev']) && !empty($_REQUEST['newPosNext'])) {

                                $newPos = $_REQUEST['newPosPrev'];

                                if ($_REQUEST['newPosPrev'] > $_REQUEST['oldPos']) {
                                    $fieldValue = 'ORDEM - 1';
                                    $extraConditional = " AND ORDEM > {$_REQUEST['oldPos']} AND ORDEM <= {$_REQUEST['newPosPrev']} ";
                                } else {
                                    $newPos++;
                                    $fieldValue = 'ORDEM + 1';
                                    $extraConditional = " AND ORDEM < {$_REQUEST['oldPos']} AND ORDEM > {$_REQUEST['newPosPrev']} ";
                                }
                            }

                            // se não tem imagem anterior então estamos mudando a imagem atual para a primeira posição
                            if (empty($_REQUEST['newPosPrev'])) {
                                $newPos = $_REQUEST['newPosNext'];
                                $fieldValue = 'ORDEM + 1';
                                $extraConditional = " AND ORDEM < {$_REQUEST['oldPos']} ";
                            }

                            // se não tem próxima imagem então estamos mudando a imagem atual para a derradeira posição
                            if (empty($_REQUEST['newPosNext'])) {
                                $newPos = $_REQUEST['newPosPrev'];
                                $fieldValue = 'ORDEM - 1';
                                $extraConditional = " AND ORDEM > {$_REQUEST['oldPos']} ";
                            }

                            Imagens::updateImagensFieldByDigital('ORDEM', $fieldValue, $_REQUEST['digital'], $extraConditional);

                            Imagens::updateImagensFieldByDigital('ORDEM', $newPos, $_REQUEST['digital'], " AND ORDEM = {$temporaryOrdemValue} ");
                            $out = array('success' => 'true');
                        } catch (Exception $e) {
                            $out = array('success' => 'false', 'error' => Util::fixErrorString($e->getMessage()));
                        }
                    } else {
                        $out = array('success' => 'false', 'error' => Util::fixErrorString('Informações importantes estão ausentes!'));
                    }
                } else {
                    $out = array('success' => 'false', 'error' => Util::fixErrorString('Acesso negado!'));
                }
                break;

            default:
                $out = array('success' => 'false', 'error' => 'Opcao Invalida!');
                break;
        }

        print(json_encode($out));
    } catch (Exception $e) {
        /* Obs: Metodos que lancam excessao do tipo BasePDOException ja serao tratadas automaticamente */
        print(json_encode($out = array('success' => 'false', 'error' => "Ocorreu um erro :: [{$e->getMessage()}]")));
    }
}