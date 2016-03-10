<?php

include_once(dirname(__FILE__) . '/include.soap.php');
agora("==================================================================");
agora("Quantitativo");
agora("==================================================================");

$testandoConexão = testarConexaoQualitativo($exercicio);
if(!$testandoConexão['sucesso']) {
	agora("Houve um problema:");
	echo "\n";
	echo str_replace("<br>", "\n", $testandoConexão['mensagensErro']);
	echo "\n";
	echo str_replace("<br>", "\n", $testandoConexão['debug']);
	echo "\n";
	die();
}

/**
 * ===================================================================================
 * Obtendo a base de dados de programas para o ano de exercício repassado
 *
 */
agora("Carregando programas obtidos na base SIOP.");
$relacaoProgramas = obterDadosSiop('programas', "exercicio = {$exercicio}", array('"codigoPrograma"','"codigoOrgao"'));

/*
 * Filtrando resultados para testes no ambiente de desenvolvimento
 */
if (CF_APP_ENVIRONMENT == 'dsv') {
	// Utilizado para quando é necessário fazer a atualização de apenas alguns programas específicos para teste, 
	// não necessitando fazer uma carga completa
	// Caso o array esteja vazio é utilizada a carga completa
	$programasFiltrados = array(
// 			'2012',
// 			'2029'
	);
	$relacaoProgramas = (count($programasFiltrados) == 0)
		? $relacaoProgramas
		: filtrarProgramasPorCodigo($relacaoProgramas, $programasFiltrados);
}

$dados = array(
	'exercicio' => $exercicio,
	'programas' => $relacaoProgramas
);

obterExecucoesOrcamentarias(
	'obterExecucaoOrcamentariaAgrupadoPorAcao', 
	'ação', 
	'exec_orcam_acao', 
	$dados);
obterExecucoesOrcamentarias(
	'obterExecucaoOrcamentariaAgrupadoPorLocalizador', 
	'localizador', 
	'exec_orcam_localizador', 
	$dados
);
obterExecucoesOrcamentarias(
	'obterExecucaoOrcamentariaAgrupadoPorPlanoOrcamentario', 
	'plano orçamentário', 
	'exec_orcam_plano_orcam', 
	$dados
);

/**
 * ======================================================================================================
 * Obtendo a base de dados de execuções orçamentária 
 * 
 * Variáveis:
 * 	$metodo	=> método utilizado para obter os dados que consta do arquivo include.soap.php
 * 	$agrupamento => label para exibição
 * 	$destino => tabela no banco de dados onde são armazenados os dados obtidos
 * 	$dados => array com valores de entrada, composto por:
 * 		'exercicio' => ano de exercício 
 * 		'programas' => array com todos os dados dos programas que serão consultados
 *
 */
function obterExecucoesOrcamentarias($metodo, $agrupamento, $destino, $dados) {
	$obtendo = "Obtendo execuções orçamentárias agrupadas por {$agrupamento} para o exercício de {$dados['exercicio']}.";
	agora( str_repeat("-", strlen($obtendo)) ) ; agora( $obtendo ) ; agora( str_repeat("-", strlen($obtendo)) ) ;
	
	limparDadosExecucoesOrcamentarias(
		$destino, 
		$dados['exercicio'], 
		$dados['programas'], 
		"Limpando base de dados das execuções orçamentárias agrupadas por {$agrupamento} para o ano de {$dados['exercicio']}."
	);
	
	$codigoPrograma = 0;
	foreach($dados['programas'] as $programa) {
		if($codigoPrograma !== $programa['CODIGOPROGRAMA']) {
			$codigoPrograma = $programa['CODIGOPROGRAMA'];
			do{
				$numRegistrosSalvos = 0;
				$execucoesOrcamentarias = $metodo($codigoPrograma, $dados['exercicio']);
				if(!$execucoesOrcamentarias['sucesso']) {	
					agora("Houve um problema:");
					echo "\n";
					echo str_replace("<br>", "\n", $execucoesOrcamentarias['mensagensErro']);
					echo "\n";
				} else {		
					// Caso não tenha sido encontrado nenhum registro o webservice retorna um array com um único elemento de índice 0 e vazio.
					// Caso tenha sido encontrado apenas um registro o webservice retorna um array com os valores desse registro dentro da chave execucaoOrcamentaria
					// Se há mais de um registro o webservice retorna execucaoOrcamentaria como sendo um array de arrays
					if(isset($execucoesOrcamentarias['registros'][0]['execucaoOrcamentaria'])) {
						foreach($execucoesOrcamentarias['registros'] as $k => $registrosExecucoesOrcamentarias) {
							if(isset($registrosExecucoesOrcamentarias['execucaoOrcamentaria']['acao'])) {
								$execucaoOrcamentaria = $registrosExecucoesOrcamentarias['execucaoOrcamentaria'];
								$dadosExecucaoOrcamentaria = montandoDadosDaExecucaoOrcamentaria($execucaoOrcamentaria, $dados['exercicio']);
								salvarDadosSiop($destino, $dadosExecucaoOrcamentaria);
								$numRegistrosSalvos++;
							} else {
								foreach($registrosExecucoesOrcamentarias as $j => $registros) {
									foreach($registros as $execucaoOrcamentaria) {
										$dadosExecucaoOrcamentaria = montandoDadosDaExecucaoOrcamentaria($execucaoOrcamentaria, $dados['exercicio']);
										salvarDadosSiop($destino, $dadosExecucaoOrcamentaria);
										$numRegistrosSalvos++;
									}
								}
							}
						}
					}
				} 
			} while(!$execucoesOrcamentarias['sucesso']);
			if($numRegistrosSalvos > 0) {
				agora("\tSalvados {$numRegistrosSalvos} registro(s) da execução orçamentária agrupadas por {$agrupamento} do programa.");
			} else {
				agora("\tExecução orçamentária do programa agrupadas por {$agrupamento} sem registros.");
			}
		}
		reset($programas);
	}	
}

