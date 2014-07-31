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

$controller = Controlador::getInstance();
$auth = $controller->usuario;

$sExtraQuery = '';
$sIndexColumn = "ID";
$sTable = "VW_ATENDIMENTO";

switch ($_GET['acao']) {
    case 'minha-caixa-de-demandas':
        
        $aColumns = array('ID',
            'CD_PROTOCOLO',
            'DT_ABERTURA',
            'NM_USUARIO',
            'NM_UNIDADE',
            'TX_EMAIL',
            'NU_TELEFONE',
            'TX_ASSUNTO',
            'TX_DESCRICAO',
            'TX_SKYPE',
            'ID');
        
        $aColumnsFTS = array(
            'CD_PROTOCOLO',
            'NM_USUARIO',
            'NM_UNIDADE',
            'TX_EMAIL',
            'NU_TELEFONE',
            'TX_ASSUNTO',
            'TX_DESCRICAO',
            'TX_SKYPE'
            );
        
        $sExtraQuery = " ID_ATENDENTE = {$auth->ID} AND ST_STATUS = 'Aguardando Atendimento' ";
        break;

    case 'demandas-triagem':
        
        $aColumns = array('ID',
            'CD_PROTOCOLO',
            'DT_ABERTURA',
            'NM_USUARIO',
            'NM_UNIDADE',
            'TX_EMAIL',
            'NU_TELEFONE',
            'TX_ASSUNTO',
            'TX_DESCRICAO',
            'TX_SKYPE',
            'ID');
        
        $aColumnsFTS = array(
            'CD_PROTOCOLO',
            'NM_USUARIO',
            'NM_UNIDADE',
            'TX_EMAIL',
            'NU_TELEFONE',
            'TX_ASSUNTO',
            'TX_DESCRICAO',
            'TX_SKYPE'
            );
        
        $sExtraQuery = " ST_STATUS = 'Triagem' ";
        break;

    case 'demandas-qualificar':
        
        $aColumns = array('ID',
            'CD_PROTOCOLO',
            'DT_ABERTURA',
            'NM_ATENDENTE',
            'DT_FINALIZACAO',
            'TX_ASSUNTO',
            'TX_DESCRICAO',
            'TX_COMENTARIO',
            'ID');
        
        $aColumnsFTS = array(
            'CD_PROTOCOLO',
            'NM_ATENDENTE',
            'TX_ASSUNTO',
            'TX_DESCRICAO',
            'TX_COMENTARIO'
            );
        
        $sExtraQuery = " ID_USUARIO = {$auth->ID} AND ST_STATUS = 'Pedido Finalizado' AND NU_NOTA IS NULL ";
        break;

    case 'historico-demandas-usuario':
        
        $aColumns = array('ID',
            'CD_PROTOCOLO',
            'DT_ABERTURA',
            'NM_ATENDENTE',
            'DT_FINALIZACAO',
            'TX_ASSUNTO',
            'TX_DESCRICAO',
            'TX_COMENTARIO',
            'NU_NOTA');
        
        $aColumnsFTS = array(
            'CD_PROTOCOLO',
            'NM_ATENDENTE',
            'TX_ASSUNTO',
            'TX_DESCRICAO',
            'TX_COMENTARIO',
            'NU_NOTA'
            );
        
        $sExtraQuery = " ID_USUARIO = {$auth->ID} AND ST_STATUS = 'Pedido Finalizado' AND NU_NOTA IS NOT NULL ";
        break;

    case 'historico-de-demandas':
        
        $aColumns = array('ID',
            'CD_PROTOCOLO',
            'DT_ABERTURA',
            'NM_USUARIO',
            'NU_TELEFONE',
            'NM_UNIDADE',
            'TX_ASSUNTO',
            'TX_DESCRICAO',
            'TX_COMENTARIO',
            'NM_TRIAGEM',
            'DT_TRIAGEM',
            'NM_ATENDENTE',
            'DT_FINALIZACAO',
            'ST_STATUS',
            'NU_NOTA');
        
        $aColumnsFTS = array(
            'CD_PROTOCOLO',
            'NM_USUARIO',
            'NU_TELEFONE',
            'NM_UNIDADE',
            'TX_ASSUNTO',
            'TX_DESCRICAO',
            'TX_COMENTARIO',
            'NM_TRIAGEM',
            'NM_ATENDENTE',
            'NU_NOTA'
            );
        
        break;

    default:
        break;
}

print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));