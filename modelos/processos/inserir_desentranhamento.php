<?php

/*
 * Copyright 2008 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuíção e/ou modifição dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuíção na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 * */

/**
 * Verificar se o processo esta na area de trabalho
 */
if (!Processo::validarProcessoAreaDeTrabalho($_POST['processo'])) {
    print(json_encode(array('success' => 'false', 'message' => Util::fixErrorString('Este processo não está na sua área de trabalho!'))));
    exit();
}

/**
 * Mensagens
 */
$stringOut = array();
$stringOut['DEF_EXCEPTION_MSG'] = "Atenção! Ocorreu um erro inesperado em sua solicitação, tente novamente mais tarde ou contate o administrador.";


new Base();

if ($_POST['acao'] == "desen") {

    try {

        if ($_POST['iddesen'] == "null") {
            $idtermo = inserirDesen($_POST);
        } else {
            updateDesen($_POST);
            $idtermo = $_POST['iddesen'];
        }

        $out = array('success' => 'true', 'processo' => $_POST['processo'] . "&termo=" . $idtermo);
    } catch (Exception $e) {
        $out = array('success' => 'false', 'message' => $stringOut['DEF_EXCEPTION_MSG']);
    }

    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($out);
} else if ($_POST['acao'] == "verif") {
    echo json_encode(array('success' => verifDesen($_POST['processo'])));
} else if ($_POST['acao'] == "lista") {
    echo json_encode(array('success' => 'true', 'lista' => listaEdit($_POST['processo'])));
} else if ($_POST['acao'] == 'loadedit') {
    echo json_encode(array('success' => 'true', 'termo' => getTermo($_POST['iddesen'])));
}

/**
 * Funcoes
 * @todo Refatorar...
 */
function verifDesen($processo) {
    $id = Util::RecuperaIdProcesso($processo);

    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT 1 FROM TB_PROCESSOS_DESENTRANHAMENTO WHERE NUMERO_PROCESSO = ?");
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $resul = $stmt->fetch();
    if (empty($resul)) {
        return false;
    }
    return true;
}

/**
 * @todo Refatorar...
 */
function inserirDesen($arrayData) {
    $id = Util::RecuperaIdProcesso($arrayData['processo']);
    $usuario = Controlador::getInstance()->usuario;


    Controlador::getInstance()->getConnection()->connection->beginTransaction();

    $id_unidade_usuario = Controlador::getInstance()->usuario->ID_UNIDADE;

    $id_usuario = $usuario->ID;
    $nome_usuario = $usuario->NOME;
    $id_unidade = $usuario->ID_UNIDADE;
    $oDiretoria = DaoUnidade::getUnidade($id_unidade);
    $diretoria = $oDiretoria['nome'];
    $tx_diretoria = $oDiretoria['nome'] . ' - ' . $oDiretoria['sigla'];

    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS
                (NUMERO_PROCESSO, ID_USUARIO, USUARIO, ID_UNIDADE, DIRETORIA, ACAO, ORIGEM, DESTINO, DT_TRAMITE)
                 VALUES(?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
    $stmt->bindParam(1, $arrayData['processo'], PDO::PARAM_STR);
    $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(3, $nome_usuario, PDO::PARAM_STR);
    $stmt->bindParam(4, $id_unidade, PDO::PARAM_INT);
    $stmt->bindParam(5, $diretoria, PDO::PARAM_STR);
    $stmt->bindValue(6, str_replace("<?>", format($arrayData['peca']), "As pecas <?> foram desentranhadas do processo."));
    $stmt->bindParam(7, $tx_diretoria);
    $stmt->bindValue(8, "XXXXX");
    $stmt->execute();


    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_PROCESSOS_DESENTRANHAMENTO 
        (NUMERO_PROCESSO, NUMERO_PECA, JUSTIFICATIVA, ID_SOLICITANTE, DT_ACAO, ID_USUARIO,ID_UNIDADE_USUARIO)
        VALUES (?, ?, ?, ?, CLOCK_TIMESTAMP(), ?,?)");
    $stmt->bindParam(1, $id);
    $stmt->bindParam(2, $arrayData['peca']);
    $stmt->bindParam(3, $arrayData['justif']);
    $stmt->bindParam(4, $id_unidade);
    $stmt->bindParam(5, $id_usuario);
    $stmt->bindParam(6, $id_unidade_usuario);

    $stmt->execute();
    $id = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_PROCESSOS_DESENTRANHAMENTO_ID_DESEN_SEQ');
    Controlador::getInstance()->getConnection()->connection->commit();
    return $id;
}

/**
 * @todo Refatorar...
 */
function updateDesen($arrayData) {


    Controlador::getInstance()->getConnection()->connection->beginTransaction();

    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_PROCESSOS_DESENTRANHAMENTO
        SET NUMERO_PECA = ?, JUSTIFICATIVA = ?, ID_SOLICITANTE = ?,  DT_ACAO =  CLOCK_TIMESTAMP(), ID_USUARIO = ? WHERE ID_DESEN = ?");
    $stmt->bindParam(1, $arrayData['peca']);
    $stmt->bindParam(2, $arrayData['justif']);
    $stmt->bindParam(3, $arrayData['diretoria']);
    $stmt->bindParam(4, Controlador::getInstance()->usuario->ID_USUARIO);
    $stmt->bindParam(5, $arrayData['iddesen']);

    $stmt->execute();
    Controlador::getInstance()->getConnection()->connection->commit();
}

/**
 * @todo Refatorar...
 */
function listaEdit($processo) {
    $id = Util::RecuperaIdProcesso($processo);

    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT P.NUMERO_PROCESSO AS processo, D.ID_DESEN as id , D.DT_ACAO as data, U.USUARIO AS usuario FROM TB_PROCESSOS_DESENTRANHAMENTO  AS D
        INNER JOIN TB_USUARIOS AS U ON D.ID_USUARIO = U.ID
        INNER JOIN TB_PROCESSOS_CADASTRO AS P ON P.ID = D.NUMERO_PROCESSO
        WHERE D.NUMERO_PROCESSO = ? ORDER BY ID_DESEN DESC LIMIT 5");
    $stmt->bindParam(1, $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @todo Refatorar...
 */
function getTermo($id) {

    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT D.JUSTIFICATIVA AS justificativa, D.NUMERO_PECA AS pecas, U.ID as idsolicitante, U.NOME as solicitante FROM TB_PROCESSOS_DESENTRANHAMENTO AS D
        INNER JOIN TB_UNIDADES AS U ON U.ID = D.ID_SOLICITANTE
        WHERE D.ID_DESEN = ?");
    $stmt->bindParam(1, $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * @todo Refatorar...
 */
function format($value) {
    $pecas = str_replace("-", " a ", $value);
    $exp = str_replace(",", ", ", $pecas);
    return $exp;
}