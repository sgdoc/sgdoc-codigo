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
class DaoComentario {

    /**
     * @return Output
     * @param Comentario $comentario
     */
    public static function inserirComentarioDocumento(Comentario $comentario) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_COMENTARIOS_DOCUMENTOS (DIGITAL, ID_USUARIO, USUARIO, 
            DT_CADASTRO, TEXTO_COMENTARIO, ID_UNIDADE, DIRETORIA)
            VALUES (?,?,?,CLOCK_TIMESTAMP(),?,?,?)");
            $stmt->bindParam(1, $comentario->digital, PDO::PARAM_STR);
            $stmt->bindParam(2, $comentario->id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(3, $comentario->usuario, PDO::PARAM_STR);
            $stmt->bindParam(4, $comentario->texto, PDO::PARAM_STR);
            $stmt->bindParam(5, $comentario->id_unidade, PDO::PARAM_INT);
            $stmt->bindParam(6, $comentario->diretoria, PDO::PARAM_STR);
            $stmt->execute();
            return new Output(array('success' => 'true'));
        } catch (Exception $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * @return Output
     * @param Comentario $comentario
     */
    public static function inserirComentarioProcesso(Comentario $comentario) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_COMENTARIOS_PROCESSOS (NUMERO_PROCESSO, ID_USUARIO, USUARIO,
            DT_CADASTRO, TEXTO_COMENTARIO, ID_UNIDADE, DIRETORIA)
            VALUES (?,?,?,CLOCK_TIMESTAMP(),?,?,?)");
            $stmt->bindParam(1, $comentario->numero_processo, PDO::PARAM_STR);
            $stmt->bindParam(2, $comentario->id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(3, $comentario->usuario, PDO::PARAM_STR);
            $stmt->bindParam(4, $comentario->texto, PDO::PARAM_STR);
            $stmt->bindParam(5, $comentario->id_unidade, PDO::PARAM_INT);
            $stmt->bindParam(6, $comentario->diretoria, PDO::PARAM_STR);
            $stmt->execute();
            return new Output(array('success' => 'true'));
        } catch (Exception $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * @return Output
     * @param integer $id
     */
    public static function excluirComentarioDocumento($id) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_COMENTARIOS_DOCUMENTOS SET ST_ATIVO = 0 WHERE ID = ?");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_COMENTARIOS_DOCUMENTOS', $id, Zend_Auth::getInstance()->getIdentity()->ID, 'excluir');

            return new Output(array('success' => 'true', 'message' => 'Comentário removido com sucesso!'));
        } catch (Exception $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * @return Output
     * @param integer $id
     */
    public static function excluirComentarioProcesso($id) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_COMENTARIOS_PROCESSOS SET ST_ATIVO = 0 WHERE ID = ?");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();

            new Log('COMENTARIOS_PROCESSOS', $id, Zend_Auth::getInstance()->getIdentity()->ID, 'excluir');

            return new Output(array('success' => 'true', 'message' => 'Comentário removido com sucesso!'));
        } catch (Exception $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * @return Output
     * @param integer $id
     */
    public static function carregarComentarioDocumento($id) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT TEXTO_COMENTARIO AS TEXTO FROM TB_COMENTARIOS_DOCUMENTOS WHERE ID = ? LIMIT 1");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Output(array('success' => 'true', 'texto' => $out['TEXTO']));
        } catch (Exception $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * @return Output
     * @param integer $id
     */
    public static function carregarComentarioProcesso($id) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT TEXTO_COMENTARIO AS TEXTO FROM TB_COMENTARIOS_PROCESSOS WHERE ID = ? LIMIT 1");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Output(array('success' => 'true', 'texto' => $out['TEXTO']));
        } catch (Exception $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * @return Output
     * @param integer $id
     * @param string $texto
     */
    public static function alterarComentarioDocumento($id, $texto) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_COMENTARIOS_DOCUMENTOS SET TEXTO_COMENTARIO = ? WHERE ID = ?");
            $stmt->bindParam(1, $texto, PDO::PARAM_INT);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_COMENTARIOS_DOCUMENTOS', $id, Zend_Auth::getInstance()->getIdentity()->ID, 'editar');

            return new Output(array('success' => 'true', 'message' => 'Comentário editado com sucesso!'));
        } catch (Exception $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * @return Output
     * @param integer $id
     * @param string $texto
     */
    public static function alterarComentarioProcesso($id, $texto) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_COMENTARIOS_PROCESSOS SET TEXTO_COMENTARIO = ? WHERE ID = ?");
            $stmt->bindParam(1, $texto, PDO::PARAM_INT);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->execute();

            new Log('COMENTARIOS_PROCESSOS', $id, Zend_Auth::getInstance()->getIdentity()->ID, 'editar');

            return new Output(array('success' => 'true', 'message' => 'Comentário editado com sucesso!'));
        } catch (Exception $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

}