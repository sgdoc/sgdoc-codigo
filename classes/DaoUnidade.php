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
class DaoUnidade {

    public static function alterarUnidade(Unidade $unidade) {
        try {

            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            /**
             * BugFix : Forcar salvamento de valor NULL quando nao possui relacionamento...
             */
            $unidade->uaaf = $unidade->uaaf == 'null' ? NULL : $unidade->uaaf;
            $unidade->cr = $unidade->cr == 'null' ? NULL : $unidade->cr;
            $unidade->superior = $unidade->superior == 'null' ? NULL : $unidade->superior;
            $unidade->diretoria = $unidade->diretoria == 'null' ? NULL : $unidade->diretoria;

            if ($unidade->isuop) {
                $unidade->uop = $unidade->id;
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_UNIDADES SET NOME=?,SIGLA=?,UAAF=?,CR=?,SUPERIOR=?,DIRETORIA=?,TIPO=?,UP=?,CODIGO=?,UF=?,EMAIL=?,UOP=?  WHERE ID = ?");
            $stmt->bindParam(1, $unidade->nome, PDO::PARAM_STR);
            $stmt->bindParam(2, $unidade->sigla, PDO::PARAM_STR);
            $stmt->bindParam(3, $unidade->uaaf);
            $stmt->bindParam(4, $unidade->cr);
            $stmt->bindParam(5, $unidade->superior);
            $stmt->bindParam(6, $unidade->diretoria);
            $stmt->bindParam(7, $unidade->tipo, PDO::PARAM_INT);
            $stmt->bindParam(8, $unidade->up, PDO::PARAM_INT);
            $stmt->bindParam(9, $unidade->codigo, PDO::PARAM_INT);
            $stmt->bindParam(10, $unidade->uf, PDO::PARAM_INT);
            $stmt->bindParam(11, $unidade->email, PDO::PARAM_STR);
            $stmt->bindParam(12, $unidade->uop, PDO::PARAM_INT);
            $stmt->bindParam(13, $unidade->id, PDO::PARAM_INT);

            $stmt->execute();

            new Log('TB_UNIDADES', $unidade->id, Zend_Auth::getInstance()->getIdentity()->ID, 'alterar :: ' . (print_r($out, true)));

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true', 'message' => 'Informações alteradas com sucesso!'));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
            throw new Exception($e);
        }
    }

    /**
     * 
     */
    public static function inserirUnidade(Unidade $unidade) {

        try {
            new Base();


            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            if (!isset($unidade->uaaf)) {
                $unidade->uaaf = null;
            }
            if (!isset($unidade->cr)) {
                $unidade->cr = null;
            }
            if (!isset($unidade->superior)) {
                $unidade->superior = null;
            }
            if (!isset($unidade->diretoria)) {
                $unidade->diretoria = null;
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_UNIDADES(NOME,SIGLA,UAAF,CR,SUPERIOR,DIRETORIA,TIPO,UP,CODIGO,UF,EMAIL,UOP) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bindParam(1, $unidade->nome, PDO::PARAM_STR);
            $stmt->bindParam(2, $unidade->sigla, PDO::PARAM_STR);
            $stmt->bindParam(3, $unidade->uaaf, PDO::PARAM_INT);
            $stmt->bindParam(4, $unidade->cr, PDO::PARAM_INT);
            $stmt->bindParam(5, $unidade->superior, PDO::PARAM_INT);
            $stmt->bindParam(6, $unidade->diretoria, PDO::PARAM_INT);
            $stmt->bindParam(7, $unidade->tipo, PDO::PARAM_INT);
            $stmt->bindParam(8, $unidade->up, PDO::PARAM_INT);
            $stmt->bindParam(9, $unidade->codigo, PDO::PARAM_INT);
            $stmt->bindParam(10, $unidade->uf, PDO::PARAM_INT);
            $stmt->bindParam(11, $unidade->email, PDO::PARAM_STR);

            if (is_null($unidade->uop)) {
                $paramUOP = PDO::PARAM_NULL;
            } else {
                $paramUOP = PDO::PARAM_INT;
            }

            $stmt->bindParam(12, $unidade->uop, $paramUOP);
            $stmt->execute();

            $idLastIdUnidade = Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_UNIDADES_ID_SEQ');

            // é necessário gerar o $idLastIdUnidade para poder atualizar a Unidade Orgão Principal
            if (is_null($unidade->uop)) {
                self::setUnidadeOrgaoPrincipal($idLastIdUnidade, $idLastIdUnidade);
            }

            /**
             * Adiciona os recursos à nova unidade, com permissão 0
             */
            $sttm = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_PRIVILEGIOS (ID_UNIDADE, ID_RECURSO, PERMISSAO)
                                        SELECT {$idLastIdUnidade}, R.ID, 0
                                            FROM TB_RECURSOS R
                                        WHERE NOT (R.DOM_ID IS NOT NULL AND R.URL IS NOT NULL 
                                                    AND R.ID_RECURSO_DIALOG IS NULL AND R.ID_RECURSO_TIPO != 5)");
            $sttm->execute();

            /**
             * Configura como permissão positiva os privilégios comuns a todas as unidades 
             */
            $sttp = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_PRIVILEGIOS SET PERMISSAO = 1 
                WHERE ID_RECURSO IN (1,2,3,4,5,6,7,102,103,104,105,113,116,201,202,210,211,303,304,307,308,310,311,410,411,501,502,510,511,601,610,611,612,613,614,615,3101,3102,3111,3112,10303,10304,10305,10306,10307,10602,10702,10703,10801,310110,310111,310112,310113,310201,310202,310203,310204,310205,310206,311110,311111,311112,311115,311201,311202,311203,311204,311205,311206,311207,311208,31020102,31020202,31120202,31120302)
                AND ID_UNIDADE = ?");
            $sttp->bindParam(1, $idLastIdUnidade, PDO::PARAM_INT);
            $sttp->execute();

            new Log('TB_UNIDADES', $idLastIdUnidade, Zend_Auth::getInstance()->getIdentity()->ID, 'inserir');

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true', 'message' => 'Unidade cadastrada com sucesso!'));
        } catch (PDOException $e) {

            $error = 'Ocorreu um erro ao tentar salvar as informações da unidade!';

            if (strpos($e->getMessage(), 'already exists')) {
                $error = 'Esta unidade já esta cadastrada, verifique se a sigla ou nome já estão sendo utilizados por outra unidade!';
            }

            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $error));
            throw new Exception($e);
        }
    }

