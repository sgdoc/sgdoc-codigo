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
?>

<html>
    <head>
        <style type="text/css">
            @import "plugins/datatable/media/css/demo_table_tabs.css";
            body{
                margin: 10px;
            }

            #botaoNovo{
                height: 16px;
                width: 16px;
                background-image: url('imagens/fam/add.png');
            }

            .print{
                cursor: pointer;
                height: 16px;
                width: 16px;
                background-image: url('imagens/fam/printer.png');
            }

        </style>

        <script type="text/javascript" src="plugins/datatable/media/js/jquery.dataTables.js"></script>
        <script type="text/javascript">
            try {
                var oTabelaEtiquetas = null;

                $(document).ready(function() {

                    $('.print').die('click').live('click', function() {
                        open('etiquetas.php?lote=' + $(this).attr('lote') + '&unidade=' + $(this).attr('unit'));
                    });

                    $("#tabs").tabs();
                    $(".cabecalho-caixas").tabs();

                    oTabelaEtiquetas = $('#TabelaEtiquetas').dataTable({
                        aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                        bStateSave: false,
                        bPaginate: true,
                        bProcessing: true,
                        bServerSide: true,
                        bJQueryUI: true,
                        sPaginationType: "full_numbers",
                        sAjaxSource: "modelos/etiquetas/listar_etiquetas_documentos.php",
                        oLanguage: {
                            sProcessing: "Carregando...",
                            sLengthMenu: "_MENU_ por página",
                            sZeroRecords: "Nenhuma etiqueta encontrada.",
                            sInfo: "_START_ a _END_ de _TOTAL_ etiquetas",
                            sInfoEmpty: "Nao foi possivel localizar etiquetas com o parametros informados!",
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
                            $('td:eq(4)', nRow).html('<div class="print" lote="' + aData[0] + '" unit="' + aData[4] + '" title="Imprimir o lote ' + aData[0] + '"></div>');

                            return nRow;
                        },
                        fnDrawCallback: function(oSettings, nRow) {
                        },
                        aoColumnDefs: [
                            // { bSortable: false, aTargets: [0, 9] }
                        ]
                    });

                });

            } catch (e) {
                alert('Ocorreu um erro:\n[' + e + ']');
            }

        </script>
        <title>Gerenciamento de Etiquetas</title>
    </head>
    <body>
        <div class="cabecalho-caixas">
            <div class="logo-gerenciamento-etiquetas"></div>
            <div class="titulo-gerenciamento-etiquetas">Gerenciamento de Etiquetas</div>
            <div class="menu-auxiliar">
                <?php Util::montaMenus($controller->botoes, array('class' => 'botao32')); ?>
            </div>
        </div>
        <div id="tabs">
            <ul>
                <li><a href="#tabs-1">Lote(s) em Aberto</a></li>
            </ul>
            <div id="tabs-1">
                <table class="display" border="0" id="TabelaEtiquetas">
                    <thead>
                        <tr>
                            <th class="column-checkbox"><input type="checkbox"></th>
                            <th class="style13">Numero do Lote</th>
                            <th class="style13">Unidade/Setor</th>
                            <th class="style13">Etiquetas Disponiveis</th>
                            <th class="style13">Opção</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <span class="style13 rodape"><?php print(__RODAPE__); ?></span>
    </body>
</html>
