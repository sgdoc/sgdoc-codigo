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

class Suporte {

    /**
     * @return integer
     * @param integer $usuario
     */
    public static function countChamadoQualificar($usuario) {

        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT COUNT(*) AS count FROM TB_ATENDIMENTO WHERE ID_USUARIO = ? AND ST_STATUS = 'Pedido Finalizado' AND NU_NOTA IS NULL");
            $stmt->bindParam(1, $usuario, PDO::PARAM_INT);
            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            return $out['count'];
        } catch (BasePDOException $e) {
            return array('success' => 'false', 'error' => $e->getMessage());
        }
    }

    /**
     * @return string
     * @param string $assunto
     * @param string $descricao
     */
    public static function abrirChamado($assunto, $descricao) {
        try {

            $id_usuario = Zend_Auth::getInstance()->getIdentity()->ID;
            $id_unidade = Controlador::getInstance()->usuario->ID_UNIDADE;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_ATENDIMENTO (ID_USUARIO,TX_ASSUNTO,TX_DESCRICAO,CD_PROTOCOLO,DT_ABERTURA,ID_UNIDADE_USUARIO) VALUES (?,?,?,?,CLOCK_TIMESTAMP(),?)");
            $stmt->bindParam(1, $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(2, $assunto, PDO::PARAM_STR);
            $stmt->bindParam(3, $descricao, PDO::PARAM_STR);
            $stmt->bindParam(4, substr(md5(microtime()), 0, 6), PDO::PARAM_STR);
            $stmt->bindParam(5, $id_unidade, PDO::PARAM_INT);

            $stmt->execute();

            return array('success' => 'true', 'message' => 'Chamado aberto com sucesso!');
        } catch (BasePDOException $e) {
            return array('success' => 'false', 'error' => $e->getMessage());
        }
    }

    /**
     * @return string
     * @param integer $id
     * @param string $comentario
     * @param integer $atendente
     */
    public static function encaminharDemanda($id, $comentario, $atendente) {

        try {

            $id_usuario = Zend_Auth::getInstance()->getIdentity()->ID;
            $id_unidade_triagem = Controlador::getInstance()->usuario->ID_UNIDADE;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_ATENDIMENTO SET TX_COMENTARIO = ?, ID_ATENDENTE = ?, ID_TRIAGEM = ?, ST_STATUS = 'Aguardando Atendimento', DT_TRIAGEM = CLOCK_TIMESTAMP(), ID_UNIDADE_TRIAGEM = ? WHERE ID = ? AND ST_STATUS = 'Triagem'");
            $stmt->bindParam(1, $comentario, PDO::PARAM_STR);
            $stmt->bindParam(2, $atendente, PDO::PARAM_INT);
            $stmt->bindParam(3, $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(4, $id_unidade_triagem, PDO::PARAM_INT);
            $stmt->bindParam(5, $id, PDO::PARAM_INT);

            $stmt->execute();

            return array('success' => 'true', 'message' => 'Demanda(s) encaminhada(s) com sucesso!');
        } catch (BasePDOException $e) {
            return array('success' => 'false', 'error' => $e->getMessage());
        }
    }

    /**
     * @return string
     * @param integer $id
     * @param string $comentario
     */
    public static function finalizarDemanda($id, $comentario) {

        try {

            $id_usuario = Zend_Auth::getInstance()->getIdentity()->ID;
            $id_unidade_atendente = Controlador::getInstance()->usuario->ID_UNIDADE;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_ATENDIMENTO SET TX_COMENTARIO = ?, ID_ATENDENTE = ?, ST_STATUS = 'Pedido Finalizado', DT_FINALIZACAO = CLOCK_TIMESTAMP(), ID_UNIDADE_ATENDENTE = ? WHERE ID = ?");
            $stmt->bindParam(1, $comentario, PDO::PARAM_STR);
            $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(3, $id_unidade_atendente, PDO::PARAM_INT);
            $stmt->bindParam(4, $id, PDO::PARAM_INT);

            $stmt->execute();

            return array('success' => 'true', 'message' => 'Demanda finalizada com sucesso!');
        } catch (BasePDOException $e) {
            return array('success' => 'false', 'error' => $e->getMessage());
        }
    }

    /**
     * @return string
     * @param integer $id
     * @param string $comentario
     */
    public static function devolverDemanda($id, $comentario) {

        try {

            $id_usuario = Zend_Auth::getInstance()->getIdentity()->ID;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_ATENDIMENTO SET TX_COMENTARIO = ?, ID_ATENDENTE = ?, ST_STATUS = 'Triagem' WHERE ID = ? AND ST_STATUS = 'Aguardando Atendimento'");
            $stmt->bindParam(1, $comentario, PDO::PARAM_STR);
            $stmt->bindParam(2, $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(3, $id, PDO::PARAM_INT);

            $stmt->execute();

            return array('success' => 'true', 'message' => 'Demanda devolvida com sucesso!');
        } catch (BasePDOException $e) {
            return array('success' => 'false', 'error' => $e->getMessage());
        }
    }

    /**
     * @return string
     * @param integer $id
     * @param integer $nota
     */
    public static function qualificarDemanda($id, $nota) {

        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_ATENDIMENTO SET NU_NOTA = ? WHERE ID = ?");
            $stmt->bindParam(1, $nota, PDO::PARAM_STR);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);

            $stmt->execute();

            return array('success' => 'true', 'message' => 'Chamado qualificado com sucesso!');
        } catch (BasePDOException $e) {
            return array('success' => 'false', 'error' => $e->getMessage());
        }
    }

}