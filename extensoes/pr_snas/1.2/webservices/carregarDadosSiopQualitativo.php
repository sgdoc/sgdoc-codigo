<?php

include_once(dirname(__FILE__) . '/include.soap.php');

echo "Qualitativo\n";

/*
 * Define que dados serão buscados no webservice. 
 * Utilizar quando precisar pegar apenas um tipo de dado específico.
 */
$baixar = array(
		'orgaos' => true,
		'programas' => true,
		'acoes' => true,
		'objetivos' => true,
		'metas' => true
);

if($baixar['orgaos']) {
	/**
	 * ===================================================================
	 * Obtendo a base de dados de órgãos para o ano de exercício repassado
	 * 
	*/
	echo "Obtendo órgãos.\n";
	$orgaos = obterTodosOrgaosPorAnoExercicio($exercicio);
	if(!$orgaos['sucesso']) {
		echo "\tNão foi possível obter os dados dos órgãos.\n";
		die;
	}
	echo "Órgaos obtidos. Limpando base de dados dos programas para o ano de {$exercicio}.\n";
	limparDadosSiop('orgaos', "exercicio = '{$exercicio}'");
	foreach($orgaos['registros'] as $orgao) {
		salvarDadosSiop('orgaos', $orgao);			
	}
	echo "Dados dos orgãos salvados.\n";
	reset($orgaos);
}

if($baixar['programas']) {
	/**
	 * ======================================================================
	 * Obtendo a base de dados de programas para o ano de exercício repassado
	 * 
	*/
	echo "Obtendo programas.\n";
	$programas = obterTodosProgramasPorAnoExercicio($exercicio);
	if(!$programas['sucesso']) {
		echo "\tNão foi possível obter os dados dos programas.\n";
		echo str_replace("<br>", "\n", $programas['mensagensErro']);
		die;
	}
	echo "Programas obtidos. Limpando base de dados dos programas para o ano de {$exercicio}.\n";
	limparDadosSiop('programas', "exercicio = '{$exercicio}'");
	foreach($programas['registros'] as $programa) {
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
	foreach($programas['registros'] as $programa) {
		echo "Obtendo ações do programa '{$programa['codigoPrograma']}'.\n";
		$acoesDoPrograma = obterAcoesPorPrograma($programa, $exercicio);
		if( !$acoesDoPrograma['sucesso'] ) {
			echo "\tNão foi possível obter os dados das ações do programa '{$programa['codigoPrograma']}'.\n";
			echo str_replace("<br>", "\n", $acoesDoPrograma['mensagensErro']);
		} else {
			foreach($acoesDoPrograma['registros'] as $acaoDoPrograma) {
				salvarDadosSiop('acoes', $acaoDoPrograma);					
			}
			echo "\tDados das ações do programa '{$programa['codigoPrograma']}' salvado.\n";
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
	print_r($objetivos);
	if(!$objetivos['sucesso']) {
		echo "\tNão foi possível obter os dados dos objetivos:\n";
		echo str_replace("<br>", "\n", $objetivos['mensagensErro']);
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
		echo "\tNão foi possível obter os dados das metas.\n";
		echo str_replace("<br>", "\n", $metas['mensagensErro']);
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
