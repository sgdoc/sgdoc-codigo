<?php
include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.8/classes/DaoPrazoDemanda.php');

$inputData = array();

$usuario = DaoPrazoDemanda::getUsuario();

$inputData['PROCEDENCIA'] = 'I';
$inputData['TIPO'] = 'MONITORAMENTO'; # MONITORAMENTO! @todo parametrizar...
$inputData['DT_CADASTRO'] = date('Y-m-d');

// Dados do usuário
$inputData['DIRETORIA'] = $usuario->DIRETORIA;
$inputData['ORIGEM'] = $usuario->DIRETORIA;
$inputData['ULTIMO_TRAMITE'] = sprintf('Área de Trabalho - %s', $usuario->DIRETORIA);
$inputData['ID_UNIDADE'] = $usuario->ID_UNIDADE;
$inputData['ID_USUARIO'] = $usuario->ID;
$inputData['ID_UNID_CAIXA_SAIDA'] = $usuario->ID_UNIDADE;
$inputData['USUARIO'] = $usuario->NOME;

// Dados recebidos 
$inputData['ASSUNTO'] = $_REQUEST['ASSUNTO'];
$inputData['ID_ASSUNTO'] = $_REQUEST['ID_ASSUNTO'];
$inputData['ASSUNTO_COMPLEMENTAR'] = $_REQUEST['ASSUNTO_COMPLEMENTAR'];
$inputData['DT_DOCUMENTO'] = Util::formatDate($_REQUEST['DT_DOCUMENTO']);
$inputData['INTERESSADO'] = $_REQUEST['INTERESSADO'];
$inputData['DT_PRAZO'] = Util::formatDate($_REQUEST['DT_PRAZO']);
$inputData['SOLICITACAO'] = $_REQUEST['SOLICITACAO'];
$inputData['DIGITAL_REFERENCIA'] = $_REQUEST['DIGITAL_REFERENCIA'];

$priority = DaoPrazoDemanda::getPriority( $_REQUEST['PRIORIDADE'] );
$inputData['NM_PRIORIDADE'] = $priority->PRIORIDADE;
$inputData['PRIORIDADES'] = array();
$inputData['PRIORIDADES'][] = $_REQUEST['PRIORIDADE'];
if (isset($_REQUEST['extras']['PRIORIDADES'])) {
	foreach( $_REQUEST['extras']['PRIORIDADES']['id_campo'] as $prioridade ) {
		$inputData['PRIORIDADES'][] = $prioridade;
	}
}	
$inputData['TRAMITES'] = array();
$inputData['TRAMITES'][$_REQUEST['ID_UNID_CAIXA_ENTRADA']] = DaoUnidade::getUnidade( $_REQUEST['ID_UNID_CAIXA_ENTRADA'], 'nome');
if (isset($_REQUEST['extras']['TRAMITES'])) {
	foreach( $_REQUEST['extras']['TRAMITES']['id_campo'] as $tramite ) {
		
		$inputData['TRAMITES'][$tramite] = DaoUnidade::getUnidade( $tramite , 'nome');
	}
}

$responses = array();
$digitaisOk = array();
$sucesso = true;
foreach( $inputData['TRAMITES'] as $tramite => $nome_tramite ) {
	$inputData['ID_UNID_CAIXA_ENTRADA'] = $tramite;
	$inputData['DESTINO'] = $nome_tramite;
	
	$response = DaoPrazoDemanda::adicionarDemanda( $inputData );
	$responses[] = $response;
	
	if( !$response['success'] ) {
		$sucesso = false;
	} else {
		$digitaisOk[] = $response['digital'];
	}
}
if( count( $digitaisOk ) > 1 ) {
	DaoPrazoDemanda::agrupaDemandas ( $digitaisOk );
	// $responses[] = DaoPrazoDemanda::agrupaDemandas ( $digitaisOk );
}
$resposta = '';
foreach( $responses as $response ) {
	$resposta .= $response['message'] . "\n";
}
print json_encode( array( "success" => $sucesso, "message" => $resposta ) );





