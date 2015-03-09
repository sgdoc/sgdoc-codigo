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

$usuario = Controlador::getInstance()->usuario;

$aColumns = array(
    'ID',
    'NU_REF',
    'INTERESSADO',
    'NM_USUARIO_ORIGEM',
    'NM_USUARIO_DESTINO',
    'NM_UNIDADE_DESTINO',
    'NULL',
    'DT_PRAZO',
    'DIAS_RESTANTES',
    'ID_USUARIO_ORIGEM'
);

$aColumnsFTS = array(
    'NU_REF',
    'INTERESSADO',//? COALESCE(tb_processos_interessados.interessado, tb_documentos_cadastro.interessado)
    'NM_USUARIO_ORIGEM',
    'NM_USUARIO_DESTINO',
    'NM_UNIDADE_DESTINO',
);

$sIndexColumn = "ID";
$sTable = "VW_PRAZOS_ENVIADOS_SETOR";
$sExtraQuery = "ID_UNIDADE_ORIGEM = {$usuario->ID_UNIDADE}";

print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, $sExtraQuery, $aColumnsFTS));