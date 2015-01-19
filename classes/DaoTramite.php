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
 * Description of DaoTramite
 *
 * @author 97714267100
 */
class DaoTramite {

    /**
     *
     * @param array $where
     * @param type $campo
     * @return type 
     */
    public static function getTramite(array $where = null) {
        $sucesso = new stdClass();


        try {

            $sql = "SELECT T.ID
                          ,T.ID_UNIDADE
                          ,T.ID_REFERENCIA
                      FROM TB_TRAMITES T 
                     WHERE T.ID_UNIDADE = :ID_UNIDADE";

            if (isset($where['ID_REFERENCIA'])) {
                $sql .= " AND T.ID_REFERENCIA = :ID_REFERENCIA";
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);

            $stmt->bindParam('ID_UNIDADE', $where['ID_UNIDADE'], PDO::PARAM_INT);

            if (isset($where['ID_REFERENCIA'])) {
                $stmt->bindParam('ID_REFERENCIA', $where['ID_REFERENCIA'], PDO::PARAM_INT);
            }
            $stmt->execute();
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $out = array_change_key_case(($out), CASE_LOWER);
            $sucesso->sucesso = true;
            if (count($out) > 0) {
                $sucesso->resultado = $out;
            } else {
                $sucesso->resultado = false;
            }
        } catch (PDOException $e) {
            $sucesso->error = true;
            $sucesso->resultado = 'Error Query: [' . $e->getMessage() . ']';
        }
        return $sucesso;
    }

