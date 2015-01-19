<?php

$response = array();

try {

    $persist = CFModelDocumento::factory();
    $persist->beginTransaction();

    $factory = TPDocumento::factory();

    $priority = current(CFModelPrioridade::factory()->find($_REQUEST['PRIORIDADE']));

    $usuario = Zend_Auth::getInstance()->getStorage()->read();

    $digital = CFModelDigital::factory()->next($usuario->ID_UNIDADE);

    if (!$digital) {
        throw new Exception('Não existe digitais disponíveis!');
    }

    $_REQUEST['SOLICITACAO'] = $_REQUEST['SOLICITACAO'];
    $_REQUEST['PROCEDENCIA'] = 'I';
    $_REQUEST['TIPO'] = 'MONITORAMENTO'; # MONITORAMENTO! @todo parametrizar...
    $_REQUEST['NUMERO'] = $digital;
    $_REQUEST['DT_DOCUMENTO'] = Util::formatDate($_REQUEST['DT_DOCUMENTO']);
    $_REQUEST['DT_PRAZO'] = Util::formatDate($_REQUEST['DT_PRAZO']);
    $_REQUEST['DIRETORIA'] = $usuario->DIRETORIA;
    $_REQUEST['ORIGEM'] = $usuario->DIRETORIA;
    $_REQUEST['DT_CADASTRO'] = date('Y-m-d');
    $_REQUEST['DIGITAL'] = $digital;
    $_REQUEST['ULTIMO_TRAMITE'] = sprintf('Área de Trabalho - %s', $usuario->DIRETORIA);
    $_REQUEST['ID_UNIDADE'] = $usuario->ID_UNIDADE;
    $_REQUEST['ID_USUARIO'] = $usuario->ID;
    $_REQUEST['ID_UNID_CAIXA_SAIDA'] = $usuario->ID_UNIDADE;
    $_REQUEST['USUARIO'] = $usuario->NOME;
    $_REQUEST['NM_PRIORIDADE'] = $priority->PRIORIDADE;

    $lastId = $factory->create($_REQUEST);

    $documento = $persist->find($lastId);

    if (empty($documento)) {
        throw new Exception('Ocorreu um erro ao tentar registrar o novo monitoramento!');
    }

    $_REQUEST['ID'] = $lastId;
    $arrAllRequest = $_REQUEST;

    $factory->generatePDF($arrAllRequest)
            ->convertPDFToPng($digital)
            ->garbageCollection()
            ->registerPNGDB($digital, Controlador::getInstance()->getConnection()->connection)
            ->registerDeadlines($arrAllRequest)
    ;

    $_REQUEST['ULTIMO_TRAMITE'] = sprintf('Encaminhado por %s - %s para %s em %s', $usuario->NOME, $usuario->DIRETORIA, $_REQUEST['DESTINO'], date('d/m/Y - H:i:s'));

    $factory->transact($arrAllRequest);

    $persist->commit();

    $response = array('success' => true, 'message' => sprintf('Monitoramento %s cadastrado com sucesso!', current($documento)->DIGITAL));
} catch (Exception $e) {
    $factory->garbageCollection();
    $persist->rollback();
    $response = array('success' => false, 'message' => $e->getMessage());
}

print json_encode($response);