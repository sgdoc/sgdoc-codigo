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

include(__BASE_PATH__ . '/extensoes/pr_snas/1.2/classes/Prazo.php');
include(__BASE_PATH__ . '/extensoes/pr_snas/1.2/classes/DaoPrazoDemanda.php');
include(__BASE_PATH__ . '/extensoes/pr_snas/1.2/classes/UploaderPdfResposta.php');

if ($_POST) {

    try {

        $out = array();

        switch ($_POST['acao']) {
            case 'carregar-prazo':
                $sq_prazo = $_POST['id'];
                $out['resposta'] = DaoPrazoDemanda::getPrazo($sq_prazo);
                $out['resposta'] = $out['resposta'];
                $out['success'] = 'true';
            break;

            case 'carregar-prazo-resposta':
            	$sq_prazo = $_POST['id'];
            	$out['resposta'] = DaoPrazoDemanda::getPrazoResposta($sq_prazo);
            	$out['resposta'] = $out['resposta'];
            	$out['success'] = 'true';
            break;
            
        	case 'carregar-resposta':
                $sq_prazo = $_POST['id'];
                /* TMP */
                $out['resposta'] = DaoPrazoDemanda::getPrazo($sq_prazo, "TX_RESPOSTA");
                $out['resposta'] = $out['resposta'];
                $out['success'] = 'true';

                break;

            case 'carregar-solicitacao':
                $sq_prazo = $_POST['id'];
                /* TMP */
                $out['solicitacao'] = DaoPrazoDemanda::getPrazo($sq_prazo, "TX_SOLICITACAO");
                $out['solicitacao'] = $out['solicitacao'];
                $out['success'] = 'true';

                break;

            case 'cadastrar':
                try {
                    $prazo = new Prazo($_POST);
                    $out = DaoPrazoDemanda::salvarPrazo($prazo)->toArray();
                } catch (Exception $e) {
                    $out = array('success' => 'false', 'error' => $e->getMessage());
                }

                break;

            case 'pesquisar':
                unset($_SESSION['PESQUISAR_PRAZOS']);

                foreach ($_POST as $key => $value) {
                    if ($key != 'acao' && $value != '' && $key != 'dt_prazo' && $key != 'dt_resposta' && $key != 'tp_periodo' && $key != 'tp_pesquisa') {
                        $_SESSION['PESQUISAR_PRAZOS'][$key] = $value;
                    } else {
                        if ($value != '') {
                            $_SESSION['PESQUISAR_PRAZOS_QUERY_PERIODO'][$key] = $value;
                        }
                    }
                }
                $out = array('success' => 'true');
                break;
                
			case 'salvar-resposta':
				try {
                	$prazo = new Prazo($_POST);
                	$out = DaoPrazoDemanda::salvarRespostaPrazo($prazo)->toArray();
                } catch (Exception $e) {
                	$out = array('success' => 'false', 'error' => $e->getMessage());
                }
            break;
                
            case 'responder-prazo':
                try {
                    $prazo = new Prazo($_POST);
                    $out = DaoPrazoDemanda::responderPrazo($prazo)->toArray();
                } catch (Exception $e) {
                    $out = array('success' => 'false', 'error' => $e->getMessage());
                }

            break;
            
            case 'salvar-resposta-ppa':
	            try {
	            	$prazo = new Prazo($_POST);
	            	$out = DaoPrazoDemanda::salvarPpaResposta($prazo)->toArray();
	            } catch (Exception $e) {
	            	$out = array('success' => 'false', 'error' => $e->getMessage());
	            }
	            
            break;
            
            case 'excluir-resposta-ppa':
	            try {
	            	$idVinculo = $_POST['id_vinculo'];
	            	$out = DaoPrazoDemanda::excluirPpaResposta($idVinculo)->toArray();
	            } catch (Exception $e) {
	            	$out = array('success' => 'false', 'error' => $e->getMessage());
	            }
	            
            break;
            
            case 'incluir-anexo-resposta':
            	try {
            		Session::set('_upload', array('digital' => $_POST['hdnUploadDigital']));
            		//var_dump($_FILES['inpFileUpload']);
            		if ($_FILES['inpFileUpload']['type'] == '') {
            			$arrNome = explode('.', $_FILES['inpFileUpload']['name']);
            			//var_dump($arrNome); die;
            			$_FILES['inpFileUpload']['type'] = array_pop($arrNome);
            		}
            		$arquivo = new UploaderPdfResposta($_FILES['inpFileUpload']);
            		$arquivo->idPrazo = $_POST['hdnUploadPrazo'];
            		$arquivo->upload();
            		$out = DaoPrazoDemanda::incluirAnexoResposta($arquivo)->toArray();
            	} catch (Exception $e) {
            		$out = array('success' => 'error', 'error' => $e->getMessage());
            	}
            
            break;
            
            case 'excluir-anexo-resposta':
            	try {
            		$idAnexo = $_POST['id_anexo'];
            		$out = DaoPrazoDemanda::excluirAnexoResposta($idAnexo)->toArray();
            	} catch (Exception $e) {
            		$out = array('success' => 'false', 'error' => $e->getMessage());
            	}
            	 
            break;
            
            case 'carregar-demanda':
            	try {
            		$digDemanda = $_POST['nu_proc_dig_ref'];
            		$out = DaoPrazoDemanda::getPrimeiroPrazo($digDemanda);
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
        $erro = new Output(array('success' => 'false', 'error' => $e->getMessage()));
        print(json_encode($erro->toArray()));
    }
}