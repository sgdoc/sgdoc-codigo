<?php

include(__BASE_PATH__ . '/extensoes/pr_snas/1.7/classes/DaoDadosSiop.php');

if ($_POST) {

	try {
		
		$out = array();
		switch ($_POST['acao']) {
			case 'listar-programas':
				try {
					$out = DaoDadosSiop::getProgramas($_POST['unidade'], $_POST['ano']);
				} catch (Exception $e) {
					$out = array('success' => 'false', 'error' => $e->getMessage());
				}
			break;

			case 'buscar-programa-vinculado':
				try {
					$out = DaoDadosSiop::getProgramaVinculado($_POST['vinculo']);
				} catch (Exception $e) {
					$out = array('success' => 'false', 'error' => $e->getMessage());
				}
			break;
			
			case 'detalhar-meta':
				try {
					$out = DaoDadosSiop::getObjetivoMetaVinculado($_POST['vinculo']);
				} catch (Exception $e) {
					$out = array('success' => 'false', 'error' => $e->getMessage());
				}
			break;

			case 'detalhar-acao':
				try {
					$out = DaoDadosSiop::getAcaoVinculado($_POST['vinculo']);
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