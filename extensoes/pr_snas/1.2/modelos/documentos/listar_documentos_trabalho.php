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
//COLUNAS DA TABELA DO BANCO
$aColumns = array(
    'ID',
    'PAI',
    'DIAS_RESTANTES',
    'DIGITAL',
    'DT_CADASTRO',
	'DT_DOCUMENTO',
    'ASSUNTO',
    'NUMERO',
    'TIPO',
    'ORIGEM',
    'INTERESSADO',
    'ID_UNID_AREA_TRABALHO',
	'PERMITE_ACAO'
);

//COLUNAS ONDE A PESQUISA TEXTUAL SERÁ FEITA
$aColumnsFTS = array(
    'DIGITAL',
    'ASSUNTO',
    'NUMERO',
    'TIPO',
    'ORIGEM',
    'INTERESSADO',
);


$sIndexColumn = "ID";
$sTable = "ext__snas__vw_area_trabalho_documentos";
$sExtraQuery = "area_busca = " . DaoUnidade::getUnidade(null, 'id');

print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));
