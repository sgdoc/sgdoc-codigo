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

switch ($_GET['usercase']) {
    case 'comentarios-documentos':
        $aColumns = array('ID', 'DIGITAL', 'USUARIO', 'DT_CADASTRO', 'TEXTO_COMENTARIO', 'DIRETORIA', 'ID');
        $aColumnsFTS = array(
            'DIGITAL', 'USUARIO', 'TEXTO_COMENTARIO', 'DIRETORIA',
        );

        $sIndexColumn = "ID";
        $sTable = "VW_COMENTARIOS_DOCUMENTOS";
        if (isset($_SESSION['COMENTARIO-DOCUMENTO'])) {
            foreach ($_SESSION['COMENTARIO-DOCUMENTO'] as $key => $value) {
                if ($key == 'ID_UNIDADE') {
                    if (strtoupper($value) != strtoupper('null')) {
                        $sExtraQuery .= " {$key} = {$value} AND ";
                    }
                } else {
                    $sExtraQuery .= " CAST({$key} AS TEXT) ILIKE '%{$value}%' AND ";
                }
            }
            $sExtraQuery = substr_replace($sExtraQuery, "", -4);
        }
        print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));
        break;

    case 'despachos-documentos':
        $aColumns = array('ID', 'DIGITAL', 'DT_CADASTRO', 'USUARIO', 'DT_DESPACHO', 'ASSINATURA_DESPACHO', 'TEXTO_DESPACHO', 'COMPLEMENTO', 'ID');
        $aColumnsFTS = array(
            'DIGITAL', 'USUARIO', 'ASSINATURA_DESPACHO', 'TEXTO_DESPACHO', 'COMPLEMENTO'
        );
        $sIndexColumn = "ID";
        $sTable = "VW_DESPACHOS_DOCUMENTOS";
        if (isset($_SESSION['DESPACHO-DOCUMENTO'])) {
            foreach ($_SESSION['DESPACHO-DOCUMENTO'] as $key => $value) {
                if ($key == 'ID_UNIDADE' && $value != null) {
                    if (strtoupper($value) != strtoupper('null')) {
                        $sExtraQuery .= " {$key} = {$value} AND ";
                    }
                } else {
                    $sExtraQuery .= " CAST({$key} AS TEXT) ILIKE '%{$value}%' AND ";
                }
            }
            $sExtraQuery = substr_replace($sExtraQuery, "", -4);
        }
        print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));
        break;

    case 'comentarios-processos':
        $aColumns = array('ID', 'NUMERO_PROCESSO', 'USUARIO', 'DT_CADASTRO', 'TEXTO_COMENTARIO', 'DIRETORIA', 'ID');
        $aColumnsFTS = array(
            'NUMERO_PROCESSO', 'USUARIO', 'TEXTO_COMENTARIO', 'DIRETORIA'
        );
        $sIndexColumn = "ID";
        $sTable = "VW_COMENTARIOS_PROCESSOS";
        if (isset($_SESSION['COMENTARIO-PROCESSO'])) {
            foreach ($_SESSION['COMENTARIO-PROCESSO'] as $key => $value) {
                if ($key == 'ID_UNIDADE') {
                    if (strtoupper($value) != strtoupper('null')) {
                        $sExtraQuery .= " {$key} = {$value} AND ";
                    }
                } else {
                    $sExtraQuery .= " CAST({$key} AS TEXT) ILIKE '%{$value}%' AND ";
                }
            }
            $sExtraQuery = substr_replace($sExtraQuery, "", -4);
        }
        print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));
        break;

    case 'despachos-processos':
        $aColumns = array('ID', 'NUMERO_PROCESSO', 'DT_CADASTRO', 'USUARIO', 'DT_DESPACHO', 'ASSINATURA_DESPACHO', 'TEXTO_DESPACHO', 'COMPLEMENTO', 'ID');
        $aColumnsFTS = array(
            'NUMERO_PROCESSO', 'USUARIO', 'ASSINATURA_DESPACHO', 'TEXTO_DESPACHO', 'COMPLEMENTO'
        );
        $sIndexColumn = "ID";
        $sTable = "VW_DESPACHOS_PROCESSOS";
        if (isset($_SESSION['DESPACHO-PROCESSO'])) {
            foreach ($_SESSION['DESPACHO-PROCESSO'] as $key => $value) {
                if ($key == 'ID_UNIDADE') {
                    if (strtoupper($value) != strtoupper('null')) {
                        $sExtraQuery .= " {$key} = {$value} AND ";
                    }
                } else {
                    $sExtraQuery .= " CAST({$key} AS TEXT) ILIKE '%{$value}%' AND ";
                }
            }
            $sExtraQuery = substr_replace($sExtraQuery, "", -4);
        }
        print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));
        break;

    default:
        break;
}