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

class CaixasHistoricos {

    /**
     * @return PDOStatement
     * @param integer $id_caixa
     * @param integer $id_documento
     * @param integer $id_usuario
     * @param string $operacao
     */
    public function __construct($id_caixa, $id_documento, $id_usuario, $operacao) {
        try {
            $data_cadastro = Zend_Date::now()->get(Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
            $id_unidade_usuario = Controlador::getInstance()->usuario->ID_UNIDADE;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_CAIXAS_HISTORICOS (ID_CAIXA,ID_DOCUMENTO,ID_USUARIO,DS_ACAO,DT_CADASTRO,ID_UNIDADE_USUARIO) VALUES (?,?,?,?,?,?)");
            $stmt->bindParam(1, $id_caixa, PDO::PARAM_INT);
            $stmt->bindParam(2, $id_documento, PDO::PARAM_INT);
            $stmt->bindParam(3, $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(4, $operacao, PDO::PARAM_STR);
            $stmt->bindParam(5, $data_cadastro, PDO::PARAM_STR);
            $stmt->bindParam(6, $id_unidade_usuario, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

}