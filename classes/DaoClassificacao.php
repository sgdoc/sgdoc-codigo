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
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
class DaoClassificacao {

    /**
     * 
     */
    public static function getClassificacao($classificacao = false, $field = false) {
        try {



            $campos = $field ? $field : strtolower('ID,NU_CLASSIFICACAO,DS_CLASSIFICACAO,ID_CLASSIFICACAO_PAI');

            if (!$classificacao) {
                throw new Exception($e);
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT {$campos} FROM TB_CLASSIFICACAO WHERE ID = ? LIMIT 1");
            $stmt->bindParam(1, $classificacao, PDO::PARAM_INT);
            $stmt->execute();

            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {

                $out = array_change_key_case(($out), CASE_LOWER);

                if (!$field) {
                    return $out;
                }
                return $out[$field];
            }

            return false;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * 
     */
    public static function inserirClassificacao(Classificacao $classificacao) {
        try {

            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            if (!$classificacao->id_classificacao_pai || $classificacao->id_classificacao_pai == 'null') {
                $classificacao->id_classificacao_pai = null;
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_CLASSIFICACAO (NU_CLASSIFICACAO, DS_CLASSIFICACAO, ID_CLASSIFICACAO_PAI)
            VALUES (?,?,?)");
            $stmt->bindParam(1, $classificacao->nu_classificacao, PDO::PARAM_STR);
            $stmt->bindParam(2, $classificacao->ds_classificacao, PDO::PARAM_STR);
            $stmt->bindParam(3, $classificacao->id_classificacao_pai, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_CLASSIFICACAO', Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_CLASSIFICACAO_ID_SEQ'), Zend_Auth::getInstance()->getIdentity()->ID, 'inserir');

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => 'Classificação cadastrada com sucesso!'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * 
     */
    public static function alterarClassificacao(Classificacao $classificacao) {
        try {


            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            if (!$classificacao->id_classificacao_pai || $classificacao->id_classificacao_pai == 'null') {
                $classificacao->id_classificacao_pai = null;
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_CLASSIFICACAO SET NU_CLASSIFICACAO=?, DS_CLASSIFICACAO=?, ID_CLASSIFICACAO_PAI=?
            WHERE ID = ?");
            $stmt->bindParam(1, $classificacao->nu_classificacao, PDO::PARAM_STR);
            $stmt->bindParam(2, $classificacao->ds_classificacao, PDO::PARAM_STR);
            $stmt->bindParam(3, $classificacao->id_classificacao_pai, PDO::PARAM_INT);
            $stmt->bindParam(4, $classificacao->id, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_CLASSIFICACAO', $classificacao->id, Zend_Auth::getInstance()->getIdentity()->ID, 'alterar :: ' . print_r($classificacao, true));

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => 'Informações alteradas com sucesso!'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * 
     */
    public static function deleteClassificacao($id, $status) {
        try {


            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_CLASSIFICACAO SET ST_ATIVO = ? WHERE ID = ?");
            $stmt->bindParam(1, $status, PDO::PARAM_INT);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_CLASSIFICACAO', $id, Zend_Auth::getInstance()->getIdentity()->ID, 'excluir');

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true', 'message' => 'Classificação excluída com sucesso!'));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * 
     */
    public static function alterarClassificacaoDocumento($id_documento, $id_classificacao) {
        try {

            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_CADASTRO SET ID_CLASSIFICACAO=? 
            WHERE ID = ?");
            $stmt->bindParam(1, $id_classificacao, PDO::PARAM_INT);
            $stmt->bindParam(2, $id_documento, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_DOCUMENTOS', $id_documento, Zend_Auth::getInstance()->getIdentity()->ID, 'alterou classificação :: ' . $id_classificacao);

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => 'Classificação do documento alterado com sucesso!'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

}