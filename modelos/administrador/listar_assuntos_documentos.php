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
    'ASSUNTO_REAL', 
    'DS_USUARIO', 
    'HOMOLOGADO', 
    'ID'
);

$aColumnsFTS = array(
    'ASSUNTO', 
    'DS_USUARIO', 
    'HOMOLOGADO', 
);

$sIndexColumn = "ID";
$sTable = "VW_DOCUMENTOS_ASSUNTO";
$sExtraQuery = "";
if (isset($_SESSION['DOCUMENTO'])) {
    $tipo = $_SESSION['DOCUMENTO']['tipo'];
    foreach ($_SESSION['DOCUMENTO'] as $key => $value) {
        if ($key == 'ASSUNTO') {
            if ($tipo == 'EXATA') {
                $sExtraQuery .= " {$key} ILIKE '{$value}' AND ";
            } else {
                $sExtraQuery .= " CAST({$key} AS TEXT) ILIKE '%{$value}%' AND ";
            }
        } else if ($key == 'CORRIGIDO') {
            if ($value == '0') {
                $sExtraQuery .= " ID_ASSUNTO_REAL IS NULL AND ";
            } else {
                $sExtraQuery .= " ID_ASSUNTO_REAL IS NOT NULL AND ";
            }
        } else if ($key != 'tipo') {
            $sExtraQuery .= " {$key} = {$value} AND ";
        }
    }
    $sExtraQuery = substr_replace($sExtraQuery, "", -4);
}

print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, $conexao, $sExtraQuery, $aColumnsFTS));