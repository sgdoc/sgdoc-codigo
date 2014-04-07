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
include("verificador_caixas.php");
include("detalhar_processos.php");

$controller = Controlador::getInstance();
$area = $controller->usuario->ID_UNIDADE;
?>
<html>
    <head>
        <title>Resultado da Pesquisa</title>

        <style type="text/css">
            @import "plugins/datatable/media/css/demo_table_tabs.css";
            body{
                margin: 10px;
            }
        </style>

        <script type="text/javascript" src="plugins/datatable/media/js/jquery.dataTables.js"></script>

        <script type="text/javascript">
            var total_documentos;
            var oTableProcessos;
            var area = '<?php echo $area; ?>';

            $(document).ready(function() {

                $('#botao-voltar-form-pesquisar').click(function() {
                    window.location = 'menu_pesquisar.php?acao=processo';
                });

                $("#tabs").tabs();
                $(".cabecalho-caixas").tabs();

                oTableProcessos = $('#TabelaProcessos').dataTable({
                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    bStateSave: false,
                    bPaginate: true,
                    bProcessing: true,
                    bServerSide: true,
                    bJQueryUI: true,
                    sPaginationType: "full_numbers",
                    sAjaxSource: "modelos/processos/listar_processos_pesquisa.php",
                    "aoColumns": [
                        {"sWidth": "7px"},
                        {"sWidth": "25px"},
                        {"sWidth": "25px"},
                        {"sWidth": "90px"},
                        {"sWidth": "150px"},
                        {"sWidth": "115px"},
                        {"sWidth": "130px"},
                        {"sWidth": "150px"},
                        {"sWidth": "60px"}
                    ],
                    oLanguage: {
                        sProcessing: "Carregando...",
                        sLengthMenu: "_MENU_ por página",
                        sZeroRecords: "Nenhum registro encontrado.",
                        sInfo: "_START_ a _END_ de _TOTAL_ registros",
                        sInfoEmpty: "Nao foi possivel localizar registros com o parametros informados!",
                        sInfoFiltered: "(Total _MAX_ registros)",
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
                        total_documentos = (iDisplayIndex + 1);
                        /*Opções*/
                        $('td:eq(0)', nRow).html(iDisplayIndex + 1);
                        var $line = $('td:eq(8)', nRow);
                        $line.html('<div title="">');

                        /*Flags de Prazo e Vinculacao - Inicio*/
                        if (aData[1] != '') {
                            $('td:eq(1)', nRow).html('<div class="flag-possui-relacao" title="Este documento possui relacao com outros documentos."></div>');
                        } else {
                            $('td:eq(1)', nRow).html('<div class=""></div>');
                        }

                        if (aData[2] != '') {
                            if (aData[2] == 1 || aData[2] == 2) {
                                $('td:eq(2)', nRow).html('<div class="flag-prazo-vermelho" title="Atencao! Falta(m)' + (aData[2]) + ' dia(s)"></div>');
                            } else if (aData[2] <= 0) {
                                if (aData[2] == 0) {
                                    $('td:eq(2)', nRow).html('<div class="flag-prazo-vermelho" title="Atencao! Esgota hoje."></div>');
                                } else {
                                    $('td:eq(2)', nRow).html('<div class="flag-prazo-vermelho" title="Atencao! Esgotado a ' + (-1 * aData[2]) + ' dia(s)"></div>');
                                }
                            } else if (aData[2] <= 7) {
                                $('td:eq(2)', nRow).html('<div class="flag-prazo-amarelo" title="Falta(m) ' + (aData[2]) + ' dia(s)"></div>');
                            } else if (aData[2] <= 15) {
                                $('td:eq(2)', nRow).html('<div class="flag-prazo-verde" title="Falta(m) ' + (aData[2]) + ' dia(s)"></div>');
                            } else if (aData[2]) {
                                $('td:eq(2)', nRow).html('<div class="flag-prazo-verde" title="Falta(m) ' + (aData[2]) + ' dia(s)"></div>');
                            }
                        } else {
                            $('td:eq(2)', nRow).html('<div class=""></div>');
                        }
                        /*Converter formato Date para String (dd/mm/aaaa)*/
                        //$('td:eq(6)', nRow).html(convertDateToString(aData[6]));

<?php
// verifica a existencia da permissao para visualizar anexos/apensos
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(3111))) {
    ?>
                            // Anexos/Apensos
                            $("<img/>", {
                                src: 'imagens/lista_anexos.png',
                                title: 'Peças/Vínculos',
                                'class': 'botao30'
                            }).bind("click", function() {
                                jquery_listar_vinculacao_processo(aData[3], false);
                            }).appendTo($line);

    <?php
}
// verifica a existencia da permissao para detalhar processos
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(3112))) {
    ?>
                            // Alterar
                            $("<img/>", {
                                src: 'imagens/alterar.png',
                                title: 'Detalhar',
                                'class': 'botao30'
                            }).bind("click", function() {
                                jquery_detalhar_processo(aData[3], area);
                            }).appendTo($line);

    <?php
}
?>

                        // Visualizar Imagens
                        $("<img/>", {
                            src: 'imagens/visualizar.png',
                            title: 'Visualizar Imagem',
                            'class': 'botao30'
                        }).bind("click", function() {
                            visualizar_imagens_processo(aData[3]);
                        }).appendTo($line);

                        $("</div>").appendTo($line);
                        return nRow;
                    },
                    fnDrawCallback: function(oSettings, nRow) {
                    },
                    aoColumnDefs: [
                        {bSortable: false, aTargets: [0, 8]}
                    ]
                });
            });

        </script>
    </head>
    <body>
        <div class="cabecalho-caixas">
            <div class="logo-pesquisa-processos"></div>
            <div class="titulo-pesquisa-processos">Resultado da Pesquisa</div>
            <div class="menu-auxiliar">
                <?php Util::montaMenus($controller->botoes, array('class' => 'botao32')); ?>
            </div>
        </div>
        <div id="tabs">

            <ul>
                <li><a href="#tabs-1">Lista de Processos</a></li>
            </ul>
            <div id="tabs-1">
                <table class="display" border="0" id="TabelaProcessos">
                    <thead>
                        <tr>
                            <th class="style13 column-checkbox"><input type="checkbox" id="marcadorP" onChange="marcar_todos_processos();"></th>
                            <th title="Anexo/Apenso" class="style13 column-processo"></th>
                            <th title="Prazo" class="style13 column-processo"></th>
                            <th class="style13 column-processo">Processo</th>
                            <th class="style13 column-interessado">Interessado</th>
                            <th class="style13 column-assunto">Assunto</th>
                            <th class="style13 column-data">Origem</th>
                            <th class="style13 column-movimentacao">Movimentacao</th>
                            <th class="style13 column-opcao-2">Opções</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <span class="style13 rodape"><?php echo __RODAPE__; ?></span>
    </body>
</html>