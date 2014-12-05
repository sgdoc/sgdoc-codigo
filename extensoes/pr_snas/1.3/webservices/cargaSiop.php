<?php
include_once(dirname(__FILE__) . '/include.soap.php');

try{
	
	$variaveis = array( 
		array(
			'letra' => 'a',
			'nome' => 'ambiente',
			'obrigatorio' => true,
			'tipo' => 'lista', 
			'valores' => array( 'prd', 'prd-presidencia', 'hmg', 'dsv', 'trn' )
		),
		array(
			'letra' => 'c',
			'nome' => 'carga',
			'obrigatorio' => true,
			'tipo' => 'lista',
			'valores' => array( 'ambas', 'qualitativo', 'quantitativo' )
		),
		array(
			'letra' => 'e',
			'nome' => 'exercicio',
			'obrigatorio' => true,
			'tipo' => 'ano'
		),
		array(
			'letra' => 'q',
			'nome' => 'qualitativo',
			'obrigatorio' => false,
			'tipo' => 'lista',
			'valores' => array( 'orgaos', 'programas', 'acoes', 'objetivos', 'metas' )
		)
	);
	
	$longOpt = array();
	$opts = '';
	foreach($variaveis as $variavel) {
		$longOpt[] = "{$variavel['nome']}:";
		$opts .= "{$variavel['letra']}:";
	}
	$parametros = getopt($opts,$longOpt);
	reset($variaveis);
	$obrigatorios = array();
	foreach($variaveis as $variavel) {
		${$variavel['nome']} = obtemValorVariavel($parametros, $variavel);
		if($variavel['obrigatorio']) {
			$obrigatorios[] = (${$variavel['nome']} == '');
		}
	}
	
	if (in_array('1', $obrigatorios)) {
		die;
	} 
	
	define('CF_APP_BASE_PATH', realpath(__DIR__) . '/../../../..');
	define('CF_APP_ENVIRONMENT', $ambiente);
	
	include_once( dirname(__FILE__) . '/ConfigWs.php' );
	
	ConfigWs::factory()
		->buildDBConfig()->buildAppConstants()
		->buildAppDefines()->buildEnvironment();
	
	if($carga == 'ambas' || $carga == 'qualitativo') {
		include_once(dirname(__FILE__) . '/carregarDadosSiopQualitativo.php');
	}
		
	
	if($carga == 'ambas' || $carga == 'quantitativo') {
		include_once(dirname(__FILE__) . '/carregarDadosSiopQuantitativo.php');
	}
	agora();
	agora("Script de carga encerrado.");
} catch (Exception $e) {
	agora();
	echo "Erro no sistema:\n";
	var_dump($e->getMessage());
}