    /**
     *
     * @param array $where
     * @param integer $idUnidadeSuperior
     * @return type 
     */
    public static function listTramiteDisponiveis(array $where = NULL, $idUnidadeSuperior = NULL) {
        $sucesso = new stdClass();


        try {

            $sql = "SELECT U.ID, U.NOME
                      FROM TB_UNIDADES U
                     WHERE (U.ID != U.UOP) AND U.TIPO = :TIPO";

            if (!is_null($idUnidadeSuperior)) {
                $whereUOP = 'AND U.UOP = :UOP';
            }

            if (!isset($where['ALL'])) {
                $sql .= " AND U.ID NOT IN (SELECT T.ID_REFERENCIA as IDS FROM TB_TRAMITES T WHERE T.ID_UNIDADE = :ID)";
            }

            $sql .= " AND U.ID != :ID AND U.ST_ATIVO = 1 {$whereUOP} ORDER BY U.NOME ASC";

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);

            $stmt->bindParam('ID', $where['ID'], PDO::PARAM_INT);
            $stmt->bindParam('TIPO', $where['TIPO'], PDO::PARAM_INT);
            $stmt->bindParam('UOP', $idUnidadeSuperior, PDO::PARAM_INT);

            $stmt->execute();

            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $out = array_change_key_case(($out), CASE_LOWER);
            $sucesso->sucesso = true;
            if (count($out) > 0) {
                $sucesso->resultado = $out;
            } else {
                $sucesso->resultado = false;
            }
        } catch (PDOException $e) {
            $sucesso->error = true;
            $sucesso->resultado = 'Error Query: [' . $e->getMessage() . ']';
        }
        return $sucesso;
    }

    /**
     *
     * @param array $where
     * @param type $campo
     * @return type 
     */
    public static function listTramiteAtivo(array $where = null) {
        $sucesso = new stdClass();


        try {

            $sql = "
                SELECT 
                    UNIDADE_REFERENCIA.ID AS ID
                    ,UNIDADE_REFERENCIA.NOME AS NOME
                    ,UT.TIPO AS TIPO
                    ,UT.ID AS ID_TIPO
                FROM TB_UNIDADES U
                    INNER JOIN TB_TRAMITES T
                        ON U.ID = T.ID_UNIDADE
                    INNER JOIN TB_UNIDADES UNIDADE_REFERENCIA
                        ON T.ID_REFERENCIA = UNIDADE_REFERENCIA.ID
                    INNER JOIN TB_UNIDADES_TIPO UT
                        ON UNIDADE_REFERENCIA.TIPO = UT.ID
                WHERE 
                    U.ID = :ID 
                    AND (U.ID != U.UOP) 
                    AND (UNIDADE_REFERENCIA.ID != UNIDADE_REFERENCIA.UOP)
                ORDER 
                    BY UT.TIPO ASC, 
                    UNIDADE_REFERENCIA.NOME ASC
            ";

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam('ID', $where['ID'], PDO::PARAM_INT);
            $stmt->execute();

            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $out = array_change_key_case(($out), CASE_LOWER);
            $sucesso->sucesso = true;
            if (count($out) > 0) {
                $sucesso->resultado = $out;
            } else {
                $sucesso->resultado = false;
            }
        } catch (PDOException $e) {
            $sucesso->error = true;
            $sucesso->resultado = 'Error Query: [' . $e->getMessage() . ']';
        }
        return $sucesso;
    }

    /**
     *
     * @param type $dados 
     */
    public static function salvar($dados) {
        $sucesso = false;
        if (array_key_exists('ID', $dados)) {
            $sucesso = self::alterar($dados, $dados['ID']);
        } else {
            $sucesso = self::inserir($dados);
        }
        return $sucesso;
    }

    /**
     *
     * @param type $dados
     * @return \Output
     * @throws Exception 
     */
    public static function inserir($dados) {


        try {
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $sql = 'INSERT INTO TB_TRAMITES (ID_UNIDADE, ID_REFERENCIA, ST_PERMISSAO) VALUES (:ID_UNIDADE, :ID_REFERENCIA, 0)';
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam('ID_UNIDADE', $dados['ID_UNIDADE'], PDO::PARAM_INT);
            $stmt->bindParam('ID_REFERENCIA', $dados['ID_REFERENCIA'], PDO::PARAM_INT);

            $stmt->execute();

            Controlador::getInstance()->getConnection()->connection->commit();
            return array('success' => 'true', 'message' => 'Trâmite cadastrado com sucesso!');
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return array('success' => 'false', 'error' => $e->getMessage());
        }
    }

    /**
     *
     * @param type $dados
     * @param type $where
     * @return \Output
     * @throws Exception 
     */
    public static function alterar($dados, $id) {



        try {
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $sql = 'UPDATE TB_TRAMITES SET ST_PERMISSAO = :ST_PERMISSAO 
                                     WHERE ID = :ID';
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);

            $stmt->bindParam('ID', $id, PDO::PARAM_INT);
            $stmt->bindParam('ST_PERMISSAO', $dados['ST_PERMISSAO'], PDO::PARAM_INT);

            $stmt->execute();

            Controlador::getInstance()->getConnection()->connection->commit();
            return array('success' => 'true', 'message' => 'Trâmite alterado com sucesso!');
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return array('success' => 'false', 'error' => $e->getMessage());
        }
    }

    /**
     *
     * @param array $where
     * @return \Output 
     */
    public static function deletarTramite($where) {



        try {
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $sql = "DELETE FROM TB_TRAMITES
                          WHERE ID = :ID";
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam('ID', $where['ID'], PDO::PARAM_INT);

            $stmt->execute();

            Controlador::getInstance()->getConnection()->connection->commit();
            return array('success' => 'true', 'message' => 'Vinculação excluída com sucesso.');
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return array('success' => 'false', 'error' => $e->getMessage());
        }
    }

    /**
     *
     * @param array $where
     * @return \Output 
     */
    public static function deletarTramitePorUnidade($where) {



        try {
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $sql = "DELETE FROM TB_TRAMITES
                          WHERE ID_UNIDADE = :ID_UNIDADE";
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam('ID_UNIDADE', $where['ID_UNIDADE'], PDO::PARAM_INT);

            $stmt->execute();

            Controlador::getInstance()->getConnection()->connection->commit();
            return array('success' => 'true', 'message' => 'Vinculação excluída com sucesso.');
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return array('success' => 'false', 'error' => $e->getMessage());
        }
    }

    /**
     * 
     */
    public static function clearAllTramitesByIdUnidade($id) {
        try {



            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("DELETE FROM TB_TRAMITES WHERE ID_UNIDADE = ? OR ID_REFERENCIA = ?");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->execute();

            Controlador::getInstance()->getConnection()->connection->commit();
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            throw new Exception($e);
        }
    }

}