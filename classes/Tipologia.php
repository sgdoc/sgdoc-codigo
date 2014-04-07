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

class Tipologia {

    /**
     * @return array
     */
    public static function getTipologias() {
        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID, TIPOLOGIA FROM TB_TIPOLOGIAS");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     *  Inserir um novo contador anual para a tipologia informada
     */
    public static function inserirContador($tipologia) {
        try {

            $ano = date('Y');

            $sttm = Controlador::getInstance()->getConnection()->connection->prepare("SELECT MAX(VALOR) AS VALOR FROM TB_CONTROLE_NUMERACAO WHERE ID_UNIDADES = ? AND ID_TIPOLOGIAS = ? AND ANO = ?");
            $sttm->bindParam(1, Controlador::getInstance()->usuario->ID_UNIDADE, PDO::PARAM_INT);
            $sttm->bindParam(2, $tipologia, PDO::PARAM_INT);
            $sttm->bindValue(3, $ano, PDO::PARAM_INT);
            $sttm->execute();
            $out = $sttm->fetch(PDO::FETCH_ASSOC);

            if (is_null($out['VALOR'])) {

                // não existe registro para esta tipologia, para este ano
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("INSERT INTO TB_CONTROLE_NUMERACAO (ID_UNIDADES,ID_TIPOLOGIAS,ANO,VALOR) VALUES (?,?,?,0)");
                $stmt->bindParam(1, Controlador::getInstance()->usuario->ID_UNIDADE, PDO::PARAM_INT);
                $stmt->bindParam(2, $tipologia, PDO::PARAM_INT);
                $stmt->bindValue(3, $ano, PDO::PARAM_INT);
                $stmt->execute();

                $out['VALOR'] = 0;
            }

            return $out['VALOR'];
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * @return string
     * @param string $tipologia
     */
    public static function atualizarContador($tipologia) {
        try {

            $ano = date('Y');

            $sttz = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID FROM TB_TIPOLOGIAS WHERE TIPOLOGIA = ? LIMIT 1");
            $sttz->bindParam(1, $tipologia, PDO::PARAM_STR);
            $sttz->execute();

            $tipologia = current($sttz->fetch(PDO::FETCH_ASSOC));

            $valor = Tipologia::inserirContador($tipologia) + 1;

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_CONTROLE_NUMERACAO SET VALOR = {$valor} WHERE ID_UNIDADES = ? AND ID_TIPOLOGIAS = ? AND ANO = ?");
            $stmt->bindParam(1, Controlador::getInstance()->usuario->ID_UNIDADE, PDO::PARAM_INT);
            $stmt->bindParam(2, $tipologia, PDO::PARAM_INT);
            $stmt->bindParam(3, $ano, PDO::PARAM_INT);
            $stmt->execute();

            $sttm = Controlador::getInstance()->getConnection()->connection->prepare("SELECT VALOR FROM TB_CONTROLE_NUMERACAO WHERE ID_UNIDADES = ? AND ID_TIPOLOGIAS = ? AND ANO = ? LIMIT 1");
            $sttm->bindParam(1, Controlador::getInstance()->usuario->ID_UNIDADE, PDO::PARAM_INT);
            $sttm->bindParam(2, $tipologia, PDO::PARAM_INT);
            $sttm->bindParam(3, $ano, PDO::PARAM_INT);

            $sttm->execute();
            $out = $sttm->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                return "{$out['VALOR']}/{$ano}";
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     *  Pegar tipologia de documento especifica
     */
    public static function getTipologia($tipologia = false, $campo = false) {
        try {

            $campo = $campo ? $campo : '*';
            $condicao = is_int($tipologia) ? 'ID' : 'TIPOLOGIA';

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT $campo FROM TB_TIPOLOGIAS WHERE $condicao = ? LIMIT 1");
            $stmt->bindParam(1, $tipologia, PDO::PARAM_STR);
            $stmt->execute();
            $out = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($out)) {
                if ($campo === '*') {
                    return $out;
                }
                return $out[$campo];
            }

            return false;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

}