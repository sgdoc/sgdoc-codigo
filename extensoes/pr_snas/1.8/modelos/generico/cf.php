<?php
include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.8/classes/CFModelDocumentoDemanda.php');
include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.8/classes/TPDocumentoDemanda.php');
include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.8/classes/CFModelDocumentoCamposDemanda.php');
include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.8/classes/CFModelAgrupamentoDocumentos.php');

$inputData = array();

$usuario = getUsuario();

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

$priority = getPriority( $_REQUEST['PRIORIDADE'] );
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
	
	$response = adicionaDemanda( $inputData, $usuario );
	$responses[] = $response;
	
	if( !$response['success'] ) {
		$sucesso = false;
	} else {
		$digitaisOk[] = $response['digital'];
	}
}
if( count( $digitaisOk ) > 1 ) {
	$responses[] = agrupaDemandas ( $digitaisOk );
}
$resposta = '';
foreach( $responses as $response ) {
	$resposta .= $response['message'] . "\n";
}
print json_encode( array( "success" => $sucesso, "message" => $resposta ) );

function agrupaDemandas( $digitais ) {
	try {
		$persist = CFModelAgrupamentoDocumentos::factory();
		$persist->beginTransaction();
		
		$novoGrupo = CFModelAgrupamentoDocumentos::obtemNovoGrupo();
		
		foreach( $digitais as $digital ) {
			$persist->insert( array(
				'ID_GRUPO' => $novoGrupo,
				'DIGITAL' => $digital
			));
		}
	
		$persist->commit();
		return array('success' => true, 'message' => sprintf('Documentos agrupado no grupo %s com sucesso!', $novoGrupo) );
	}catch (Exception $e) {
		$factory->garbageCollection();
		$persist->rollback();
		$response = array('success' => false, 'message' => $e->getMessage() );
	}
}



function adicionaDemanda( $inputData, $usuario ) 
{ 
	$response = array();
	try {
		$persist = CFModelDocumentoDemanda::factory();
		$persist->beginTransaction();
		
		$factory = TPDocumentoDemanda::factory();
	
		$digital = CFModelDigital::factory()->next($usuario->ID_UNIDADE);
		if (!$digital) {
			throw new Exception('Não existe digitais disponíveis!');
		}
		$inputData['NUMERO'] = $digital;
		$inputData['DIGITAL'] = $digital;

		$lastId = $factory->create($inputData);
		$documento = $persist->find($lastId);
		$idDocumentoPai = current($persist->findByParam(array('DIGITAL' => $inputData['DIGITAL_REFERENCIA'])));
		$inputData['DIGITAL_PAI'] = $idDocumentoPai->DIGITAL;
	
		if (empty($documento)) {
			throw new Exception('Ocorreu um erro ao tentar registrar o novo monitoramento!');
		}
	
		$inputData['ID'] = $lastId;
		
		$factory->generatePDF($inputData)
				->convertPDFToPng($inputData['DIGITAL'])
				->garbageCollection()
				->registerPNGDB($inputData['DIGITAL'], Controlador::getInstance()->getConnection()->connection)
				->registerDeadlines($inputData);
	
		//Cria o histórico do documento para encaminhamento
		$inputData['ULTIMO_TRAMITE'] = sprintf('Encaminhado por %s - %s para %s em %s', $usuario->NOME, $usuario->DIRETORIA, $inputData['DESTINO'], date('d/m/Y - H:i:s'));
		$factory->transact($inputData);
	
	
		/**
		 * @author Bruno Pedreira
		 * Data: 11/12/2013
		 * Funcionalidade adicionada para associar documentos.
		 * Necessidade PG/PR SNAS
		 */
		if (!empty($inputData['DIGITAL_REFERENCIA'])) {
			$persist->associarDocumentos($idDocumentoPai->ID, $lastId, $usuario->ID, $usuario->ID_UNIDADE, $usuario->NOME, $usuario->DIRETORIA, 'XXXXX', 'XXXXX');
		}
	
		//tratar PRIORIDADES
		if (isset($inputData['PRIORIDADES'])) {
	
			$prioridades = $inputData['PRIORIDADES'];
	
			if ( count($prioridades) > 1 ) {
	
				//desabilitar todos os vinculos do documento com as campos extras
				CFModelDocumentoCamposDemanda::factory()->disassociateAllByDigital($inputData['DIGITAL'], "PR");
	
				foreach ($prioridades as $prioridade) {
					if (CFModelDocumentoCamposDemanda::factory()->isExists($inputData['DIGITAL'], $prioridade, "PR")) { //Se existir atualiza
						CFModelDocumentoCamposDemanda::factory()->updateAssociationWithDigital($inputData['DIGITAL'], $prioridade, 1, "PR");
					} else { //Se não cria
						CFModelDocumentoCamposDemanda::factory()->createAssociationWithDigital($inputData['DIGITAL'], $prioridade, "PR");
					}
				}
			}
		}
	
		//Fim da funcionalidade
	
		$persist->commit();
	
		$response = array('success' => true, 'message' => sprintf('Monitoramento %s cadastrado com sucesso!', current($documento)->DIGITAL), 'digital' => current($documento)->DIGITAL );
	} catch (Exception $e) {
		$factory->garbageCollection();
		$persist->rollback();
		$response = array('success' => false, 'message' => $e->getMessage(), 'digital' => current($documento)->DIGITAL );
	}
	
	return $response;
}

function getPriority( $prioridade )
{
	return current( CFModelPrioridade::factory()->find( $prioridade ) );
}

function getUsuario()
{
	return Zend_Auth::getInstance()->getStorage()->read();
}
