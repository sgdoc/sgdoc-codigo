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
 * Description of DaoPrivilegio
 *
 * @author 97714267100
 */
class DaoPrivilegioUsuario
{
    /**
     *
     * @param array $where
     * @return stdClass
     * @throws Exception
     */
    public static function getPrivilegiosUsuario(array $where = null)
    {

        

        $sucesso = new stdClass();
        try {
            $sql = 'SELECT ID
                         , ID_USUARIO
                         , ID_RECURSO
                         , PERMISSAO
                      FROM TB_PRIVILEGIOS_USUARIOS
                     WHERE ID_USUARIO = :ID_USUARIO
                       AND ID_RECURSO = :ID_RECURSO';
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);

            $stmt->bindParam('ID_USUARIO', $where['ID_USUARIO'], PDO::PARAM_INT);
            $stmt->bindParam('ID_RECURSO', $where['ID_RECURSO'], PDO::PARAM_INT);

            $stmt->execute();

            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $out = array_change_key_case(($out), CASE_LOWER);
            $sucesso->success = true;
            if (count($out) > 0) {
                $sucesso->result = $out;
            } else {
                $sucesso->result = false;
            }
        } catch (PDOException $e) {
            $sucesso->error = true;
            $sucesso->result = 'Error Query: [' . $e->getMessage() . ']';
        }
        return $sucesso;
    }

    /**
     *
     * @param type $dados 
     */
    public static function salvar($dados)
    {
        $sucesso = false;
        if(array_key_exists('ID', $dados)) {
            $sucesso = self::alterarPrivilegioUsuario($dados, $dados['ID']);
        } else {
            $sucesso = self::inserirPrivilegioUsuario($dados);
        }
        return $sucesso;
    }

    /**
     *
     * @param type $dados
     * @return \Output
     * @throws Exception 
     */
    public static function inserirPrivilegioUsuario ($dados)
    {

        
        try {
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $sql = 'INSERT INTO TB_PRIVILEGIOS_USUARIOS (ID_USUARIO
                                                       , ID_RECURSO
                                                       , PERMISSAO
                                               ) VALUES (:ID_USUARIO
                                                       , :ID_RECURSO
                                                       , :PERMISSAO)';
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);

            $stmt->bindParam('ID_USUARIO', $dados['ID_USUARIO'], PDO::PARAM_INT);
            $stmt->bindParam('ID_RECURSO', $dados['ID_RECURSO'], PDO::PARAM_INT);
            $stmt->bindParam('PERMISSAO',  $dados['PERMISSAO'],  PDO::PARAM_INT);

            $stmt->execute();

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true', 'message' => 'Unidade cadastrada com sucesso!'));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
            throw new Exception($e);
        }
    }

    /**
     *
     * @param type $dados
     * @param type $where
     * @return \Output
     * @throws Exception 
     */
    public static function alterarPrivilegioUsuario ($dados, $id)
    {

        

        try {
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $sql = 'UPDATE TB_PRIVILEGIOS_USUARIOS SET ID_USUARIO = :ID_USUARIO
                                                     , ID_RECURSO = :ID_RECURSO
                                                     , PERMISSAO = :PERMISSAO
                                                 WHERE ID = :ID';
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);

            $stmt->bindParam('ID_USUARIO', $dados['ID_USUARIO'], PDO::PARAM_INT);
            $stmt->bindParam('ID_RECURSO', $dados['ID_RECURSO'], PDO::PARAM_INT);
            $stmt->bindParam('PERMISSAO',  $dados['PERMISSAO'],  PDO::PARAM_INT);
            $stmt->bindParam('ID',         $id,                  PDO::PARAM_INT);

            $stmt->execute();

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true', 'message' => 'Unidade cadastrada com sucesso!'));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
            throw new Exception($e);
        }
    }

    /**
     *
     * @param array $where
     * @return \Output 
     */
    public static function deletePrivilegioUsuario($where)
    {

        

        try {
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $sql = "DELETE FROM TB_PRIVILEGIOS_USUARIOS
                          WHERE ID = :ID";
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);

            $stmt->bindParam('ID', $where['ID'], PDO::PARAM_INT);

            $stmt->execute();

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true'));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }
    
    /**
     * @param integer $id_usuario
     * @return array
     */
    public static function getPrivilegiosPorUsuario($id_usuario)
    {

        

        try {
            $sql = 'SELECT ID
                         , ID_USUARIO
                         , ID_RECURSO
                         , PERMISSAO
                      FROM TB_PRIVILEGIOS_USUARIOS
                     WHERE ID_USUARIO = :ID_USUARIO';
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);

            $stmt->bindParam('ID_USUARIO', $id_usuario, PDO::PARAM_INT);

            $stmt->execute();

            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $out;
        } catch (PDOException $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }
}