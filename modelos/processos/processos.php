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
            case 'cadastrar':
                try {
//                return '{teste:teste}';
                    $processo = new Processo();
                    $processo->prepararCadastro($_REQUEST['numero_processo'], $_REQUEST['interessado'], $_REQUEST['assunto'], $_REQUEST['assunto_complementar'], $_REQUEST['tipo_origem'], $_REQUEST['origem'], $_REQUEST['data_autuacao'], $_REQUEST['data_prazo']);
                    $processo->salvarCadastro();
                    $processo->printJson();
                } catch (Exception $e) {
                    print(json_encode(array('success' => 'false', 'error' => $e->getMessage())));
                }
                break;

            case 'autuar':
                try {
                    $processo = new Processo();
                    $processo->prepararAutuacao($_REQUEST['digital'], (integer) $_REQUEST['interessado'], (integer) $_REQUEST['assunto'], $_REQUEST['assunto_complementar'], (integer) $_REQUEST['origem'], $_REQUEST['dt_prazo']);
                    $processo->salvarAutuacao();
                    $processo->printJson();
                } catch (Exception $e) {
                    print(json_encode(array('success' => 'false', 'error' => $e->getMessage())));
                }
                break;

            case 'alterar':
                $processo = new Processo($_REQUEST);
                $out = DaoProcesso::alterarProcesso($processo)->toArray();
                print(json_encode($out));
                break;

            case 'verificar-processo':
                $out = Processo::existeProcessoCadastrado($_REQUEST['numero_processo']);
                print(json_encode($out));
                break;

            case 'pegar-processo':
                $out = Processo::existeProcessoCadastrado($_REQUEST['numero_processo'], true);
                print(json_encode($out));
                break;

            case 'limpar-volumes':
                $out = Volume::factory($_REQUEST)->limparVolumes()->toArray();
                print(json_encode($out));
                break;

            case 'carregar':
                $processo = DaoProcesso::getProcesso($_REQUEST['numero_processo']);
                $processo['dt_prazo'] = Util::formatDate($processo['dt_prazo']);
                $processo['dt_autuacao'] = Util::formatDate($processo['dt_autuacao']);
                $processo['fg_prazo'] = ($processo['fg_prazo'] > 0) ? true : false;
                if (is_array($processo)) {
                    $out = array('success' => 'true', 'processo' => $processo);
                } else {
                    $out = array('success' => 'false');
                }
                print(json_encode($out));
                break;

            case 'adicionar-comentario':
                $comentario = new Comentario(array(numero_processo => $_REQUEST['numero_processo'], texto => $_REQUEST['texto']));
                $out = DaoComentario::inserirComentarioProcesso($comentario)->toArray();
                print(json_encode($out));
                break;

            case 'adicionar-despacho':
                $despacho = new Despacho(array(numero_processo => $_REQUEST['numero_processo'], assinatura => $_REQUEST['assinatura'], texto => $_REQUEST['texto'], complemento => $_REQUEST['complemento'], data_despacho => $_REQUEST['data_despacho']));
                $out = DaoDespacho::inserirDespachoProcesso($despacho)->toArray();
                print(json_encode($out));
                break;

            case 'pesquisar':
                unset($_SESSION['PESQUISAR_PROCESSOS']);

                $_SESSION['PESQUISAR_PROCESSOS_QUERY_PEDIODO']['dt_inicial'] = '0001-01-01';
                $_SESSION['PESQUISAR_PROCESSOS_QUERY_PEDIODO']['dt_final'] = date('Y-m-d');
                $_SESSION['PESQUISAR_PROCESSOS_QUERY_PEDIODO']['tp_periodo'] = 'DT_CADASTRO';

                foreach ($_REQUEST as $key => $value) {
                    if ($key != 'acao' && $value && $key != 'dt_inicial' && $key != 'dt_final' && $key != 'tp_periodo' && $key != 'tp_pesquisa') {
                        $_SESSION['PESQUISAR_PROCESSOS'][$key] = $value;
                    } else {
                        if ($value != '') {
                            $_SESSION['PESQUISAR_PROCESSOS_QUERY_PEDIODO'][$key] = $value;
                        }
                    }
                }
                $out = array('success' => 'true');
                print(json_encode($out));
                break;

            default:
                $out = array('success' => 'false', 'error' => 'Opcao Invalida!');
                print(json_encode($out));
                break;
        }
    } catch (Exception $e) {
        
    }
}