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
class DaoDespacho {

    /**
     * @return Output
     * @param Despacho $despacho
     */ public static function inserirDespachoDocumento(Despacho $despacho) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_DESPACHOS_DOCUMENTOS (DIGITAL, DT_CADASTRO, ID_USUARIO,
                USUARIO, DT_DESPACHO, ID_UNIDADE, DIRETORIA, ASSINATURA_DESPACHO, TEXTO_DESPACHO, COMPLEMENTO)
            VALUES (?,CLOCK_TIMESTAMP(),?,?,?,?,?,?,?,?)");
            $stmt->bindParam(1, $despacho->digital, PDO::PARAM_STR);
            $stmt->bindParam(2, $despacho->id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(3, $despacho->usuario, PDO::PARAM_STR);
            $stmt->bindParam(4, Util::formatDate($despacho->data_despacho), PDO::PARAM_STR);
            $stmt->bindParam(5, $despacho->id_unidade, PDO::PARAM_INT);
            $stmt->bindParam(6, $despacho->diretoria, PDO::PARAM_STR);
            $stmt->bindParam(7, $despacho->assinatura, PDO::PARAM_STR);
            $stmt->bindParam(8, $despacho->texto, PDO::PARAM_STR);
            $stmt->bindParam(9, $despacho->complemento, PDO::PARAM_STR);
            $stmt->execute();
            return new Output(array('success' => 'true'));
        } catch (Exception $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * @return Output
     * @param Despacho $despacho
     */
    public static function inserirDespachoProcesso(Despacho $despacho) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_DESPACHOS_PROCESSOS (NUMERO_PROCESSO, DT_CADASTRO, ID_USUARIO,
                USUARIO, DT_DESPACHO, ID_UNIDADE, DIRETORIA, ASSINATURA_DESPACHO, TEXTO_DESPACHO, COMPLEMENTO)
            VALUES (?,CLOCK_TIMESTAMP(),?,?,?,?,?,?,?,?)");
            $stmt->bindParam(1, $despacho->numero_processo, PDO::PARAM_STR);
            $stmt->bindParam(2, $despacho->id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(3, $despacho->usuario, PDO::PARAM_STR);
            $stmt->bindParam(4, Util::formatDate($despacho->data_despacho), PDO::PARAM_STR);
            $stmt->bindParam(5, $despacho->id_unidade, PDO::PARAM_INT);
            $stmt->bindParam(6, $despacho->diretoria, PDO::PARAM_STR);
            $stmt->bindParam(7, $despacho->assinatura, PDO::PARAM_STR);
            $stmt->bindParam(8, $despacho->texto, PDO::PARAM_STR);
            $stmt->bindParam(9, $despacho->complemento, PDO::PARAM_STR);
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
    public static function excluirDespachoDocumento($id) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DESPACHOS_DOCUMENTOS SET ST_ATIVO = 0 WHERE ID = ?");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_DESPACHOS_DOCUMENTOS', $id, Zend_Auth::getInstance()->getIdentity()->ID, 'excluir');

            return new Output(array('success' => 'true', 'message' => 'Despacho removido com sucesso!'));
        } catch (Exception $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * @return Output
     * @param integer $id
     */
    public static function excluirDespachoProcesso($id) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DESPACHOS_PROCESSOS SET ST_ATIVO = 0 WHERE ID = ?");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_DESPACHOS_PROCESSOS', $id, Zend_Auth::getInstance()->getIdentity()->ID, 'excluir');

            return new Output(array('success' => 'true', 'message' => 'Despacho removido com sucesso!'));
        } catch (Exception $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * @return Output
     * @param integer $id
     */
    public static function carregarDespachoDocumento($id) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT TEXTO_DESPACHO AS TEXTO FROM TB_DESPACHOS_DOCUMENTOS WHERE ID = ? LIMIT 1");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Output(array('success' => 'true', 'texto' => ($out['TEXTO'])));
        } catch (Exception $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * @return Output
     * @param integer $id
     */
    public static function carregarDespachoProcesso($id) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT TEXTO_DESPACHO AS TEXTO FROM TB_DESPACHOS_PROCESSOS WHERE ID = ? LIMIT 1");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Output(array('success' => 'true', 'texto' => ($out['TEXTO'])));
        } catch (Exception $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * @return Output
     * @param integer $id
     * @param string $texto
     */
    public static function alterarDespachoDocumento($id, $texto) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DESPACHOS_DOCUMENTOS SET TEXTO_DESPACHO = ? WHERE ID = ?");
            $stmt->bindParam(1, $texto, PDO::PARAM_INT);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_DESPACHOS_DOCUMENTOS', $id, Zend_Auth::getInstance()->getIdentity()->ID, 'editar');

            return new Output(array('success' => 'true', 'message' => 'Despacho editado com sucesso!'));
        } catch (Exception $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

    /**
     * @return Output
     * @param integer $id
     * @param string $texto
     */
    public static function alterarDespachoProcesso($id, $texto) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_DESPACHOS_PROCESSOS SET TEXTO_DESPACHO = ? WHERE ID = ?");
            $stmt->bindParam(1, $texto, PDO::PARAM_INT);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->execute();

            new Log('TB_DESPACHOS_PROCESSOS', $id, Zend_Auth::getInstance()->getIdentity()->ID, 'editar');

            return new Output(array('success' => 'true', 'message' => 'Despacho editado com sucesso!'));
        } catch (Exception $e) {
            return new Output(array('success' => 'false', 'error' => $e->getMessage()));
        }
    }

}