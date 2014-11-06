<?php

include_once(dirname(__FILE__) . '/include.soap.php');

echo "Quantitativo\n";

/**
 * ===================================================================================
 * Obtendo a base de dados de programas para o ano de exercício repassado
 *
 */
$programas = obterDadosSiop('programas', "exercicio = {$exercicio}");
echo "Limpando base de dados das execuções orçamentárias para o ano de {$exercicio}.\n";
limparDadosSiop('execucao_orcamentaria', "exercicio = '{$exercicio}'");

/**
 * ===================================================================================
 * Obtendo a base de dados de execuções orçamentária para o ano de exercício repassado
 *
 */
foreach($programas as $programa) {	
	echo "Obtendo execuções orçamentárias do programa '{$programa['CODIGOPROGRAMA']}'.\n";
	$execucoesOrcamentarias = obterExecucaoOrcamentaria($programa['CODIGOPROGRAMA'], $exercicio);
	foreach($execucoesOrcamentarias['registros'] as $nivel) {
		foreach($nivel as $registros) {
			foreach($registros as $execucaoOrcamentaria) {
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
						"percentualLiquidadoEmpenhado" => $percentual
				);									
				salvarDadosSiop('execucao_orcamentaria', $dadosExecucaoOrcamentaria);
			}
		}
	}
	echo "\tDados da execução orçamentária do programa salvados.\n";
	
}	

