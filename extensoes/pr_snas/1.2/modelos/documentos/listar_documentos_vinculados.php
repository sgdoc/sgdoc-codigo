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

//$contexto = Controlador::getInstance()->getContexto();
$usuario = Zend_Auth::getInstance()->getStorage()->read();
$digital = current(CFModelDocumento::factory()->find($_REQUEST['DIGITAL_PAI']));
$lista = $_REQUEST['lista'];

$aColumns = array(
    'DEMANDA',
    'SOLICITACAO_DEMANDA',
    'RESPOSTA_DEMANDA',
    'INTERESSADO',
    'ORGAO',
    'PRAZO_DEMANDA',
    'FG_ATIVO',
    'PRAZO_PAI',
	'UNIDADE_DESTINO',
	'DATA_DEMANDA_RESPONDIDA',
	'SQ_PRAZO'
);
/*
$aColumnsFTS = array(
    'DIGITAL_FILHO',
    'ID_VINCULO',
    'RESPOSTA',
    'ID_VINCULO',
    'TEXTO_DEMANDA',
    'NOME_USUARIO',
    'FG_ATIVO'
);

$sIndexColumn = "ID_VINCULO";
*/
$sTable = "EXT__SNAS__VW_VINCULO_DOCUMENTOS";
$sExtraQuery = "DOCUMENTO_VINCULO_PAI = '{$digital->DIGITAL}'";

if ($lista == 'unidade') {
	$sExtraQuery .= ' and UNIDADE_DESTINO = ' . $usuario->ID_UNIDADE;
} else if ($lista == 'outras') {
	$sExtraQuery .= ' and UNIDADE_DESTINO <> ' . $usuario->ID_UNIDADE;
}

print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));