<?php

include_once(dirname(__FILE__) . '/include.soap.php');
agora("==================================================================");
agora("Quantitativo");
agora("==================================================================");

/**
 * ===================================================================================
 * Obtendo a base de dados de programas para o ano de exercício repassado
 *
 */
agora("Carregando programas obtidos na base SIOP.");
$programas = obterDadosSiop('programas', "exercicio = {$exercicio}", array('"codigoPrograma"','"codigoOrgao"'));
limparDadosSiop('execucao_orcamentaria', "exercicio = '{$exercicio}'","Limpando base de dados das execuções orçamentárias para o ano de {$exercicio}.");

/**
 * ===================================================================================
 * Obtendo a base de dados de execuções orçamentária para o ano de exercício repassado
 *
 */
$codigoPrograma = 0;
foreach($programas as $programa) {
	if($codigoPrograma !== $programa['CODIGOPROGRAMA']) {
		$codigoPrograma = $programa['CODIGOPROGRAMA'];
		do{
			$numRegistrosSalvos = 0;
			$execucoesOrcamentarias = obterExecucaoOrcamentaria($codigoPrograma, $exercicio);
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
							$dadosExecucaoOrcamentaria = montandoDadosDaExecucaoOrcamentaria($execucaoOrcamentaria, $exercicio);
							salvarDadosSiop('execucao_orcamentaria', $dadosExecucaoOrcamentaria);
							$numRegistrosSalvos++;
						} else {
							foreach($registrosExecucoesOrcamentarias as $j => $registros) {
								foreach($registros as $execucaoOrcamentaria) {
									$dadosExecucaoOrcamentaria = montandoDadosDaExecucaoOrcamentaria($execucaoOrcamentaria, $exercicio);
									salvarDadosSiop('execucao_orcamentaria', $dadosExecucaoOrcamentaria);
									$numRegistrosSalvos++;
								}
							}
						}
					}
				}
			} 
		} while(!$execucoesOrcamentarias['sucesso']);
		if($numRegistrosSalvos > 0) {
			agora("\tSalvados {$numRegistrosSalvos} registro(s) da execução orçamentária do programa.");
		} else {
			agora("\tExecução orçamentária do programa sem registros.");
		}
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
			"exercicio" => $exercicio,
			"dotacaoAtual" => $execucaoOrcamentaria['dotAtual'],
			"empenhado" => $empenhado,
			"liquidado" => $liquidado,
			"percentualLiquidadoEmpenhado" => $percentual,
			"codigoOrgao" => $execucaoOrcamentaria['unidadeOrcamentaria']
	);
	return $dadosExecucaoOrcamentaria;
}

