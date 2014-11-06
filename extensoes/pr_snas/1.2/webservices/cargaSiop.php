<?php

include_once(dirname(__FILE__) . '/include.soap.php');

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

/*
//TESTES
echo 'CF_APP_ENVIRONMENT = ' . CF_APP_ENVIRONMENT ."\n";

// EXEMPLO DE CONSULTA EM BANCO
$stmt = ConfigWs::factory()->getConnection()->prepare("SELECT * FROM TB_CONTROLE_PRAZOS LIMIT 1");
$stmt->execute();
$out = $stmt->fetch(PDO::FETCH_ASSOC);

var_dump($out);
*/
	
function obtemValorVariavel($opcoes, $opcoesVariavel) {
	$nome = $opcoesVariavel['nome'];
	$letra = $opcoesVariavel['letra'];
	$obrigatorio = $opcoesVariavel['obrigatorio'];
	$valor = '';
	if (array_key_exists($nome, $opcoes)) {
		$valor = strtolower($opcoes[$nome]);
	} elseif (array_key_exists($letra, $opcoes)) {
		$valor = strtolower($opcoes[$letra]);
	}
	$mensagem = '';
	switch($opcoesVariavel['tipo']) {
		case 'lista': 
			$argumentos = implode(' | ', $opcoesVariavel['valores']);
			if ($valor == '') { 
				if ($obrigatorio) {
					$mensagem = "Argumento obrigatório: -{$letra} ou --{$nome} [ {$argumentos} ]";
				}
			} else if (in_array($valor, $opcoesVariavel['valores']) === false) {
				$mensagem = "Argumento inválido para o parâmetro '{$nome}', informe [ {$argumentos} ]";
				$valor = '';
			}				
			break;
		case 'ano':
			$valor = (int) $valor;
			if ($valor == '') {
				if($obrigatorio) {
					$mensagem = "Argumento obrigatório: -{$letra} ou --{$nome} [ ano no formato 9999 ]";
				}
			} else if ( $valor < 1900 || $valor > 9999) {
				$mensagem = "Argumento inválido para o parâmetro '{$nome}', informe [ ano no formato 9999 ]";
				$valor = '';
			}
	}
	if($mensagem != '') 
	{
		print "{$mensagem}\n";
	}
	return $valor;
}
