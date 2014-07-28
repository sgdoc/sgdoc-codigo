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

class DaoDigital
{
    /**
     *
     * @param array $where
     * @return stdClass
     * @throws Exception
     */
    public static function getDigitalLiberada(array $where = null)
    {
        $usuario = Controlador::getInstance()->usuario;
        if(!isset($where['ID_UNIDADE'])) {
            $where['ID_UNIDADE'] = $usuario->ID_UNIDADE;
        }
        
        $sucesso = new stdClass();
        try {
            $sql = "SELECT ID
                         , DIGITAL
                         , USO
                         , ID_USUARIO
                         , LOTE
                         , ID_UNIDADE
                      FROM TB_DIGITAL
                     WHERE ID_UNIDADE = :ID_UNIDADE
                       AND USO != '1'
                       AND ID_USUARIO is null
                  ORDER BY ID DESC
                     LIMIT 1";

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam('ID_UNIDADE', $where['ID_UNIDADE'], PDO::PARAM_INT);
            $stmt->execute();
            //
            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            $out = array_change_key_case(($out), CASE_LOWER);
            $sucesso->success = true;
            if ($out !== false && count($out) > 0) {
                $sucesso->result = $out;
            } else {
                $sucesso->result = false;
            }
        } catch (PDOException $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
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
            $sucesso = self::alterar($dados, $dados['ID']);
        } else {
            $sucesso = self::inserir($dados);
        }
        return $sucesso;
    }

    static public function alterar($dados, $id)
    {
        $idUsuario = Zend_Auth::getInstance()->getIdentity()->ID;
        
        Controlador::getInstance()->getConnection()->connection->beginTransaction();
        try {
            $query = "
                UPDATE TB_DIGITAL
                SET
                    USO = :USO,
                    ID_USUARIO = :ID_USUARIO
                WHERE ID = :ID
            ";

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($query);

            $stmt->bindParam('ID',         $id,           PDO::PARAM_INT);
            $stmt->bindParam('ID_USUARIO', $idUsuario,    PDO::PARAM_INT);
            $stmt->bindParam('USO',        $dados['USO'], PDO::PARAM_STR);

            $stmt->execute();
            Controlador::getInstance()->getConnection()->connection->commit();

            return new Output(array('success' => 'true', 'id' => $id));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            LogError::sendReport($e);
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    static public function inserir($dados)
    {
        $usuario = Controlador::getInstance()->usuario;
        $dados['ID_USUARIO'] = $usuario->ID;
        if(!isset($dados['ID_UNIDADE'])) {
            $dados['ID_UNIDADE'] = $usuario->ID_UNIDADE;
        }
        
        Controlador::getInstance()->getConnection()->connection->beginTransaction();

        try {
            $query = "INSERT INTO TB_DIGITAL (DIGITAL
                                            , USO
                                            , ID_USUARIO
                                            , LOTE
                                            , ID_UNIDADE
                                    ) VALUES (:DIGITAL
                                            , :USO
                                            , :ID_USUARIO
                                            , :LOTE
                                            , :ID_UNIDADE)";
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($query);

            $stmt->bindParam('DIGITAL',    $dados['DIGITAL'],    PDO::PARAM_STR);
            $stmt->bindParam('USO',        $dados['USO'],        PDO::PARAM_STR);
            $stmt->bindParam('ID_USUARIO', $dados['ID_USUARIO'], PDO::PARAM_STR);
            $stmt->bindParam('LOTE',       $dados['LOTE'],       PDO::PARAM_INT);
            $stmt->bindParam('ID_UNIDADE', $dados['ID_UNIDADE'], PDO::PARAM_INT);

            $stmt->execute();
            $id = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_DIGITAL_ID_SEQ');

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true', 'id' => $id));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            LogError::sendReport($e);
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }
    
    /**
     * verificar se é necessário ser o dono da minuta para excluir
     * @param type $where
     * @return \Output 
     */
    public function excluir($where)
    {        
        Controlador::getInstance()->getConnection()->connection->beginTransaction();
        try {

            $query = "DELETE FROM TB_DIGITAL WHERE ID = :ID";

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($query);

            $stmt->bindParam('ID', $where['ID'], PDO::PARAM_INT);
            $stmt->execute();
            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true', 'id' => $where['ID']));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            LogError::sendReport($e);
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }
}