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
 * @author Jhonatan R. P. Flach <jhonatanflach@gmail.com>
 */
class DaoRecurso {

    /**
     * Pega um recurso passando uma URL como parametro
     * SELECT ID, NOME, URL, IMG, ID_RECURSO_TIPO, CONTROLADOR, ACAO FROM TB_RECURSOS WHERE URL = ? LIMIT 1;
     * 
     * @param string $url
     * @return \Recurso
     * @throws Exception
     */
    public static function getRecursoByUrl($url) {
        try {



            if ($url != '') {
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID
                                             , NOME
                                             , URL
                                             , IMG
                                             , ID_RECURSO_TIPO
                                             , CONTROLADOR
                                             , ACAO
                                         FROM TB_RECURSOS WHERE URL = ? LIMIT 1");
                $stmt->bindParam(1, $url, PDO::PARAM_STR);
                $stmt->execute();

                $out = $stmt->fetchObject("Recurso");

                if (isset($out->id)) {
                    $out->filhos = self::getRecursosByParentId($out->id);
                }

                return $out;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * Pega os recursos que estão associados a um recurso pai
     * SELECT R.* FROM TB_RECURSOS R INNER JOIN TB_RECURSOS_ASSOCIACAO A
     * ON A.ID_RECURSO_PAI = ?;
     * 
     * @param string $id_recurso_pai
     * @return Array
     * @throws Exception
     */
    public static function getRecursosByParentId($id_recurso_pai) {
        try {



            $out = array();

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT R.* FROM TB_RECURSOS_ASSOCIACAO A INNER JOIN TB_RECURSOS R
                                    ON A.ID_RECURSO_FILHO = R.ID
                                    WHERE A.ID_RECURSO_PAI = ?
                                    ORDER BY A.ORDEM ASC");
            $stmt->bindParam(1, $id_recurso_pai, PDO::PARAM_INT);
            $stmt->execute();

            while ($obj = $stmt->fetchObject("Recurso")) {
                if (!is_null($obj->id_recurso_dialog)) {
                    $obj->dialog = self::getRecursoById($obj->id_recurso_dialog);
                }
                if ($obj->id_recurso_tipo == Recurso::TIPO_ABA) {
                    // recurso é uma aba, pegar os filhos
                    $obj->filhos = self::getRecursosByParentId($obj->id);
                }
                $out[$obj->id] = $obj;
            }

            return $out;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * Retorna um recurso pelo id do mesmo
     * 
     * @param type $id 
     * @return \Recurso
     * @throws Exception
     */
    public static function getRecursoById($id) {
        try {



            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT * FROM TB_RECURSOS WHERE ID = ? LIMIT 1");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            $obj = $stmt->fetchObject("Recurso");

            if ($obj !== false) {
                return $obj;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * $usuario é o id de um usuario
     * $tipo é o tipo de recurso, padrão sendo Página
     * $url é o link da página, conforme montado pelo controlador
     */
    public static function getRecursos($usuario = null, $tipo = 1, $url = '') {
        try {



            if ($url != '') {
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT * FROM TB_RECURSOS WHERE URL = ?");
                $stmt->bindParam(1, $url, PDO::PARAM_STR);
            } else {
                if (is_null($usuario)) {
                    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT * FROM TB_RECURSOS WHERE ID_RECURSO_TIPO = ?");
                    $stmt->bindParam(1, $tipo, PDO::PARAM_INT);
                } else {
                    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT R.* FROM TB_RECURSOS R 
                        LEFT JOIN TB_PRIVILEGIOS PU ON 
                        (PU.ID_RECURSO = R.ID AND PU.PERMISSAO = 1)
                        LEFT JOIN TB_PRIVILEGIOS_USUARIOS PUS ON 
                        (PUS.ID_RECURSO = R.ID AND PUS.PERMISSAO = 1) 
                        WHERE R.ID_RECURSO_TIPO = ? AND PU.ID_UNIDADE = ? AND PUS.ID_USUARIO = ?");
                    $stmt->bindParam(1, $tipo, PDO::PARAM_INT);
                    $stmt->bindParam(2, $usuario->ID_UNIDADE, PDO::PARAM_INT);
                    $stmt->bindParam(3, $usuario->ID, PDO::PARAM_INT);
                }
            }
            $stmt->execute();

            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                $out = array_change_key_case(($out), CASE_LOWER);
                return $out;
            }

            return false;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     *
     * @param stdClass $usuario
     * @return boolean 
     */
    public static function getPrivilegioByUsuario(stdClass $usuario, Recurso $recurso) {



        $privilegio = false;

        try {
            $sql = 'SELECT PERMISSAO
                      FROM TB_PRIVILEGIOS
                     WHERE ID_UNIDADE = :ID_UNIDADE AND ID_RECURSO = :ID_RECURSO';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam('ID_UNIDADE', $usuario->ID_UNIDADE, PDO::PARAM_INT);
            $stmt->bindParam('ID_RECURSO', $recurso->id, PDO::PARAM_INT);

            $stmt->execute();

            $pri_unidade = $stmt->fetch();

            $privilegio = (boolean) $pri_unidade['PERMISSAO'];

            $sql2 = 'SELECT PERMISSAO
                      FROM TB_PRIVILEGIOS_USUARIOS
                     WHERE ID_USUARIO = :ID_USUARIO AND ID_RECURSO = :ID_RECURSO';

            $stmt2 = Controlador::getInstance()->getConnection()->connection->prepare($sql2);
            $stmt2->bindParam('ID_USUARIO', $usuario->ID, PDO::PARAM_INT);
            $stmt2->bindParam('ID_RECURSO', $recurso->id, PDO::PARAM_INT);

            $stmt2->execute();

            $pri_usuario = $stmt2->fetch();

            if ($pri_usuario !== FALSE) {
                $privilegio = (boolean) $pri_usuario['PERMISSAO'];
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            $privilegio = false;
        }
        return $privilegio;
    }

    /**
     * SELECT ID, NOME, DESCRICAO, URL, IMG, ID_RECURSO_TIPO, CONTROLADOR, ACAO, CHECA_AREA_TRABALHO, CHECA_VINCULACAO, CHECA_PROTOCOLIZADORA, ID_RECURSO_DIALOG FROM TB_RECURSOS LIMIT 10;
     * 
     * @param array $where
     * @return stdClass
     * @throws Exception 
     */
    public static function listRecursos(array $where = null) {



        $sucesso = new stdClass();

        try {
            $sql = 'SELECT ID
                         , NOME
                         , DESCRICAO
                         , URL
                         , IMG
                         , ID_RECURSO_TIPO
                         , CONTROLADOR
                         , ACAO
                         , CHECA_AREA_TRABALHO
                         , CHECA_VINCULACAO
                         , CHECA_PROTOCOLIZADORA
                         , ID_RECURSO_DIALOG 
                      FROM TB_RECURSOS
                  ORDER BY NOME ASC';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
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

}