function montandoDadosDaExecucaoOrcamentaria($execucaoOrcamentaria, $exercicio) {
	if($execucaoOrcamentaria['dotAtual'] == '') {
		$execucaoOrcamentaria['dotAtual'] = 0;
	}
	if($execucaoOrcamentaria['empLiquidado'] == '') {
		$execucaoOrcamentaria['empLiquidado'] = 0;
	}
	if($execucaoOrcamentaria['empenhadoALiquidar'] == '') {
		$execucaoOrcamentaria['empenhadoALiquidar'] = 0;
	}
	$liquidado = $execucaoOrcamentaria['empLiquidado'];
	$empenhado = $execucaoOrcamentaria['empenhadoALiquidar'] + $liquidado;
	if($empenhado != 0) {
		$percentual = ($liquidado / $empenhado) * 100;
	} else {
		$percentual = 0;
	}
	$dadosExecucaoOrcamentaria = array(
			"codigoAcao" => $execucaoOrcamentaria['acao'],
			"codigoPrograma" => $execucaoOrcamentaria['programa'],
			"codigoOrgao" => $execucaoOrcamentaria['unidadeOrcamentaria'],
			"exercicio" => $exercicio,
			"dotacaoAtual" => $execucaoOrcamentaria['dotAtual'],
			"empenhado" => $empenhado,
			"liquidado" => $liquidado,
			"percentualLiquidadoEmpenhado" => $percentual
	);
	if(isset($execucaoOrcamentaria['localizador'])) {
		$dadosExecucaoOrcamentaria["codigoLocalizador"] = $execucaoOrcamentaria['localizador'];		
	}	
	if(isset($execucaoOrcamentaria['planoOrcamentario'])) {
		$dadosExecucaoOrcamentaria["planoOrcamentario"] = $execucaoOrcamentaria['planoOrcamentario'];		
	}
	return $dadosExecucaoOrcamentaria;
}

function filtrarProgramasPorCodigo($relacaoProgramas,$programasFiltrados) {
	$programas = array();
	foreach($relacaoProgramas as $programa) {
		if( in_array($programa['CODIGOPROGRAMA'], $programasFiltrados ) ) {
			$programas[] = $programa;
		}
	}
	return $programas;
}

function limparDadosExecucoesOrcamentarias($destino, $exercicio, $programas, $mensagem = "") {
	$condicoes = array();
	// Remove os programas do exercicio repassado
	$condicoes[] = "exercicio = '{$exercicio}'";
	
	// Remove os programas que serão atualizados
	foreach($programas as $programa) {
		$codigosProgramas[] = $programa['CODIGOPROGRAMA'];
	}
	$condicoesProgramas = array();
	foreach(array_unique($codigosProgramas) as $codigoPrograma) {
		$condicoesProgramas[] = "'{$codigoPrograma}'";	
	} 
	$condicoes[] = '( "codigoPrograma" IN (' . implode(' , ', $condicoesProgramas ) . ') )';
	return limparDadosSiop($destino, $condicoes, $mensagem);	
} 