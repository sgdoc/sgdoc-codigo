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
 * @author jhonatan.flach
 */
class DaoAssuntoDocumento {

    /**
     * 
     */
    public static function uniqueAssunto(AssuntoDocumento $assunto) {
        try {

            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            // checar se já existe um assunto cadastrado com este texto
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT count(*) as num, ID FROM TB_DOCUMENTOS_ASSUNTO WHERE ASSUNTO ilike ? GROUP BY ID");
            $stmt->bindParam(1, $assunto->assunto->assunto, PDO::PARAM_STR);
            $stmt->execute();

            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($out !== false && $out['NUM'] > 0) {
                // verifica se é diferente do que está sendo atualizado
                if ((int) $out['ID'] != (int) $assunto->id) {
                    // Já está cadastrado, retornar sem sucesso
                    return new Output(array('success' => 'false', 'error' => 'Já existe assunto com mesma descrição cadastrado'));
                }
            }
            // Não está cadastrado, retornar sucesso
            return new Output(array('success' => 'true'));
        } catch (Exception $e) {
            // Erro, retornar sem sucesso
            return new Output(array('success' => 'false', 'error' => 'Erro ao tentar verificar duplicidade do assunto: ' . $e->getMessage()));
        }
    }

    /**
     * 
     */
    public static function getAssunto($assunto = false, $field = false) {
        try {


            $campos = $field ? $field : strtolower('ID,ASSUNTO');

            if (!$assunto) {
                return NULL;
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT {$campos} FROM TB_DOCUMENTOS_ASSUNTO WHERE ID = ? LIMIT 1");
            $stmt->bindParam(1, $assunto, PDO::PARAM_INT);
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
    public static function inserirAssunto(AssuntoDocumento $assunto) {
        try {

            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            if (!$assunto->usuario) {
                $assunto->usuario = Zend_Auth::getInstance()->getIdentity()->ID;
            }

            $assunto->assunto->homologado = (int) 0;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_DOCUMENTOS_ASSUNTO (ASSUNTO, USUARIO, HOMOLOGADO)
            VALUES (?,?,?)");
            $stmt->bindParam(1, $assunto->assunto->assunto, PDO::PARAM_STR);
            $stmt->bindParam(2, $assunto->assunto->usuario, PDO::PARAM_INT);
            $stmt->bindParam(3, $assunto->assunto->homologado, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_DOCUMENTOS_ASSUNTO', Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_DOCUMENTOS_ASSUNTO_ID_SEQ'), Zend_Auth::getInstance()->getIdentity()->ID, 'inserir');

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => 'Assunto cadastrado com sucesso!'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * 
     */
    public static function alterarAssunto(AssuntoDocumento $assunto) {
        try {


            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_ASSUNTO SET ASSUNTO=?
            WHERE ID = ?");
            $stmt->bindParam(1, $assunto->assunto->assunto, PDO::PARAM_STR);
            $stmt->bindParam(2, $assunto->assunto->id, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_DOCUMENTOS_ASSUNTO', $assunto->id, Zend_Auth::getInstance()->getIdentity()->ID, 'alterar :: ' . print_r($assunto, true));

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
    public static function deleteAssunto($id, $status) {
        try {

            Controlador::getInstance()->getConnection()->connection->beginTransaction();
            $status = 0;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_ASSUNTO SET HOMOLOGADO = ? WHERE ID = ?");
            $stmt->bindParam(1, $status, PDO::PARAM_INT);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_DOCUMENTOS_ASSUNTO', $id, Zend_Auth::getInstance()->getIdentity()->ID, 'excluir');

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true', 'message' => 'Assunto excluido com sucesso!'));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * 
     */
    public static function alterarHomologacao($id, $homologacao) {
        try {


            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $tx_homologacao = 'Homologado';
            if ($homologacao == 0) {
                $tx_homologacao = 'Não-homologado';
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_ASSUNTO SET HOMOLOGADO = ? WHERE ID = ?");
            $stmt->bindParam(1, $homologacao, PDO::PARAM_INT);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_DOCUMENTOS_ASSUNTO', $id, Zend_Auth::getInstance()->getIdentity()->ID, 'alterou homologação :: ' . $tx_homologacao);

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => 'Homologacao do assunto alterada com sucesso!'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * 
     */
    public static function alterarAssuntoReal(Array $ids, $id_real) {
        try {


            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            foreach ($ids as $k => $id) {

                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DOCUMENTOS_ASSUNTO SET ID_ASSUNTO_REAL = ?, HOMOLOGADO = 0 WHERE ID = ?");
                $stmt->bindParam(1, $id_real, PDO::PARAM_INT);
                $stmt->bindParam(2, $id, PDO::PARAM_INT);
                $stmt->execute();

                new Log('TB_DOCUMENTOS_ASSUNTO', $id, Zend_Auth::getInstance()->getIdentity()->ID, "alterou assunto real do assunto {$id} para {$id_real}.");
            }

            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'message' => 'Homologação do(s) assunto(s) alterada(s) com sucesso!'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollBack();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

}