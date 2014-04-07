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
class Documento {

    public $documento;
    public $array = array();
    public $cont = 0;
    public $raiz;
    public $relacao_tramite;
    public $tramite;

    /**
     * 
     */
    public function __construct($array = null) {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $this->documento->{$key} = $value;
            }
        }
    }

    /**
     * 
     */
    public function __set($var, $value) {
        $this->documento->$var = $value;
    }

    /**
     * 
     */
    public function __get($var) {
        if (property_exists($this->documento, $var)) {
            return $this->documento->$var;
        } else {
            return null;
        }
    }

    /**
     *  Validar se digital esta disponivel para ser usado pelo usuario e setor 
     */
    public static function validarDigitalDocumento($digital) {
        try {



            /**
             * BugFix Notice
             */
            $diretoria = Controlador::getInstance()->usuario->ID_UNIDADE;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT DIGITAL FROM TB_DIGITAL WHERE DIGITAL = ? AND ID_UNIDADE = ? AND USO != '1' LIMIT 1");
            $stmt->bindParam(1, $digital, PDO::PARAM_STR);
            $stmt->bindParam(2, $diretoria, PDO::PARAM_INT);

            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                return new Output(array('success' => 'true', 'valid' => true));
            } else {
                return new Output(array('success' => 'true', 'valid' => false));
            }
        } catch (PDOException $e) {
            throw new BasePDOException($e);
        }
    }
 
    /**
     *  Verificar se o documento informado eh peca de processo 
     */
    public static function validarDocumentoPecaProcesso($digital) {
        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID FROM TB_PROCESSOS_DOCUMENTOS WHERE ID_DOCUMENTOS_CADASTRO = ? LIMIT 1");
            $stmt->bindParam(1, DaoDocumento::getDocumento($digital, 'id'), PDO::PARAM_INT);

            $stmt->execute();
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            throw new BasePDOException($e);
        }
    }

    /**
     *  Verificar se o documento informado eh subordinado a um documento principal 
     */
    public static function validarDocumentoVinculadoDocumentoPrincipal($digital) {
        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT DC.ID FROM SGDOC.TB_DOCUMENTOS_VINCULACAO V 
                                                INNER JOIN SGDOC.TB_DOCUMENTOS_CADASTRO DC ON DC.ID = V.ID_DOCUMENTO_FILHO
                                                    WHERE V.FG_ATIVO = 1 AND DC.DIGITAL = ? LIMIT 1");
            $stmt->bindParam(1, $digital, PDO::PARAM_STR);

            $stmt->execute();
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            throw new BasePDOException($e);
        }
    }

    /**
     *  Verificar se o documento informado esta na area de trabalho do usuario logado 
     */
    public static function validarDocumentoAreaDeTrabalho($digital, $unidade = false) {
        try {



            $unidade = $unidade ? $unidade : Zend_Auth::getInstance()->getIdentity()->ID_UNIDADE;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT 1 FROM TB_DOCUMENTOS_CADASTRO WHERE DIGITAL = ? AND ID_UNID_AREA_TRABALHO = ? LIMIT 1");
            $stmt->bindParam(1, $digital, PDO::PARAM_STR);
            $stmt->bindParam(2, $unidade, PDO::PARAM_INT);
            $stmt->execute();

            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            throw new BasePDOException($e);
        }
    }

    /**
     *  Pegar a quantidade de imagens do documento informado
     */
    public static function getQuantidadeImagemDocumento($digital, $status = false/* Implementar */) {
        try {
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT COUNT(DIGITAL) as TOTAL 
                FROM TB_DOCUMENTOS_IMAGEM WHERE DIGITAL = ? AND FLG_PUBLICO != 2 AND ST_ATIVO = 1
            "); /* FG_PUBLICO :: 0=Confedencial 1=Public 2=Excluida */
            $stmt->bindParam(1, $digital, PDO::PARAM_STR);
            $stmt->execute();
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                return $out[0]['TOTAL'];
            }

            return false;
        } catch (PDOException $e) {
            throw new BasePDOException($e);
        }
    }

}