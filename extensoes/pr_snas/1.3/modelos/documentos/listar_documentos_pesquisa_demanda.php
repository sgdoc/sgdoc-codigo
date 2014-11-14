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

$aColumns = array(
    'ID',
    'DIGITAL',
    'ASSUNTO',
    'NUMERO',
    'TIPO',
    'ORIGEM',
    'ULTIMO_TRAMITE',
    'INTERESSADO',
    'SOLICITACAO_DEMANDA',
    'RESPOSTA_DEMANDA',
    'DATA_PRAZO',
    'ID'
);

$aColumnsFTS = array(
    'DIGITAL',
    'ASSUNTO',
    'NUMERO',
    'TIPO',
    'ORIGEM',
    'ULTIMO_TRAMITE',
);

$sIndexColumn = "ID";
$sTable = "EXT__SNAS__VW_DOCUMENTOS_PESQUISA_DEMANDA";
$sExtraQuery = '';

$tpPesquisa = isset($_SESSION['PESQUISAR_DOCUMENTOS_QUERY_PEDIODO']['tp_pesquisa']) ? $_SESSION['PESQUISAR_DOCUMENTOS_QUERY_PEDIODO']['tp_pesquisa'] : '';

if (isset($_SESSION['PESQUISAR_DOCUMENTOS'])) {
    foreach ($_SESSION['PESQUISAR_DOCUMENTOS'] as $key => $value) {
        if ($tpPesquisa == 'FTS' && in_array(strtoupper($key), $aColumnsFTS)) {

            //Escape de várias situações ocorridas em cópias de fontes duvidosas
            $strSearchWS = StringUtil::escapeFromGenericSourceCopy($value);

            //Insere & para consultas
            $tsQuery = str_replace(' ', '&', $strSearchWS);

            $sExtraQuery .= sprintf(" to_tsvector_sgdoc( CAST(%s AS TEXT) ) @@ to_tsquery_sgdoc( '\"%s\"' ) AND ", $key, $tsQuery);
        } else {
            $sExtraQuery .= " CAST({$key} AS TEXT) ILIKE '%{$value}%' AND ";
        }
    }
    $sExtraQuery = substr_replace($sExtraQuery, "", -4);
}

if (!empty($_SESSION['PESQUISAR_DOCUMENTOS_QUERY_PEDIODO']['dt_inicial']) &&
        !empty($_SESSION['PESQUISAR_DOCUMENTOS_QUERY_PEDIODO']['dt_final'])) {
    if ($sExtraQuery) {
        $sExtraQuery .= ' AND ';
    }
    $sExtraQuery .= " ({$_SESSION['PESQUISAR_DOCUMENTOS_QUERY_PEDIODO']['tp_periodo']}  
                  BETWEEN '{$_SESSION['PESQUISAR_DOCUMENTOS_QUERY_PEDIODO']['dt_inicial']}' 
                      AND '{$_SESSION['PESQUISAR_DOCUMENTOS_QUERY_PEDIODO']['dt_final']}')";
}

print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));