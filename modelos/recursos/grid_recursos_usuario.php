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

/**
 * Constrói a grid para ser apresentada na view.
 * 
 * @param array $aColumns colunas a serem apresentadas na grid
 * @param string $sIndexColumn coluna indexada
 * @param string[VIEW|QUERY SQL] $sTable obrigatório, pode ser uma view ou uma query.
 * @param string $sExtraQuery opcional (representa o where)
 * @param array $auxView opcional (apenas se usa quando a tabela for especificamente uma query)
 */
$aColumns = array(
    'ID', 
    'NOME', 
    'DESCRICAO', 
    'TIPO', 
    'FUNC_GET_PERMISSAO_UNIDADE' => "sgdoc.fn_permissao_unidade({$_GET['unidade']}, ID)",
    'FUNC_GET_PERMISSAO_USUARIO' => "sgdoc.fn_permissao_usuario({$_GET['usuario']}, ID)"
);
    
$aColumnsFTS = array(
    'NOME', 
    'DESCRICAO', 
    'TIPO'
);

$sIndexColumn = 'ID';
$sTable = 'VW_RECURSOS';

print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, NULL, false, $aColumnsFTS));
