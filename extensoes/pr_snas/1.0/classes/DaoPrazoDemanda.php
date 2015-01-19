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

include(__BASE_PATH__ . '/classes/DaoPrazo.php');

/**
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
class DaoPrazoDemanda extends DaoPrazo {

    /**
     * 
     */
    public static function getPrazo($prazo = false, $campo = false) {
        try {



            $campo = $campo ? $campo : '*';

            if (!$prazo) {
                throw new Exception($e);
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT $campo FROM TB_CONTROLE_PRAZOS WHERE SQ_PRAZO = ? LIMIT 1");
            $stmt->bindParam(1, $prazo, PDO::PARAM_INT);
            $stmt->execute();

            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                $out = array_change_key_case(($out), CASE_LOWER);

                if ($campo === '*') {
                    return $out;
                }
                return $out[strtolower($campo)];
            }

            return false;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * 
     */
    public static function salvarPrazo(Prazo $prazo) {
        try {


            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            if (!isset($prazo->prazo->id_usuario_destino) || $prazo->prazo->id_usuario_destino == '') {
                $prazo->prazo->id_usuario_destino = NULL;
            }

            $prazo->id_unid_origem = isset($prazo->prazo->id_unid_origem) ?
                    $prazo->prazo->id_unid_origem : Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE_ORIGINAL;
            $prazo->id_usuario_origem = Controlador::getInstance()->usuario->ID;
            
            $dt_prazo = Util::formatDate($prazo->prazo->dt_prazo);
            $pai =  strlen($prazo->prazo->nu_proc_dig_ref_pai) > 0 ? $prazo->prazo->nu_proc_dig_ref_pai : null ;
            
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_CONTROLE_PRAZOS (NU_PROC_DIG_REF, ID_USUARIO_ORIGEM, ID_USUARIO_DESTINO, ID_UNID_ORIGEM, ID_UNID_DESTINO, DT_PRAZO, TX_SOLICITACAO)
            VALUES (?,?,?,?,?,?,?)");
            $stmt->bindParam(1, $prazo->prazo->nu_proc_dig_ref, PDO::PARAM_STR);
            $stmt->bindParam(2, $prazo->prazo->id_usuario_origem, PDO::PARAM_INT);
            $stmt->bindParam(3, $prazo->prazo->id_usuario_destino, PDO::PARAM_INT);
            $stmt->bindParam(4, $prazo->prazo->id_unid_origem, PDO::PARAM_INT);
            $stmt->bindParam(5, $prazo->prazo->id_unid_destino, PDO::PARAM_INT);
            $stmt->bindParam(6, $dt_prazo, PDO::PARAM_STR);
            $stmt->bindParam(7, $prazo->prazo->tx_solicitacao, PDO::PARAM_STR);
            $stmt->execute();
            
            $lastIdPrazo = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_CONTROLE_PRAZOS_SQ_PRAZO_SEQ');

            $sttt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO EXT__SNAS__TB_CONTROLE_PRAZOS (ID,NU_PROC_DIG_REF_PAI) VALUES (?,?)");
            $sttt->bindParam(1, $lastIdPrazo, PDO::PARAM_INT);
            $sttt->bindParam(2, $pai, PDO::PARAM_STR);
            $sttt->execute();

            new Log('TB_CONTROLE_PRAZOS', $lastIdPrazo, Zend_Auth::getInstance()->getIdentity()->ID, 'inserir');

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => 'Prazo cadastrado com sucesso!'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * 
     */
    public static function removerPrazo($digital) {
        try {


            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $id = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_CONTROLE_PRAZOS_SQ_PRAZO_SEQ');

            $stml = Controlador::getInstance()->getConnection()->connection->prepare("DELETE FROM TB_LOGS WHERE NM_TABELA = 'TB_CONTROLE_PRAZOS' AND ID_REGISTRO = ?");
            $stml->bindParam(1, $id, PDO::PARAM_INT);
            $stml->execute();

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("DELETE FROM TB_CONTROLE_PRAZOS WHERE NU_PROC_DIG_REF = ?");
            $stmt->bindParam(1, $digital, PDO::PARAM_STR);
            $stmt->execute();

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'error' => 'Prazo excluido com sucesso!'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * A ação só é permitida aos usuários que estão relacionados como destinatários
     * ou àqueles que efetivamente estejam lotados na unidade de destino, não sendo
     * possível realizar, por exemplo, a usuários que trocaram de Unidade 
     * temporariamente
     */
    public static function responderPrazo(Prazo $prazo) {
        try {

            $oUsuario = Controlador::getInstance()->usuario;
            $id_unidade_usuario_resposta = Controlador::getInstance()->usuario->ID_UNIDADE;

            Controlador::getInstance()->getConnection()->connection->beginTransaction();


            if (!isset($prazo->prazo->nu_proc_dig_res) ||
                    (strtolower($prazo->prazo->nu_proc_dig_res) == 'null')) {
                $prazo->prazo->nu_proc_dig_res = NULL;
            }

            $usuario = $oUsuario->ID;
            $unidade = $oUsuario->ID_UNIDADE_ORIGINAL;
            $prazo->prazo->id_usuario_resposta = $usuario;
            $prazo->prazo->fg_status = 'RP';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                    UPDATE TB_CONTROLE_PRAZOS 
                    SET NU_PROC_DIG_RES = ?,
                        ID_USUARIO_RESPOSTA = ?, 
                        TX_RESPOSTA = ?, 
                        DT_RESPOSTA = CURRENT_TIMESTAMP(0), 
                        FG_STATUS = ?,
                        ID_UNIDADE_USUARIO_RESPOSTA = ?
                    WHERE SQ_PRAZO = ? 
                        AND (ID_USUARIO_DESTINO = ? OR ID_UNID_DESTINO = ?)
                        AND FG_STATUS = 'AR'
            ");

            $stmt->bindParam(1, $prazo->prazo->nu_proc_dig_res, PDO::PARAM_STR);
            $stmt->bindParam(2, $prazo->prazo->id_usuario_resposta, PDO::PARAM_INT);
            $stmt->bindParam(3, $prazo->prazo->tx_resposta, PDO::PARAM_STR);
            $stmt->bindParam(4, $prazo->prazo->fg_status, PDO::PARAM_STR);

            $stmt->bindParam(5, $id_unidade_usuario_resposta);

            $stmt->bindParam(6, $prazo->prazo->sq_prazo, PDO::PARAM_INT);
            $stmt->bindParam(7, $prazo->prazo->id_usuario_resposta, PDO::PARAM_INT);
            $stmt->bindParam(8, $unidade, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_CONTROLE_PRAZOS', $prazo->prazo->sq_prazo, Zend_Auth::getInstance()->getIdentity()->ID, 'responder');

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => 'Prazo respondido com sucesso!'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

}
