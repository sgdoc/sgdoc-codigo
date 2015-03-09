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

$aColumns = array('ID',
    'NU_REF',
    'INTERESSADO',
    'NU_RES',
    'NM_USUARIO_DESTINO',
    'NM_UNIDADE_DESTINO',
    'NM_USUARIO_ORIGEM',
    'NM_UNIDADE_ORIGEM',
    'NULL',
    'NULL',
    'DT_PRAZO',
    'DT_RESPOSTA',
    'DIAS_RESPOSTA');

$aColumnsFTS = array(
    'NU_REF',
    'INTERESSADO',
    'NU_RES',
    'NM_USUARIO_DESTINO',
    'NM_UNIDADE_DESTINO',
    'NM_USUARIO_ORIGEM',
    'NM_UNIDADE_ORIGEM');

$sIndexColumn = "ID";
$sTable = "VW_PRAZOS_RESPONDIDOS_USUARIOS";
$sExtraQuery = "ID_USUARIO_DESTINO = " . Zend_Auth::getInstance()->getIdentity()->ID;

print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));