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
class DaoUsuario {

    /**
     * Autentica usuário
     * @deprecated
     * @todo Substituir pelo ZendAuth...
     */
    public static function autenticar($usuario) {

        $usuario = Zend_Auth::getInstance()->getIdentity();

        if (!empty($usuario)) {
            return new Usuario(array_change_key_case((array) $usuario, CASE_LOWER));
        }

        throw new Exception('Acesso Negado!');

        return false;
    }

    /**
     * Carrega informações de um usuário
     * @param type $usuario
     * @param type $campo
     * @param type $returnObject
     * @return \Usuario|null
     * @throws Exception
     */
    public static function getUsuario($usuario = false, $campo = false, $returnObject = false) {
        try {
            $campo = $campo ? strtolower($campo) : '*';
            $condicao = is_string($usuario) ? 'USUARIO' : 'ID';
            $usuario = $usuario ? $usuario : Zend_Auth::getInstance()->getIdentity()->ID;

            /**
             * TODO: modificar estrutura de usuario.ID_UNIDADE
             * $campo pode ser ID_UNIDADE
             * FORAM RETIRADOS NAS CHAMADAS MAS OBSERVAR COMPORTAMENTO
             */
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT $campo FROM TB_USUARIOS WHERE $condicao = ? LIMIT 1
            ");
            $stmt->bindParam(1, $usuario, PDO::PARAM_STR);
            $stmt->execute();

            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                $out = array_change_key_case($out, CASE_LOWER);
                if ($campo === '*') {
                    if ($returnObject) {
                        $usuario = new Usuario();
                        $usuario->populate($out);
                        return $usuario;
                    }
                    return $out;
                } else if (strpos($campo, ',') !== FALSE) {
                    // achou virgula, então é o código do recruta
                    return $out;
                } else {
                    return $out[$campo];
                }
            }

            return null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Retorna ID de um usuário com o CPF informado
     * @param type $cpf
     * @return int
     * @throws Exception
     */
    public static function getUsuarioIdByCpf($cpf) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT id FROM TB_USUARIOS WHERE CPF = ? LIMIT 1
            ");
            $stmt->bindParam(1, $cpf, PDO::PARAM_STR);
            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                return $out['ID'];
            }
            return 0;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * Retorna todos os usuários com a sigla de uma de suas gerências
     * @depracated
     * @return PDOStatement
     * @throws Exception
     */
    public static function fetchAll() {
        try {
            /**
             * TODO: modificar estrutura de usuario.ID_UNIDADE
             * ESTA FUNÇÃO NÃO ESTÁ SENDO CHAMADA EM OUTRA PARTE DO SISTEMA,
             * PORÉM FOI REALIZADO AJUSTE NO SQL PARA RETORNAR SOMENTE UMA DAS UNIDADES
             */
            $sql = "
                SELECT 
                    U.ID, U.USUARIO, U.NOME, U.TELEFONE, U.EMAIL, UN.SIGLA AS DIRETORIA, U.SKYPE, U.CPF, U.STATUS
                FROM TB_USUARIOS u
                    INNER JOIN TB_USUARIOS_UNIDADES UU ON UU.ID_USUARIO = u.ID
                    INNER JOIN TB_UNIDADES UN ON UN.ID = UU.ID_UNIDADE
                GROUP BY U.ID, UN.SIGLA
            ";
            return Controlador::getInstance()->getConnection()->connection->query($sql);
        } catch (Exception $e) {
            throw new $e;
        }
    }

    /**
     * Salva informações de um Usuário
     * @deprecated
     * @param Usuario $usuario
     * @return \Output
     * @throws Exception
     */
    public static function salvar(Usuario $usuario) {
        try {

            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            $usuarioId = $usuario->id;

            if ($usuarioId) {
                //EDITAR
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                    UPDATE TB_USUARIOS 
                    SET USUARIO = ?, NOME = ?, TELEFONE = ?, EMAIL = ?, SKYPE = ?, CPF = ? 
                    WHERE ID = ?");
                $stmt->bindParam(7, $usuario->id, PDO::PARAM_INT);

                //CRIA LOG COM AS ALTERAÇÕES
                new Log('USUARIOS', $usuario->id, Zend_Auth::getInstance()->getIdentity()->ID, 'salvar :: ' . (print_r(self::getUsuario((int) $usuario->id), true)));
            } else {
                //INSERIR
                //VERIFICA SE JÁ NÃO EXISTE USUÁRIO CADASTRADO COM O MESMO LOGIN
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                    SELECT ID FROM TB_USUARIOS WHERE USUARIO = ? LIMIT 1
                ");
                $stmt->bindParam(1, $usuario->usuario, PDO::PARAM_STR);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    throw new Exception('Já existe usuário cadastrado com esse login.');
                }

                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                    INSERT INTO TB_USUARIOS (USUARIO, NOME, TELEFONE, EMAIL, SKYPE, CPF) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
            }

            $stmt->bindParam(1, $usuario->usuario, PDO::PARAM_STR);
            $stmt->bindParam(2, $usuario->nome, PDO::PARAM_STR);
            $stmt->bindParam(3, $usuario->telefone, PDO::PARAM_STR);
            $stmt->bindParam(4, $usuario->email, PDO::PARAM_STR);
            $stmt->bindParam(5, $usuario->skype, PDO::PARAM_STR);
            $stmt->bindParam(6, $usuario->cpf, PDO::PARAM_STR);

            $stmt->execute();

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true', 'message' => 'Operação realizada com sucesso!'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    public static function alterarStatus(Usuario $usuario) {
        try {
            new Base();

            Controlador::getInstance()->getConnection()->connection->beginTransaction();

            /**
             * BugFix Notice
             */
            $id_usuario = $usuario->id;
            $status = $usuario->status;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                UPDATE TB_USUARIOS SET STATUS = ? WHERE ID = ?");
            $stmt->bindParam(1, $status, PDO::PARAM_INT);
            $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            //CRIA LOG COM AS ALTERAÇÕES
            new Log('USUARIOS', $usuario->id, Zend_Auth::getInstance()->getIdentity()->ID, 'alterar-status :: ' . (print_r(self::getUsuario((int) $usuario->id), true)));

            /**
             * BugFix
             * Se o status for para ativo entao inserir um novo registro no controle de acesso 
             * para evitar a inativacao por falta de acesso...
             */
            if ($status == 1) {
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                    INSERT INTO TB_CONTROLE_ACESSO (ID_USUARIO, DT_ACESSO, IP_ACESSO) 
                    VALUES (?, CLOCK_TIMESTAMP(), '0.0.0.0')
                ");
                $stmt->bindParam(1, $id_usuario, PDO::PARAM_INT);
                $stmt->execute();
            }

            Controlador::getInstance()->getConnection()->connection->commit();
            return new Output(array('success' => 'true', 'Operação realizada com sucesso!'));
        } catch (Exception $e) {
            Controlador::getInstance()->getConnection()->connection->rollback();
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

}
