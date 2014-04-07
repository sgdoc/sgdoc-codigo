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
 * SELECT ID, NOME, DESCRICAO FROM TB_GRUPOS_PRIVILEGIOS
 *
 * @author 97714267100
 */
class DaoGrupoPrivilegio
{

    /**
     *
     * @param array $where
     * @return stdClass
     * @throws Exception
     */
    public static function getGruposPrivilegios (array $where = null)
    {

        

        $sucesso = new stdClass();
        try {
            $sql = 'SELECT ID
                         , NOME
                         , DESCRICAO
                      FROM TB_GRUPOS_PRIVILEGIOS
                  ORDER BY NOME';

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

    /**
     *
     * @param array $where
     * @return stdClass
     * @throws Exception
     */
    public static function getGruposPrivilegiosPorRecurso (array $where = null)
    {

        

        $sucesso = new stdClass();
        try {
            $sql = 'SELECT R.ID,
                           GP.NOME,
                           GP.DESCRICAO,
                           R.NOME,
                           R.DESCRICAO,
                           RT.NOME AS TIPO,
                           GPR.PERMISSAO
                      FROM TB_GRUPO_PRIVILEGIO_RECURSOS GPR
                INNER JOIN TB_GRUPOS_PRIVILEGIOS GP ON GP.ID = GPR.ID_GRUPO_PRIVILEGIO
                INNER JOIN TB_RECURSOS R ON R.ID = GPR.ID_RECURSO
                INNER JOIN TB_RECURSOS_TIPOS RT ON RT.ID = R.ID_RECURSO_TIPO
                     WHERE GPR.ID_GRUPO_PRIVILEGIO = :ID_GRUPO_PRIVILEGIO
                  ORDER BY R.NOME ASC';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam('ID_GRUPO_PRIVILEGIO', $where['ID_GRUPO_PRIVILEGIO'], PDO::PARAM_INT);
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
     */
    public static function inserirPermissoesTB_PRIVILEGIOS ($dados)
    {

        
        Controlador::getInstance()->getConnection()->connection->beginTransaction();
        $sucesso = new stdClass();
        try {
            $idUnidade = (integer) $dados['ID_UNIDADE'];
            $idGrupoPrivilegio = (integer) $dados['ID_GRUPO_PRIVILEGIO'];

            $sql = "INSERT INTO TB_PRIVILEGIOS (ID_UNIDADE, ID_RECURSO, PERMISSAO) SELECT  :ID_UNIDADE, GPR.ID_RECURSO,GPR.PERMISSAO FROM TB_GRUPO_PRIVILEGIO_RECURSOS AS GPR WHERE GPR.ID_GRUPO_PRIVILEGIO = :ID_GRUPO_PRIVILEGIO";

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
            $stmt->bindParam('ID_UNIDADE', $idUnidade, PDO::PARAM_INT);
            $stmt->bindParam('ID_GRUPO_PRIVILEGIO', $idGrupoPrivilegio, PDO::PARAM_INT);
            $stmt->execute();

            Controlador::getInstance()->getConnection()->connection->commit();
            $sucesso = (Object) array('success' => 'true', 'message' => 'Permissões geradas com sucesso!');
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            $sucesso = (Object) array('success' => 'false', 'error' => $e->getMessage());
        }

        return $sucesso;
    }

}

