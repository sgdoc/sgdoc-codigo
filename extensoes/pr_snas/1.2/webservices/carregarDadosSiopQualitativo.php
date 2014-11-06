<?php

include_once(dirname(__FILE__) . '/include.soap.php');

echo "Qualitativo\n";

/*
 * Define que dados serão buscados no webservice. 
 * Utilizar quando precisar pegar apenas um tipo de dado específico.
 */

$baixar = array(
		'orgaos' => TRUE,
		'programas' => TRUE,
		'acoes' => TRUE,
		'objetivos' => TRUE,
		'metas' => TRUE
);
		
if(isset($qualitativo) && $qualitativo !== '') {
	foreach($baixar as $area => $valor) {
		$baixar[$area] = ($area == $qualitativo);		
	}
}

if($baixar['orgaos']) {
	/**
	 * ===================================================================
	 * Obtendo a base de dados de órgãos para o ano de exercício repassado
	 * 
	*/
	echo "Limpando base de dados dos programas para o ano de {$exercicio}.\n";
	//limparDadosSiop('orgaos', "exercicio = '{$exercicio}'");
	echo "Obtendo órgãos.\n";
	$orgaosSiorg = obterOrgaosSgbio();
	$totalRegistros = 0;
	foreach($orgaosSiorg as $k => $j) {
		$codigoSiorg = $j['CODIGOSIORG'];			
		do {
			echo "Obtendo dados do orgão {$codigoSiorg}. ";
			$orgaosAux = obterOrgaosPorCodigoSiorgAnoExercicio($codigoSiorg, $exercicio);
			if(!$orgaosAux['sucesso']) {		
				echo "Houve um problema:\n\n";
				echo str_replace("<br>", "\n", $orgaosAux['mensagensErro']);
				echo "\n";
			} else {
				$numRegistros = 0;
				foreach($orgaosAux['registros'] as $orgao) {
					if(isset($orgao['tipoOrgao'])) {
						$orgao['orgaoSiorg'] = $codigoSiorg;
						//print_r($orgao);
						salvarDadosSiop('orgaos', $orgao);
						$numRegistros++;
						$totalRegistros++;
					}						
				}
				echo "Registros salvos: {$numRegistros}.\n";
			}
		} while (!$orgaosAux['sucesso']);
	}
	echo "Total de órgãos registrados: {$totalRegistros}. \n";
}

if($baixar['programas']) {
	/**
	 * ======================================================================
	 * Obtendo a base de dados de programas para o ano de exercício repassado
	 * 
	*/
	echo "Obtendo programas.\n";
	$orgaosSiop = obterOrgaosSiopPorExercicio($exercicio);
	$programas = array();
	$camposPrograma = obterCampos('programas');	
	$totalRegistros = 0;
	foreach($orgaosSiop as $k => $j) {
		$codigoSiop = $j['CODIGOSIOP'];
		do {
			echo "Obtendo programas do orgão {$codigoSiop}.\n";
			$programasAux = obterProgramasPorOrgaoAnoExercicio($codigoSiop, $exercicio);
			if(!$programasAux['sucesso']) {
				echo "\tNão foi possível obter os dados dos programas:\n\n";
				echo str_replace("<br>", "\n", $programasAux['mensagensErro']);
				echo "\n";
			} else {
				foreach($programasAux['registros'] as $programa) {
					$programa['codigoOrgao'] = $codigoSiop;
					$chvPrograma = $programa['codigoPrograma'];
					foreach($camposPrograma as $chave => $tipo) {
						$default = '';
						if($tipo == \PDO::PARAM_INT) {
							$default = 0;
						}
						if($tipo == \PDO::PARAM_BOOL) {
							$default = false;
						}
						$programas["{$chvPrograma}:{$codigoSiop}"][$chave] = retornaValorValido($programa, $chave, $default);  
					}
					reset($camposPrograma);	
				}
			}
		} while(!$programasAux['sucesso']); 
	}		
	echo "Programas obtidos. Limpando base de dados dos programas para o ano de {$exercicio}.\n";
	limparDadosSiop('programas', "exercicio = '{$exercicio}'");
	foreach($programas as $programa) {
		salvarDadosSiop('programas', $programa);			
	}	
	echo "Dados dos programas salvados.\n";
	reset($programas);
}

if($baixar['acoes']) {
	/**
	 * ======================================================================
	 * Obtendo a base de dados de ações para o ano de exercício repassado
	 * 
	*/
	echo "Limpando base de dados das ações para o ano de {$exercicio}.\n";
	limparDadosSiop('acoes', " exercicio = '{$exercicio}' ");
	$programas = obterProgramasSiopPorExercicio($exercicio);
	foreach($programas as $programa) {
		echo "Obtendo ações do programa '{$programa['CODIGOPROGRAMA']}'.\n";
		$acoesDoPrograma = obterAcoesPorPrograma($programa['CODIGOPROGRAMA'], $exercicio);
		if( !$acoesDoPrograma['sucesso'] ) {
			echo "\tNão foi possível obter os dados das ações do programa '{$programa['CODIGOPROGRAMA']}:'.\n\n";
			echo str_replace("<br>", "\n", $acoesDoPrograma['mensagensErro']);
			echo "\n\n";
		} else {
			foreach($acoesDoPrograma['registros'] as $acaoDoPrograma) {
				salvarDadosSiop('acoes', $acaoDoPrograma);					
			}
			echo "\tDados das ações do programa '{$programa['CODIGOPROGRAMA']}' salvado.\n";
			reset($acoesDoPrograma);
		}
	}
	reset($programas);
}

if($baixar["objetivos"]) {
	/**
	 * ======================================================================
	 * Obtendo a base de dados de objetivos para o ano de exercício repassado
	 *
	 */
	echo "Obtendo objetivos.\n";
	$objetivos = obterTodosObjetivosPorAnoExercicio($exercicio);
	if(!$objetivos['sucesso']) {
		echo "\tNão foi possível obter os dados dos objetivos:\n\n";
		echo str_replace("<br>", "\n", $objetivos['mensagensErro']);
		echo "\n\n";
	} else {
		echo "Objetivos obtidos. Limpando base de dados dos objetivos para o ano de {$exercicio}.\n";
		limparDadosSiop('objetivos', "exercicio = '{$exercicio}'");
		foreach($objetivos['registros'] as $objetivo) {
			salvarDadosSiop('objetivos', $objetivo);
		}
		echo "Dados dos objetivos salvados.\n";
		reset($objetivos);
	}
}

if($baixar['metas']) {
	/**
	 * ======================================================================
	 * Obtendo a base de dados de metas para o ano de exercício repassado
	 *
	*/
	echo "Obtendo metas.\n";
	$metas = obterTodasMetasPorAnoExercicio($exercicio);
	if(!$metas['sucesso']) {
		echo "\tNão foi possível obter os dados das metas:\n\n";
		echo str_replace("<br>", "\n", $metas['mensagensErro']);
		echo "\n\n";
	} else {
		echo "Metas obtidas. Limpando base de dados das metas para o ano de {$exercicio}.\n";
		limparDadosSiop('metas', "exercicio = '{$exercicio}'");
		foreach($metas['registros'] as $meta) {
			salvarDadosSiop('metas', $meta);
		}
		echo "Dados das metas salvados.\n";
		reset($metas);
	}
}
