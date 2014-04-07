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
    'ASSUNTO',
    'NU_REF',
    'TIPO',
    'NM_UNIDADE_DESTINO',
    'TX_SOLICITACAO',
    'TX_RESPOSTA',
    'DT_PRAZO',
    'NM_UNIDADE_ORIGEM',//'NM_USUARIO_ORIGEM',
    'INTERESSADO',
    'DIAS_RESTANTES',
    'DIAS_RESPOSTA',
    'ID_USUARIO_RESPOSTA',//Para controle de prazos respondidos
);

$aColumnsFTS = array(
    'ASSUNTO',
    'NU_REF',
    'TIPO',
    'NM_UNIDADE_DESTINO',
    'TX_SOLICITACAO',
    'TX_RESPOSTA',
    'NM_UNIDADE_ORIGEM',
    'INTERESSADO'
);

$sIndexColumn = "ID";
$sTable = "VW_PRAZOS_PESQUISA";
$sExtraQuery = '';

$tpPesquisa = isset($_SESSION['PESQUISAR_PRAZOS_QUERY_PERIODO']['tp_pesquisa'])? $_SESSION['PESQUISAR_PRAZOS_QUERY_PERIODO']['tp_pesquisa'] : '';

$arrClausule = array();
/**
 * TODO: Realizar tratamento em formação de consulta para ESCAPE de aspas duplas e etc
 */
if (isset($_SESSION['PESQUISAR_PRAZOS'])) {
    foreach ($_SESSION['PESQUISAR_PRAZOS'] as $key => $value) {
        if( $tpPesquisa == 'FTS' && in_array( strtoupper($key), $aColumnsFTS ) ){
            //Remove Espaços duplicados
            $strSearchWS = preg_replace('/\s\s+/', ' ', $value);
            //Insere & para consultas
            $tsQuery = str_replace(' ', '&', $strSearchWS );
            
            //Adiciona ao array de clausulas
            $arrClausule[] = sprintf( " to_tsvector_sgdoc( CAST(%s AS TEXT) ) @@ to_tsquery_sgdoc( '\"%s\"' ) ", $key, $tsQuery); 
 
        }else{
            if($key!='st_resposta'){//Tratamento diferenciado
                //Adiciona ao array de clausulas
                $arrClausule[] = " CAST({$key} AS TEXT) ILIKE '%{$value}%' ";
            }
        }
    }
}

//Trata pesquisa a ser feita incluindo STATUS DE RESPOSTA
if( isset($_SESSION['PESQUISAR_PRAZOS']['st_resposta']) ){
    switch ($_SESSION['PESQUISAR_PRAZOS']['st_resposta']) {
        case "RESP":
            $arrClausule[] = " id_usuario_resposta IS NOT NULL ";
            break;
        case "NRESP":
            $arrClausule[] = " id_usuario_resposta IS NULL ";
            break;
        default:
            break;
    }
}
//Une as cláusulas com conectivo AND
$sExtraQuery = implode(" AND ", $arrClausule);

if (isset($_SESSION['PESQUISAR_PRAZOS_QUERY_PERIODO']['dt_prazo']) && $_SESSION['PESQUISAR_PRAZOS_QUERY_PERIODO']['dt_prazo'] != ''){
    $sExtraQuery .= ($sExtraQuery)? " AND " : "";
    $sExtraQuery .= " DT_PRAZO = '{$_SESSION['PESQUISAR_PRAZOS_QUERY_PERIODO']['dt_prazo']}' ";
}

if (isset($_SESSION['PESQUISAR_PRAZOS_QUERY_PERIODO']['dt_resposta']) && $_SESSION['PESQUISAR_PRAZOS_QUERY_PERIODO']['dt_resposta'] != ''){
    $sExtraQuery .= ($sExtraQuery)? " AND " : "";
    $sExtraQuery .= " DT_RESPOSTA = '{$_SESSION['PESQUISAR_PRAZOS_QUERY_PERIODO']['dt_resposta']}' ";
}

//die($sExtraQuery);

print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));