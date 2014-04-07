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
 * 
 */
function validarProcesso($processo) {


    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID_PROCESSO_FILHO FROM TB_PROCESSOS_VINCULACAO WHERE ID_PROCESSO_FILHO = ? AND ST_ATIVO = 1 AND FG_ATIVO = 1");
    $stmt->bindParam(1, $processo);
    $stmt->execute();
    $resul = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out = array();

    if (!empty($resul)) {
        foreach ($resul as $value) {
            $out[] = $value['ID_PROCESSO_FILHO'];
        }
    }
    return $out;
}

/**
 * 
 */
function valCadProc($args) {
    is_array($args) ? $array = implode("', '", $args) : $array = $args;


    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT NUMERO_PROCESSO FROM TB_PROCESSOS_CADASTRO WHERE NUMERO_PROCESSO IN ('" . $array . "')");
    $stmt->execute();
    $resul = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out = array();

    if (!empty($resul)) {
        foreach ($resul as $value) {
            $out[] = $value['NUMERO_PROCESSO'];
        }
    }
    return $out;
}

/**
 * @param <type> $arrayData
 */
function vincularProcessos($arrayData) {
    $usuario = Controlador::getInstance()->usuario;
    /* recupera id do processo */
    $numero_processo = getIdProcesso($arrayData['numero_processo']);

    /* 1 - anexar 2 - apensar */
    /* 1 - anexar */

    /* mensagem padrão do historico de tramite */
    $mensagem_tramite_pai = "O processo <?> foi anexado neste processo.";
    $mensagem_tramite_filho = "O processo foi anexado ao processo <?>.";

    /* recupera indice de controle de impressão do termo */
    $bloco_impressao = getLastBloc($numero_processo, 1);
    $tipo_vinculacao = 1; /* tabela TB_VINCULACAO */
    if ($arrayData['acao'] == "apensar") {
        /* 2 - apensar */

        /* mensagem padrão do historico de tramite */
        $mensagem_tramite_pai = "O processo <?> foi apensado neste processo.";
        $mensagem_tramite_filho = "O processo foi apensado ao processo <?>.";

        $bloco_impressao = getLastBloc($numero_processo, 2);
        $tipo_vinculacao = 2; /* tabela TB_VINCULACAO */
    }

    /* array com processos a ser apensados ou anexados
     * utilizado array_unique para retirar possiveis processos repetidos
     */
    $anexos = array_unique(explode(',', $arrayData['processos']));

    $data_acao = date('Y-m-d h:m:s');

    $id_usuario = $usuario->ID;
    $nome_usuario = $usuario->NOME;
    $id_unidade = $usuario->ID_UNIDADE;
    $oDiretoria = DaoUnidade::getUnidade($id_unidade);
    $diretoria = $oDiretoria['nome'];
    $tx_diretoria = $oDiretoria['nome'] . ' - ' . $oDiretoria['sigla'];



    Controlador::getInstance()->getConnection()->connection->beginTransaction();
    try {
        $iterator = new ArrayIterator($anexos);

        while ($iterator->valid()) {
            /* recupera id do processo corrente */
            $numero_apenso = getIdProcesso($iterator->current());

            $id_unidade_usuario = Controlador::getInstance()->usuario->ID_UNIDADE;

            /*             * ************************* TRAMITE PAI ************************* */

            /**
             * BugFix Notice
             */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS"
                    . " (NUMERO_PROCESSO, ID_USUARIO, USUARIO, ID_UNIDADE, DIRETORIA, ACAO, ORIGEM, DESTINO, DT_TRAMITE)"
                    . " VALUES(?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
            $stmt->bindParam(1, $arrayData['numero_processo'], PDO::PARAM_STR);
            $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(3, $nome_usuario, PDO::PARAM_STR);
            $stmt->bindParam(4, $id_unidade, PDO::PARAM_INT);
            $stmt->bindParam(5, $diretoria, PDO::PARAM_STR);
            $stmt->bindValue(6, str_replace("<?>", $iterator->current(), $mensagem_tramite_pai));
            $stmt->bindParam(7, $tx_diretoria);
            $stmt->bindValue(8, "XXXXX");
            $stmt->execute();
            $id_tramite_pai = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_HISTORICO_TRAMITE_PROCESSOS_ID_SEQ');
            /*             * **************************************************************** */

            /*             * ************************* TRAMITE FILHO ************************ */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS (NUMERO_PROCESSO, "
                    . " ID_USUARIO, USUARIO, ID_UNIDADE, DIRETORIA, ACAO, ORIGEM, DESTINO, DT_TRAMITE)"
                    . " VALUES(?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");

            $stmt->bindParam(1, $iterator->current(), PDO::PARAM_STR);
            $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(3, $nome_usuario, PDO::PARAM_STR);
            $stmt->bindParam(4, $id_unidade, PDO::PARAM_INT);
            $stmt->bindParam(5, $diretoria, PDO::PARAM_STR);
            $stmt->bindValue(6, str_replace("<?>", $arrayData['numero_processo'], $mensagem_tramite_filho));
            $stmt->bindParam(7, $tx_diretoria);
            $stmt->bindValue(8, "XXXXX");
            $stmt->execute();
            $id_tramite_filho = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_HISTORICO_TRAMITE_PROCESSOS_ID_SEQ');

            /*             * ************************** ATUALIZAR MOVIMENTAÇÃO ************** */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_PROCESSOS_CADASTRO SET ULTIMO_TRAMITE = ? WHERE ID = ?");

            $data = date("d/m/Y " . " - " . "H:i:s");

            $ultimo_tramite = str_replace(".", " ", $mensagem_tramite_filho);
            $ultimo_tramite.= "por {$nome_usuario} em {$data}.";
            $stmt->bindParam(1, str_replace("<?>", $arrayData['numero_processo'], $ultimo_tramite), PDO::PARAM_STR);
            $stmt->bindParam(2, $numero_apenso, PDO::PARAM_INT);

            $stmt->execute();

            /*             * **************************************************************** */

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_PROCESSOS_VINCULACAO
                                    (ID_PROCESSO_PAI, ID_PROCESSO_FILHO, DT_INCLUSAO_FORM, DT_EXCLUSAO_FORM, ID_USUARIO, ID_SOLICITANTE, "
                    . "BLOCO_IMPRESSAO, DT_ACAO, ID_HISTORICO_TRAMITE_PAI, ID_HISTORICO_TRAMITE_FILHO, ID_VINCULACAO,ID_UNIDADE_USUARIO)
                                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bindValue(1, $numero_processo);
            $stmt->bindParam(2, $numero_apenso);
            $stmt->bindParam(3, format($arrayData['data_cadastro']));
            $stmt->bindValue(4, null);
            $stmt->bindParam(5, $id_usuario);
            $stmt->bindValue(6, $arrayData['diretoria']);
            $stmt->bindParam(7, $bloco_impressao);
            $stmt->bindParam(8, $data_acao);
            $stmt->bindParam(9, $id_tramite_pai);
            $stmt->bindParam(10, $id_tramite_filho);
            $stmt->bindParam(11, $tipo_vinculacao);
            $stmt->bindParam(12, $id_unidade_usuario);
            $stmt->execute();

            $iterator->next();
        }
        Controlador::getInstance()->getConnection()->connection->commit();
    } catch (PDOException $e) {
        Controlador::getInstance()->getConnection()->connection->rollBack();
        throw new Exception($e);
    }
}

/**
 * 
 */
function desvincularProcesso($arrayData) {
    $usuario = Controlador::getInstance()->usuario;
    /* recupera id do processo */
    $numero_processo = getIdProcesso($arrayData['numero_processo']);

    /* mensagem padrão do historico de tramite do tipo desanexar */
    $mensagem_tramite_pai = "O processo <?> foi desapensado deste processo.";
    $mensagem_tramite_filho = "O processo foi desapensado do processo <?>.";

    /* sequencial para controle de impressão do termo. */
    $bloco_impressao = getLastBloc($numero_processo, 2/* 1 - anexar 2 - apensar  - TB_VINCULACAO */);

    $anexos = array_unique(explode(',', $arrayData['processos']));

    $data_acao = date('Y-m-d h:m:s');

    $id_usuario = $usuario->ID;
    $nome_usuario = $usuario->NOME;
    $id_unidade = $usuario->ID_UNIDADE;
    $oDiretoria = DaoUnidade::getUnidade($id_unidade);
    $diretoria = $oDiretoria['nome'];
    $tx_diretoria = $oDiretoria['nome'] . ' - ' . $oDiretoria['sigla'];



    Controlador::getInstance()->getConnection()->connection->beginTransaction();
    try {
        $iterator = new ArrayIterator($anexos);

        while ($iterator->valid()) {
            $numero_apenso = getIdProcesso($iterator->current());

            /*             * ************************* TRAMITE PAI ************************* */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS
                (NUMERO_PROCESSO, ID_USUARIO, USUARIO, ID_UNIDADE, DIRETORIA, ACAO, ORIGEM, DESTINO, DT_TRAMITE)
                 VALUES(?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
            $stmt->bindParam(1, $arrayData['numero_processo'], PDO::PARAM_STR);
            $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(3, $nome_usuario, PDO::PARAM_STR);
            $stmt->bindParam(4, $id_unidade, PDO::PARAM_INT);
            $stmt->bindParam(5, $diretoria, PDO::PARAM_STR);
            $stmt->bindValue(6, str_replace("<?>", $iterator->current(), $mensagem_tramite_pai));
            $stmt->bindParam(7, $tx_diretoria);
            $stmt->bindValue(8, "XXXXX");
            $stmt->execute();
            $id_tramite_pai = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_HISTORICO_TRAMITE_PROCESSOS_ID_SEQ');
            /*             * **************************************************************** */
            /*             * ************************* TRAMITE FILHO ************************ */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_HISTORICO_TRAMITE_PROCESSOS
                (NUMERO_PROCESSO, ID_USUARIO, USUARIO, ID_UNIDADE, DIRETORIA, ACAO, ORIGEM, DESTINO, DT_TRAMITE)
                 VALUES(?,?,?,?,?,?,?,?,CLOCK_TIMESTAMP())");
            $stmt->bindParam(1, $iterator->current(), PDO::PARAM_STR);
            $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(3, $nome_usuario, PDO::PARAM_STR);
            $stmt->bindParam(4, $id_unidade, PDO::PARAM_INT);
            $stmt->bindParam(5, $diretoria, PDO::PARAM_STR);
            $stmt->bindValue(6, str_replace("<?>", $arrayData['numero_processo'], $mensagem_tramite_filho));
            $stmt->bindParam(7, $tx_diretoria);
            $stmt->bindValue(8, "XXXXX");
            $stmt->execute();
            $id_tramite_filho = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_HISTORICO_TRAMITE_PROCESSOS_ID_SEQ');

            /*             * ************************** ATUALIZAR MOVIMENTAÇÃO ************** */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_PROCESSOS_CADASTRO SET ULTIMO_TRAMITE = ? WHERE ID = ?");

            $data = date("d/m/Y " . " - " . "H:i:s");

            $ultimo_tramite = str_replace(".", " ", $mensagem_tramite_filho);
            $ultimo_tramite.= "por {$nome_usuario} em {$data}.";
            $stmt->bindParam(1, str_replace("<?>", $arrayData['numero_processo'], $ultimo_tramite), PDO::PARAM_STR);
            $stmt->bindParam(2, $numero_apenso, PDO::PARAM_INT);

            $stmt->execute();

            /*             * **************************************************************** */

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_PROCESSOS_VINCULACAO
                                    SET DT_EXCLUSAO_FORM = ?, ID_SOLICITANTE = ?, FG_ATIVO = ?, BLOCO_IMPRESSAO = ?, ID_HISTORICO_TRAMITE_PAI = ?, ID_HISTORICO_TRAMITE_FILHO = ? , DT_ACAO = ?
                                    WHERE ID_PROCESSO_PAI = ? AND ID_PROCESSO_FILHO = ? AND ST_ATIVO = 1 AND ID_VINCULACAO = 2");

            $stmt->bindParam(1, format($arrayData['data_exclusao']), PDO::PARAM_STR);
            $stmt->bindParam(2, $arrayData['diretoria'], PDO::PARAM_STR);
            $stmt->bindValue(3, 0, PDO::PARAM_INT);
            $stmt->bindParam(4, $bloco_impressao, PDO::PARAM_STR);
            $stmt->bindParam(5, $id_tramite_pai, PDO::PARAM_INT);
            $stmt->bindParam(6, $id_tramite_filho, PDO::PARAM_INT);
            $stmt->bindParam(7, $data_acao, PDO::PARAM_STR);
            $stmt->bindParam(8, $numero_processo, PDO::PARAM_STR);
            $stmt->bindParam(9, $numero_apenso, PDO::PARAM_INT);
            $stmt->execute();

            $iterator->next();
        }

        Controlador::getInstance()->getConnection()->connection->commit();
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * 
 */
function getIdProcesso($processo) {


    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID FROM TB_PROCESSOS_CADASTRO WHERE NUMERO_PROCESSO = ?");
    $stmt->bindParam(1, $processo, PDO::PARAM_STR);
    $stmt->execute();
    $resul = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resul['ID'];
}

/**
 * 
 */
function format($data) {
    $array = explode("/", $data);
    $data = implode("-", array_reverse($array));
    return $data;
}

/**
 * @param <type> $processo numero do processo
 * @param <type> $id_vinc tipo de vinculação ex: anexação ou desanexação
 * @param <type> $st_ativo ação para desapensar ou desanexar processo
 * @return int
 */
function getLastBloc($processo, $id_vinc) {


    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT MAX(BLOCO_IMPRESSAO) AS BLOCO FROM TB_PROCESSOS_VINCULACAO WHERE ID_PROCESSO_PAI = ? AND ID_VINCULACAO = ?");
    $stmt->bindParam(1, $processo);
    $stmt->bindParam(2, $id_vinc);
    $stmt->execute();
    $resul = $stmt->fetch(PDO::FETCH_ASSOC);

    !empty($resul) ? $blocoreg = ++$resul['BLOCO'] : $blocoreg = 0;

    return $blocoreg;
}