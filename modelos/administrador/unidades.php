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

try {
    switch ($_GET['tipo']) {
        case 'administrador-mudar-unidade':
            /**
             * Deve realizar a consulta das Unidades que contém os parâmetros listados.
             * A pesquisa é realizada desconsiderando Case (case insensitive),
             * desconsiderando Acentuação
             * Caso a consulta tenha somente um parâmetro, deve considerar SIGLA e NOME com OR
             * Caso possua mais de um parametro, o sistema deve considerar somente o NOME com AND
             * O resultado da pesquisa deve ser limitado a 100 registros
             */
            $strSearchWS = preg_replace('/\s\s+/', ' ', $_GET['query']);

            $arrWords = explode(' ', $strSearchWS);

            $strConectivo = "";

            $arrClausule = array();
            if (count($arrWords) == 1) {//Se consulta possuir somente 1 parametro, pesquisa em SIGLA e NOME
                //Define conectivo
                $strConectivo = "OR";

                $arrClausule[] = "fn_remove_acentuacao(SIGLA) ILIKE fn_remove_acentuacao(?)";
                $arrClausule[] = "fn_remove_acentuacao(NOME) ILIKE fn_remove_acentuacao(?)";
                $arrWords[0] = "%{$arrWords[0]}%";
                $arrWords[1] = "%{$arrWords[0]}%";
            } else {//Pesquisa somente em NOME
                $strConectivo = "AND";

                $i = 0;
                foreach ($arrWords as $word) {
                    $arrClausule[] = "fn_remove_acentuacao(NOME) ILIKE fn_remove_acentuacao(?)";
                    $arrWords[$i] = "%{$word}%";
                    $i++;
                }
            }

            //Create AND Clausule with ILIKE
            $strAND = sprintf(" AND (%s) ", implode(" {$strConectivo} ", $arrClausule));

            $strSQL = "
                SELECT ID, SIGLA, NOME 
                FROM TB_UNIDADES 
                WHERE 
                    (ID != UOP) 
                    AND ST_ATIVO = '1'
                    {$strAND}
                ORDER BY NOME
                LIMIT 100
            ";

            //Get Statement Object
            $stmt = Controlador::getInstance()->getConnection()->connection->prepare($strSQL);

            //Bind Statement
            $i = 0;
            while ($i < count($arrWords)) {
                $stmt->bindParam($i + 1, $arrWords[$i], PDO::PARAM_STR);
                $i++;
            }
            break;
    }

    $stmt->execute();

    $out = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out = array_change_key_case(($out), CASE_LOWER);

    foreach ($out as $key => $value) {
        print("{$value['NOME']} - {$value['SIGLA']}|{$value['ID']}\n");
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}