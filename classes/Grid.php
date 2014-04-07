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
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 * @author Bruno Neves <brunonm@gmail.com>
 */
class Grid extends Base {
    /**
     * @todo Melhorar este metodo usando os recurso do PDO.
     * @todo Observacao : as chamas deste metodo nao estao validando o usuario logado! Corrigir urgente...
     */

    /**
     * Retorna o JSON gerado para o component DataTables do JqueryUi
     * @param <Array>	$_GET		requisição gerada pelo plugin
     * @param <Array>	$aColumns	colunas que serão lidas e enviadas de volta para o DataTables
     * @param <String>	$sIndexColumn	coluna indexada do banco
     * @param <String>	$sTable		nome da tabela com os dados
     * @param <Resource> $conexao	conexão do mysql
     * @param <String>	$sExtraQuery 	fragmento de sql para complementar a busca
     * @param <Array>	$aColumnsFTS 	lista de colunas de $aColumns que são indexadas por FTS
     */
    public static function getGrid($allGET, $aColumns, $sIndexColumn, $sTable, $conexao = NULL, $sExtraQuery = false, $aColumnsFTS = array()) {

        $micro = microtime(true);

        $arrColumnsAdapt = array();

        $sWhere = ($sExtraQuery) ? " {$sExtraQuery}" : '';

        foreach ($aColumns as $key => $value) {
            $arrColumnsAdapt[$key] = array($value => ((is_int($key)) ? $value : $key));
        }

        $grid = grid\Grid::factory(null)
                ->primary('ID')
                ->columns($arrColumnsAdapt)
                ->query($sTable)
                ->setExtraQuery($sWhere)
                ->setColumnsFTS($aColumnsFTS)
                ->params($allGET)
                ->make()
                ->output()
        ;
        print json_encode($grid);
    }

}