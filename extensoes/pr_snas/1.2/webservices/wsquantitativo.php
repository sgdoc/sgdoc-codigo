<?php

include_once(dirname(__FILE__) . '/include.soap.php');

$credencial = array(
	'perfil'	=> $configuracao['Quantitativo']['user']['perfil'],
	'senha'		=> $configuracao['Quantitativo']['user']['senha'],
	'usuario'	=> $configuracao['Quantitativo']['user']['usuario']
);

$parametros = false;
$sucesso = false;
$erro = '';

$dados = array();

$programaParametros = array(				
	'credencial'		=> $credencial,
	'filtro'			=> array(
			'anoExercicio'		=> '2012',
			'acoes'				=> array(
					//'acao' 		=> '20TP',
					//'acao'		=> '2011',
			),
			'programas'	=> array(
 					'programa'	=> '0570',
					)
			),
	'selecaoRetorno' => array(
			'acao'				=> true,
			'dotAtual'			=> true,
			'dotacaoInicial' 	=> true,
			'empLiquidado'		=> true,
			'empenhadoALiquidar'=> true,
			'pago'				=> true,
			'programa'			=> true,
			),
	'paginacao'		=> array(
			'pagina'			=> 1,
			'registrosPorPagina'=> 1
			)
);
$programasDTO = acessarWebServiceSOF( 'consultarExecucaoOrcamentaria', $programaParametros, $configuracao['Quantitativo'], 'execucoesOrcamentarias' );
echo "<pre>"; print_r($programasDTO); die();