    /**
     * Se a unidade NÃO for do tipo Unidade Orgão Principal é necessário
     * informar qual é a Unidade Orgão Principal dela.
     *
     * @param type $id
     * @param type $idOUP
     */
    private static function setUnidadeOrgaoPrincipal($id, $idOUP) {
        $con = Controlador::getInstance()->getConnection()->connection;

        $sql = $con->prepare("UPDATE TB_UNIDADES SET UOP = {$idOUP} WHERE ID = ?");
        $sql->bindParam(1, $id, PDO::PARAM_INT);
        $sql->execute();
    }

    /**
     *
     * @param array $where
     * @param type $campo
     * @return type 
     */
    public static function listUnidades(array $where = null, $campo = false) {
        try {
            $sucesso = new stdClass();



            $campo = $campo ? $campo : '*';
            $sql = "SELECT {$campo} FROM TB_UNIDADES";

            $sql .= self::where($where);

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);
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
     */
    public static function getUnidade($unidade = false, $campo = false) {
        try {

            $campo = $campo ? $campo : '*';

            $usuario = Zend_Auth::getInstance()->getIdentity();

            if ($unidade == 'null') {
                
            }

            $unidade = is_null($unidade) ? $usuario->ID_UNIDADE : $unidade;

            if (is_null($unidade)) {
                throw new Exception("Problemas com os dados do usuário logado ao tentar obter unidade");
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT {$campo} FROM TB_UNIDADES WHERE ID = ? LIMIT 1");
            $stmt->bindParam(1, $unidade, PDO::PARAM_INT);
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
    public static function deleteUnidade($id, $status) {
        try {

            new Base();



            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_UNIDADES SET ST_ATIVO = ? WHERE ID = ?");
            $stmt->bindParam(1, $status, PDO::PARAM_INT);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_UNIDADES', $id, Zend_Auth::getInstance()->getIdentity()->ID, 'excluir :: ' . (print_r($out, true)));

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true'));
        } catch (PDOException $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * Auxilia no tratamento do where.
     * 
     * @param array $where
     * @return type 
     */
    public static function where($where) {
        $string = '';
        if (count($where) > 0) {

            $string .= " WHERE 1=1";
            foreach ($where as $k => $v) {
                $string .= " AND {$k} = '{$v}'";
            }
        }
        return $string;
    }

}