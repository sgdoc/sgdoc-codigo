<?php

include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.2/classes/CFModelDocumentoDemanda.php');
include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.2/classes/TPDocumentoDemanda.php');
include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.2/classes/CFModelDocumentoCamposDemanda.php');


$response = array();

try {

    $persist = CFModelDocumentoDemanda::factory();


    $persist->beginTransaction();

    $factory = TPDocumentoDemanda::factory();

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
    $idDocumentoPai = current($persist->findByParam(array('DIGITAL' => $_REQUEST['DIGITAL_REFERENCIA'])));
    $_REQUEST['DIGITAL_PAI'] = $idDocumentoPai->DIGITAL;

    if (empty($documento)) {
        throw new Exception('Ocorreu um erro ao tentar registrar o novo monitoramento!');
    }

    $_REQUEST['ID'] = $lastId;

    $factory->generatePDF($_REQUEST)
            ->convertPDFToPng($digital)
            ->garbageCollection()
            ->registerPNGDB($digital, Controlador::getInstance()->getConnection()->connection)
            ->registerDeadlines($_REQUEST);

    //Cria o histórico do documento para encaminhamento
    $_REQUEST['ULTIMO_TRAMITE'] = sprintf('Encaminhado por %s - %s para %s em %s', $usuario->NOME, $usuario->DIRETORIA, $_REQUEST['DESTINO'], date('d/m/Y - H:i:s'));
    $factory->transact($_REQUEST);


    /**
     * @author Bruno Pedreira
     * Data: 11/12/2013
     * Funcionalidade adicionada para associar documentos.
     * Necessidade PG/PR SNAS
     */
    if (!empty($_REQUEST['DIGITAL_REFERENCIA'])) {
        $persist->associarDocumentos($idDocumentoPai->ID, $lastId, $usuario->ID, $usuario->ID_UNIDADE, $usuario->NOME, $usuario->DIRETORIA, 'XXXXX', 'XXXXX');
    }

    //tratar PRIORIDADES
    if (isset($_REQUEST['extras']['PRIORIDADES'])) {

        $prioridades = $_REQUEST['extras']['PRIORIDADES'];

        if (is_array($prioridades)) {

            //desabilitar todos os vinculos do documento com as campos extras
            CFModelDocumentoCamposDemanda::factory()->disassociateAllByDigital($_REQUEST['DIGITAL'], "PR");

            for ($i = 0; $i < count($prioridades["id"]); $i++) {
                if (CFModelDocumentoCamposDemanda::factory()->isExists($_REQUEST['DIGITAL'], $prioridades["id_campo"][$i], "PR")) { //Se existir atualiza
                    CFModelDocumentoCamposDemanda::factory()->updateAssociationWithDigital($_REQUEST['DIGITAL'], $prioridades["id_campo"][$i], 1, "PR");
                } else { //Se não cria
                    CFModelDocumentoCamposDemanda::factory()->createAssociationWithDigital($_REQUEST['DIGITAL'], $prioridades["id_campo"][$i], "PR");
                }
            }
        }
    }

    //Fim da funcionalidade

    $persist->commit();

    $response = array('success' => true, 'message' => sprintf('Monitoramento %s cadastrado com sucesso!', current($documento)->DIGITAL));
} catch (Exception $e) {
    $factory->garbageCollection();
    $persist->rollback();
    $response = array('success' => false, 'message' => $e->getMessage());
}

print json_encode($response);
