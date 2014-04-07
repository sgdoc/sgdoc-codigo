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

$DIGITAL = null;
$NUMERO_PROCESSO = null;
$sExtraQuery = null;

/* Documentos */
if (isset($_GET['digital'])) {
    if (strlen($_GET['digital']) == 7) {
        $DIGITAL = $_SESSION['HISTORICO']['DIGITAL'] = $_GET['digital'];
    } else {
        $DIGITAL = $_SESSION['HISTORICO']['DIGITAL'];
    }
    $sExtraQuery = "DIGITAL = '{$DIGITAL}'";
}


/* Processos */
if (isset($_GET['numero_processo'])) {
    if (strlen($_GET['numero_processo']) > 0) {
        $NUMERO_PROCESSO = $_SESSION['HISTORICO']['NUMERO_PROCESSO'] = $_GET['numero_processo'];
    } else {
        $NUMERO_PROCESSO = $_SESSION['HISTORICO']['NUMERO_PROCESSO'];
    }
    $sExtraQuery = "NUMERO_PROCESSO = '{$NUMERO_PROCESSO}'";
}

switch ($_GET['usercase']) {
    /* Documentos */
    case 'historico-comentarios-documentos':
        $aColumns = array('ID', 'DT_CADASTRO', 'TEXTO_COMENTARIO', 'USUARIO', 'DIRETORIA');
        $aColumnsFTS = array(
            'TEXTO_COMENTARIO', 
            'USUARIO', 
            'DIRETORIA'
        );
        $sIndexColumn = "ID";
        $sTable = "VW_COMENTARIOS_DOCUMENTOS";
        print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));
        break;

    case 'historico-despachos-documentos':
        $aColumns = array('ID', 'USUARIO', 'DIRETORIA', 'DT_CADASTRO', 'DT_DESPACHO', 'TEXTO_DESPACHO', 'COMPLEMENTO', 'ASSINATURA_DESPACHO');
        $aColumnsFTS = array(
            'USUARIO', 
            'DIRETORIA', 
            'TEXTO_DESPACHO', 
            'COMPLEMENTO', 
            'ASSINATURA_DESPACHO'
        );
        $sIndexColumn = "ID";
        $sTable = "VW_DESPACHOS_DOCUMENTOS";
        print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));
        break;

    case 'historico-tramite-documentos':
        $aColumns = array('ID', 'USUARIO', 'DIRETORIA', 'ACAO', 'ORIGEM', 'DESTINO', 'DT_TRAMITE');
        $aColumnsFTS = array(
            'USUARIO', 
            'DIRETORIA', 
            'ACAO', 
            'ORIGEM', 
            'DESTINO', 
        );
        $sIndexColumn = "ID";
        $sTable = "VW_HISTORICO_TRAMITE_DOCUMENTOS";
        print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));
        break;

    /* Processos */
    case 'historico-comentarios-processos':
        $aColumns = array('ID', 'DT_CADASTRO', 'TEXTO_COMENTARIO', 'USUARIO', 'DIRETORIA');
        $aColumnsFTS = array(
            'TEXTO_COMENTARIO', 
            'USUARIO', 
            'DIRETORIA'
        );
        $sIndexColumn = "ID";
        $sTable = "VW_COMENTARIOS_PROCESSOS";
        print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));
        break;

    case 'historico-despachos-processos':
        $aColumns = array('ID', 'USUARIO', 'DIRETORIA', 'DT_CADASTRO', 'DT_DESPACHO', 'TEXTO_DESPACHO', 'COMPLEMENTO', 'ASSINATURA_DESPACHO');
        $aColumnsFTS = array(
            'USUARIO', 
            'DIRETORIA', 
            'TEXTO_DESPACHO', 
            'COMPLEMENTO', 
            'ASSINATURA_DESPACHO'            
        );
        $sIndexColumn = "ID";
        $sTable = "VW_DESPACHOS_PROCESSOS";
        print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));
        break;

    case 'historico-tramite-processos':
        $aColumns = array('ID', 'USUARIO', 'DIRETORIA', 'ACAO', 'ORIGEM', 'DESTINO', 'DT_TRAMITE');
        $aColumnsFTS = array(
            'USUARIO', 
            'DIRETORIA', 
            'ACAO', 
            'ORIGEM', 
            'DESTINO', 
        );
        $sIndexColumn = "ID";
        $sTable = "VW_HISTORICO_TRAMITE_PROCESSOS";
        print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));
        break;

    default:
        break;
}