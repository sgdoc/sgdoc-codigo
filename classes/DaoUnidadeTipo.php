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
 * @author Carlos Eduardo
 */
class DaoUnidadeTipo {

    /**
     * @param integer
     * @return Object 
     */
    public static function listUnidadesTipo($idOrgaoSuperior = NULL) {
        $sucesso = new stdClass();

        
        try {

            $sql = "SELECT ID, TIPO, TIPO AS NOME FROM TB_UNIDADES_TIPO ORDER BY TIPO ASC";

            if (!is_null($idOrgaoSuperior)) {
                $sql = 'SELECT DISTINCT (T.ID), T.TIPO, T.TIPO AS NOME FROM TB_UNIDADES_TIPO T
                            INNER JOIN TB_UNIDADES U ON U.TIPO = T.ID 
                        WHERE U.UOP = ? ORDER BY T.TIPO ASC';
            }

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($sql);

            if (!is_null($idOrgaoSuperior)) {
                $stmt->bindParam(1, $idOrgaoSuperior);
            }

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

}