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

$digital = trim($_REQUEST['digital_doc']);
$usuario = Zend_Auth::getInstance()->getStorage()->read();
$origem = trim($_REQUEST['origem']);
$status = trim($_REQUEST['status']);

$aColumns = array(
	0 => 'SQ_PRAZO',
	1 => 'DEMANDA',
	2 => 'GRUPO',
	3 => 'TX_SOLICITACAO',
	4 => 'TX_RESPOSTA',
	5 => 'INTERESSADO',
	6 => 'NOME_UNIDADE_ORIGEM',
	7 => 'NOME_UNIDADE_DESTINO',
	8 => 'DATA_PRAZO',
	9 => 'NULL', //coluna das opções
	10 => 'STATUS_PRAZO',
	11 => 'ID_UNIDADE_ORIGEM',
	12 => 'ID_UNIDADE_DESTINO'
);

$sTable = "snas.vw_vinculo_documentos_agrupados_filtro";
$sExtraQuery = "digital = '$digital'";

if ($origem != 'TD') {
	if ($origem == 'PU') {
		$sExtraQuery .= ' and ID_UNIDADE_DESTINO = ' . $usuario->ID_UNIDADE;
	} elseif ($origem == 'EN') {
		$sExtraQuery .= ' and ID_UNIDADE_ORIGEM = ' . $usuario->ID_UNIDADE;
		$sExtraQuery .= " and id_prazo_pai in (select sq_prazo FROM snas.vw_vinculo_documentos_filtro WHERE digital = '$digital' and id_unidade_destino = {$usuario->ID_UNIDADE})";
	} elseif ($origem == 'GU') {
		$sExtraQuery .= ' and ID_UNIDADE_ORIGEM = ' . $usuario->ID_UNIDADE;
	}
}

if ($status != 'TD') {
	$sExtraQuery .= " and STATUS_PRAZO = '$status'";
}

print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));