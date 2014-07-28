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
    'NU_CAIXA', 
    'DS_CLASSIFICACAO', 
    'DS_USUARIO', 
    'DS_UNIDADE', 
    'NU_ANO_CAIXA', 
    'ST_FINALIZADA', 
    'DT_CADASTRO', 
    'NU_DOCUMENTOS_CAIXA'
);

$aColumnsFTS = array(
    'NU_CAIXA', 
    'DS_CLASSIFICACAO', 
    'DS_USUARIO', 
    'DS_UNIDADE', 
    'NU_ANO_CAIXA', 
    'ST_FINALIZADA', 
    'DT_CADASTRO', 
    'NU_DOCUMENTOS_CAIXA'
);

$sIndexColumn = "ID";
$sTable = "VW_CAIXAS";

$sExtraQuery = "ST_ATIVO = 1 AND ";
if (isset($_SESSION['PESQUISAR_CAIXAS'])) {
    foreach ($_SESSION['PESQUISAR_CAIXAS'] as $key => $value) {
        if ($value != "") {
            if (strtolower($key) != 'nu_caixa' && strtolower($key) != 'nu_ano_caixa') {
                $sExtraQuery .= " {$key} = {$value} AND ";
            } else {
                $sExtraQuery .= " CAST({$key} AS TEXT) ILIKE '%{$value}%' AND ";
            }
        }
    }
}
$sExtraQuery = substr_replace($sExtraQuery, "", -4);

print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));