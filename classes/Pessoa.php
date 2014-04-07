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

class Pessoa {

    /**
     * 
     */
    public static function novoInteressado($interessado, $cpf = 'Em Branco', $homologado = false) {
        try {
            /* Novo Interessado */

            $id_unidade_usuario = Controlador::getInstance()->usuario->ID_UNIDADE;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_PROCESSOS_INTERESSADOS (INTERESSADO,CNPJ_CPF,USUARIO,HOMOLOGADO,ID_UNIDADE_USUARIO) VALUES (?,?,?,?,?)");
            $stmt->bindParam(1, $interessado, PDO::PARAM_STR);
            $stmt->bindParam(2, $cpf, PDO::PARAM_STR);
            $stmt->bindParam(3, Zend_Auth::getInstance()->getIdentity()->ID, PDO::PARAM_INT);
            $stmt->bindParam(4, $homologado, PDO::PARAM_INT);
            $stmt->bindParam(5, $id_unidade_usuario, PDO::PARAM_INT);
            $stmt->execute();

            $out = array('success' => 'true', 'interessado' => $interessado, 'id' => Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_PROCESSOS_INTERESSADOS_ID_SEQ'));

            if (!empty($out)) {
                return $out;
            }

            return null;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * 
     */
    public static function novaPessoa($origem, $tipo, $cpf = 'Em Branco', $homologado = false) {
        try {
            /* Novo Pessoa */


            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_PESSOA (NM_PESSOA,TP_PESSOA,HOMOLOGADO) VALUES (?,?,0)");
            $stmt->bindParam(1, $origem, PDO::PARAM_STR);
            $stmt->bindParam(2, $tipo, PDO::PARAM_STR);
            $stmt->execute();
            $out = array('success' => 'true', 'pessoa' => $origem, 'id' => Controlador::getInstance()->getConnection()->connection->lastInsertId('TB_PESSOA_ID_PESSOA_SEQ'));

            if (!empty($out)) {
                return $out;
            }

            return null;
        } catch (PDOException $e) {
            throw new BasePDOException($e);
        }
    }

}