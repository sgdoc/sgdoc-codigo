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
 * SELECT ID, ID_UNIDADE, ID_RECURSO, PERMISSAO FROM TB_PRIVILEGIOS
 *
 * @author 97714267100
 */
class DaoPrivilegio
{
    /**
     *
     * @param array $where
     * @return stdClass
     * @throws Exception
     */
    public static function getPrivilegios(array $where = null) {

        

        $sucesso = new stdClass();
        try {
            $sql = 'SELECT ID
                         , ID_UNIDADE
                         , ID_RECURSO
                         , PERMISSAO
                      FROM TB_PRIVILEGIOS
                     WHERE ID_UNIDADE = :ID_UNIDADE';

            if(isset($where['ID_RECURSO']))
                       $sql.=' AND ID_RECURSO = :ID_RECURSO';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            
            if(isset($where['ID_RECURSO']))
            $stmt->bindParam('ID_RECURSO', $where['ID_RECURSO'], PDO::PARAM_INT);

            $stmt->bindParam('ID_UNIDADE', $where['ID_UNIDADE'], PDO::PARAM_INT);

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
            $sucesso = self::alterarPrivilegio($dados, $dados['ID']);
        } else {
            $sucesso = self::inserirPrivilegio($dados);
        }
        return $sucesso;
    }

    /**
     *
     * @param type $dados
     * @return \Output
     * @throws Exception 
     */
    public static function inserirPrivilegio ($dados)
    {

        
        try {
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $sql = 'INSERT INTO TB_PRIVILEGIOS (ID_UNIDADE, ID_RECURSO, PERMISSAO) VALUES (?,?,?)';
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam(1, $dados['ID_UNIDADE'], PDO::PARAM_INT);
            $stmt->bindParam(2, $dados['ID_RECURSO'], PDO::PARAM_INT);
            $stmt->bindParam(3, $dados['PERMISSAO'],  PDO::PARAM_INT);

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
    public static function alterarPrivilegio ($dados, $id)
    {

        

        try {
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $sql = 'UPDATE TB_PRIVILEGIOS SET ID_UNIDADE = :ID_UNIDADE
                                            , ID_RECURSO = :ID_RECURSO
                                            , PERMISSAO = :PERMISSAO
                                        WHERE ID = :ID';
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);

            $stmt->bindParam('ID_UNIDADE', $dados['ID_UNIDADE'], PDO::PARAM_INT);
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
    public static function deletePrivilegio($where)
    {

        

        try {
            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $sql = "DELETE FROM TB_PRIVILEGIOS
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
     *
     * @param array $where
     * @return \Output 
     */
    public static function deletePrivilegioPorUnidade($where)
    {

        
        try {
            Controlador::getInstance()->getConnection()->connection->beginTransaction();
            $sql = "DELETE FROM TB_PRIVILEGIOS
                          WHERE ID_UNIDADE = :ID_UNIDADE";
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam('ID_UNIDADE', $where['ID_UNIDADE'], PDO::PARAM_INT);
            $stmt->execute();
            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true'));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }
    
    /**
     * @param integer $id_unidade 
     * @return \Output
     */
    public static function getPrivilegiosPorUnidade($id_unidade)
    {

        

        try {
            $sql = 'SELECT ID
                         , ID_UNIDADE
                         , ID_RECURSO
                         , PERMISSAO
                      FROM TB_PRIVILEGIOS
                     WHERE ID_UNIDADE = :ID_UNIDADE';
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);

            $stmt->bindParam('ID_UNIDADE', $id_unidade, PDO::PARAM_INT);

            $stmt->execute();

            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $out;
        } catch (PDOException $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }
}