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
 * @deprecated 
 */

/**
 * Função responsável em construir a cláusula WHERE para a pesquisa nas Grids.
 * 
 * @param Array $arrayPesquisa array com os parametros da pesquisa. As chaves devem
 * ser correspondentes aos campos da tabela do banco de dados. Todos os dados são
 * case sensitive.
 * 
 * @param Array $arrayMapTabela array com o mapeamento da tabela. A estrutra deve
 * ser: $array['NOME_DO_CAMPO'] = 'tipo', sendo que os tipos são dividos somente
 * em 'numero' e 'string', já que a intenção é só construir a cláusula where. Todos
 * os dados são case sensitive. Todos os campos da tabela que podem ser usados na
 * consulta devem ser informados.
 * 
 * @return String
 * 
 */
function montaWherePesquisa($arrayPesquisa, $arrayMapTabela) {

    $extraSql = '';
    $i = 0;

    foreach ($arrayPesquisa as $chave => $valor) {
        if (!empty($valor)) {
            if ($i > 0) {
                $extraSql .= " AND ";
            }

            $tipoCampo = strtolower($arrayMapTabela[$chave]);

            if ($tipoCampo != 'string') {
                $extraSql .= "$chave = $valor";
            } else {
                $extraSql .= "CAST({$chave} AS TEXT) ILIKE '%$valor%'";
            }

            $i++;
        }
    }

    return $extraSql;
}

$aColumns = array(
    'ID',
    'CPF',
    'USUARIO',
    'NOME',
    'TELEFONE',
    'EMAIL',
//    'DIRETORIA',
    'SKYPE',
    'STATUS',
    'NULL');

$aColumnsFTS = array(
    'CPF',
    'USUARIO',
    'NOME',
    'TELEFONE',
    'EMAIL',
//    'DIRETORIA',
    'SKYPE',
);

$sIndexColumn = "ID";

$sTable = "TB_USUARIOS";

if (isset($_GET['PESQUISA'])) {

    //DEIXA SÓ NÚMEROS NOS CAMPOS DE CPF E TELEFONE PARA PESQUISA
    $_GET['PESQUISA']['CPF'] = preg_replace('/[^\d]/', '', $_GET['PESQUISA']['CPF']);
    $_GET['PESQUISA']['TELEFONE'] = preg_replace('/[^\d]/', '', $_GET['PESQUISA']['TELEFONE']);

    $mapeamentoTabela = array(
        'USUARIO' => 'string',
        'NOME' => 'string',
        'TELEFONE' => 'string',
        'EMAIL' => 'string',
        'ID_UNIDADE' => 'int',
        'SKYPE' => 'string',
        'CPF' => 'string'
    );

    $extraSql = montaWherePesquisa($_GET['PESQUISA'], $mapeamentoTabela);
}

print(Grid::getGrid($_GET, $aColumns, $sIndexColumn, $sTable, null, (isset($extraSql) ? $extraSql : false), $aColumnsFTS));