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

include("function/auto_load_statics.php");

$controller = Controlador::getInstance();
$auth = $controller->usuario;

$objeto = DaoProcesso::getProcesso($_GET['numero_processo']);
$controller->setContexto($objeto);
$controller->botoes = Util::getMenus($auth, $controller->recurso, $controller->acl);
foreach ($controller->recurso->dependencias as $arquivo) {
    include_once('interfaces/' . $arquivo);
}
?>

<html>
    <head>
        <script type="text/javascript" src="plugins/datatable/media/js/jquery.dataTables.js"></script>
        <style type="text/css">
            @import "plugins/datatable/media/css/demo_table_tabs.css";
            body{
                margin: 10px;
                background-color: #101c01;
                background-image: url('imagens/<?php print(__BACKGROUND__); ?>');
                background-position: bottom right;
                background-repeat: no-repeat;
            }
            #form-novo-despacho-processo textarea,#form-novo-despacho-processo input{
                width: 270px;
            }
        </style>

        <script type="text/javascript">

            /* Create an array with the values of all dates in a column */
            $.fn.dataTableExt.afnSortData['dom-dt_cadastro'] = function(oSettings, iColumn)
            {
                var aData = [];
                $('td:eq(' + 8 + ')', oSettings.oApi._fnGetTrNodes(oSettings)).each(function() {
                    aData.push(this.value);
                });
                return aData;
            }
            $.fn.dataTableExt.afnSortData['dom-dt_despacho'] = function(oSettings, iColumn)
            {
                var aData = [];
                $('td:eq(' + 9 + ')', oSettings.oApi._fnGetTrNodes(oSettings)).each(function() {
                    aData.push(this.value);
                });
                return aData;
            }

            var oTabelaHistoricos = null;

            $(document).ready(function() {

                $("#tabs").tabs();
                $(".cabecalho-caixas").tabs();

                oTabelaHistoricos = $('#TabelaHistoricos').dataTable({
                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    bStateSave: false,
                    bPaginate: true,
                    bProcessing: true,
                    bServerSide: true,
                    bJQueryUI: true,
                    sPaginationType: "full_numbers",
                    aaSorting: [[3, "desc"]],
                    "aoColumns": [
                        null,
                        null,
                        null,
                        {"asSorting": ["desc", "asc"]},
                        {"asSorting": ["desc", "asc"]},
                        null,
                        null,
                        null
                    ],
                    sAjaxSource: "modelos/historicos/listar_historicos_documentos_processos.php?usercase=historico-despachos-processos&numero_processo=<?php echo $_GET['numero_processo'] ?>",
                    oLanguage: {
                        sProcessing: "Carregando...",
                        sLengthMenu: "_MENU_ por página",
                        sZeroRecords: "Nenhum despacho encontrado.",
                        sInfo: "_START_ a _END_ de _TOTAL_ despachos",
                        sInfoEmpty: "Não foi possível localizar despachos com os parâmetros informados!",
                        sInfoFiltered: "",
                        sInfoPostFix: "",
                        sSearch: "Pesquisar:",
                        oPaginate: {
                            sFirst: "Primeiro",
                            sPrevious: "Anterior",
                            sNext: "Próximo",
                            sLast: "Ultimo"
                        }
                    },
                    fnServerData: function(sSource, aoData, fnCallback) {
                        $.getJSON(sSource, aoData, function(json) {
                            fnCallback(json);
                        });
                    },
                    fnRowCallback: function(nRow, aData, iDisplayIndex) {
                        /* Contador */
                        $('td:eq(0)', nRow).html(iDisplayIndex + 1);
                        /* Complemento Em Branco */
                        if (aData[6] == '') {
                            $('td:eq(6)', nRow).html('Em Branco');
                        }
                        $('td:eq(3)', nRow).html(convertDateToString(aData[3]));
                        $('td:eq(4)', nRow).html(convertDateToString(aData[4]));

                        /*Retorna a linha modificada*/
                        return nRow;
                    }
                });
            });

        </script>

        <title>Despachos</title>
    </head>
    <body>
        <div class="cabecalho-caixas">
            <div class="logo-historico-despachos"></div>
            <div class="titulo-historico">Despachos - <?php echo $_GET['numero_processo'] ?></div>
            <div class="menu-auxiliar">
                <?php Util::montaMenus($controller->botoes, array('class' => 'botao32')); ?>
            </div>
        </div>
        <div id="tabs">
            <table class="display" border="0" id="TabelaHistoricos">
                <thead>
                    <tr>
                        <th class="style13">#</th>
                        <th class="style13">Usuario</th>
                        <th class="style13">Unidade</th>
                        <th class="style13">Data Cadastro</th>
                        <th class="style13">Data Despacho</th>
                        <th class="style13">Despacho</th>
                        <th class="style13">Complemento</th>
                        <th class="style13">Assinatura</th>
                    </tr>
                </thead>
            </table>
        </div>
    </body>
</html>
