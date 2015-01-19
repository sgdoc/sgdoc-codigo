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
 * @author Bruno Neves <brunonm@gmail.com>
 */
class GridQuery extends Base {
    /**
     * @todo Melhorar este metodo usando os recurso do PDO.
     * @todo Observacao : as chamas deste metodo nao estao validando o usuario logado! Corrigir urgente...
     */

    /**
     * Retorna o JSON gerado para o component DataTables do JqueryUi
     * @param <Array>	 $_GET		requisição gerada pelo plugin
     * @param <Array>	 $aColumns	colunas que serão lidas e enviadas de volta para o DataTables
     * @param <String>	 $sIndexColumn	coluna indexada do banco
     * @param <String>	 $sTable		nome da tabela com os dados
     * @param <Resource> $conexao	conexão do mysql
     * @param <String>	 $sExtraQuery 	fragmento de sql para complementar a busca
      * @param <Array>   $auxView opcional (apenas se usa quando a tabela for especificamente uma query)
     */
    public static function getGrid($_GET, $aColumns, $sIndexColumn, $sTable, $conexao, $sExtraQuery = false, array $auxView = null) {
        /**
         * @todo Remover quando for refatorar esta classe...
         */
        $conexao = Config::factory()
                        ->buildDBConfig()->buildAppConstants()
                        ->buildAppDefines()->buildEnvironment()->getConnection();

        /* Definir Limits */
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " .
                    mysql_real_escape_string($_GET['iDisplayLength']);
        }
        /* Definir Ordenacao */
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
					    " . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
                }
            }
            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }

        /* Denifir Argumentos da Busca */
        $sWhere = "";
        if ($_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                /**
                 * @todo problema critico de desempenho
                 */
                //$sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                $sWhere .= $aColumns[$i] . " LIKE '" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, ")", -3);
            if ($sExtraQuery) {
                $sWhere .= " AND ({$sExtraQuery})";
            }
        } else {
            if ($sExtraQuery) {
                $sWhere = "WHERE {$sExtraQuery}";
            }
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                /**
                 * @todo problema critico de desempenho
                 */
                //$sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
                $sWhere .= $aColumns[$i] . " LIKE '" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
            }
        }

        if (isset($auxView)) {
            $sTable = sprintf($sTable, implode(',', $auxView));
        }

        $sQuery = "SELECT DISTINCT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . " 
                     FROM ({$sTable}) AS X {$sWhere} {$sOrder} {$sLimit}";

        $rResult = mysql_query($sQuery, $conexao) or die(mysql_error());

        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS()";

        $rResultFilterTotal = mysql_query($sQuery, $conexao) or die(mysql_error());
        $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
        $iFilteredTotal = $aResultFilterTotal[0];

        $aResultTotal = $aResultFilterTotal;
        $iTotal = $iFilteredTotal;

        /*
         * Output
         */
        $sOutput = '{';
        $sOutput .= '"sEcho": ' . intval($_GET['sEcho']) . ', ';
        $sOutput .= '"iTotalRecords": ' . $iTotal . ', ';
        $sOutput .= '"iTotalDisplayRecords": ' . $iFilteredTotal . ', ';
        $sOutput .= '"aaData": [ ';
        while ($aRow = mysql_fetch_array($rResult)) {
            $sOutput .= "[";
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "version") {
                    /* Special output formatting for 'version' */
                    $sOutput .= ( $aRow[$aColumns[$i]] == "0") ?
                            '"-",' :
                            '"' . str_replace('"', '\"', $aRow[$aColumns[$i]]) . '",';
                } else if ($aColumns[$i] == "ASSUNTO" || $aColumns[$i] == "ORIGEM" || $aColumns[$i] == "TEXTO_DESPACHO") {
                    /* special output formatting for 'assunto' */
                    $ary = "ASCII, UTF-8, CP1252, ISO-8859-1";
                    $enc = mb_detect_encoding($aRow[$aColumns[$i]], $ary);
                    if ($enc == 'UTF-8') {
                        $sOutput .= '"' . str_replace('"', '\"', utf8_decode(trim($aRow[$aColumns[$i]]))) . '",';
                    } else {
                        if (preg_match('/[\x7F-\x9F]/', $aRow[$aColumns[$i]])) {
                            $sOutput .= '"' . str_replace('"', '\"', utf8_decode(trim($aRow[$aColumns[$i]]))) . '",';
                        } else {
                            $sOutput .= '"' . str_replace('"', '\"', trim($aRow[$aColumns[$i]])) . '",';
                        }
                    }
                } else if ($aColumns[$i] != ' ') {
                    /* General output */
                    $sOutput .= '"' . str_replace('"', '\"', trim($aRow[$aColumns[$i]])) . '",';
                }
            }

            $sOutput = substr_replace($sOutput, "", -1);
            $sOutput .= "],";
        }
        $sOutput = substr_replace($sOutput, "", -1);
        $sOutput .= '] }';
        $sOutput = preg_replace('/\s\s+/', '', $sOutput);
        $sOutput = str_replace(array("\t", "\n", "\r"), ' ', $sOutput);

        return $sOutput;
    }
}