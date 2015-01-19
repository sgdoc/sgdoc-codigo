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

$controller = Controlador::getInstance();
?>

<html>
    <head>
        <script type="text/javascript"  src="plugins/datatable/media/js/jquery.dataTables.js"></script>
        <style type="text/css" title="currentStyle">
            @import "plugins/datatable/media/css/demo_table_tabs.css";
            fieldset{
                border: 1px #9ac619 dotted;
                margin: 2px;
            }
            fieldset label{
                margin: 5px;
            }
            #tabela_regras_filter input[type=text]{
                width: 100px;
            }
            #tabela_regras_filter .sorting_1{
                width: 20px;
            }
        </style>

        <script type="text/javascript">

            var oTableClassificacao;
            var iUnidadeAtiva;

            $(document).ready(function() {
                /*Tabs*/
                $("#tabs").tabs();
                $(".cabecalho-caixas").tabs();

                /*DataTable*/
                oTableClassificacao = $('#tabela_classificacao').dataTable({
                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    bStateSave: false,
                    bPaginate: true,
                    bProcessing: true,
                    bServerSide: true,
                    bJQueryUI: true,
                    sPaginationType: "full_numbers",
                    sAjaxSource: "modelos/classificacao/listar_classificacoes.php",
                    aoColumnDefs: [{bSortable: false, aTargets: [0, 1, 2]}],
                    oLanguage: {
                        sProcessing: "Carregando...",
                        sLengthMenu: "_MENU_ por página",
                        sZeroRecords: "Nenhuma classificacao encontrada.",
                        sInfo: "_START_ a _END_ de _TOTAL_ classificacoes.",
                        sInfoEmpty: "Nao foi possivel localizar classificacao com os parametros informados!",
                        sInfoFiltered: "(Total _MAX_ classificacoes)",
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
                        nRow.setAttribute('title', aData[0]);

                        var $line = $('td:eq(2)', nRow);
                        $line.html('');

                        if (aData[1] == '') {
                            $('td:eq(1)', nRow).html('<div title="">Nenhum</div>');
                        }

                        $("<img/>", {
                            src: 'imagens/alterar.png',
                            title: 'Editar',
                            'class': 'botao32'
                        }).bind("click", function() {
                            jquery_detalhar_classificacoes(aData[2]);
                        }).appendTo($line);

                        return nRow;
                    }
                });

                /*Dialogs*/
                /*Detalhar*/
                $('#box-detalhar-classificacoes').dialog({
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    width: 600,
                    autoHeight: true,
                    buttons: {
                        Salvar: function() {
                            if (jquery_validar_nova_classificacao('DETALHAR')) {
                                var c = confirm('Você tem certeza que deseja salvar esta classificacao agora?');
                                if (c) {
                                    $.post("modelos/classificacao/classificacao.php", {
                                        acao: 'alterar',
                                        id: $('#ID_DETALHAR_CLASSIFICACAO').val(),
                                        ds_classificacao: $('#DS_DETALHAR_CLASSIFICACAO').val(),
                                        nu_classificacao: $('#NU_DETALHAR_CLASSIFICACAO').val(),
                                        id_classificacao_pai: $('#ID_PAI_DETALHAR_CLASSIFICACAO').val()
                                    },
                                    function(data) {
                                        if (data.success == 'true') {
                                            $('#box-detalhar-classificacoes').dialog("close");
                                            oTableClassificacao.fnDraw(false);
                                            alert(data.message);
                                        } else {
                                            alert('Ocorreu um erro ao tentar salvar as informacoes da classificacao!\n[' + data.error + ']');
                                        }
                                    }, "json");
                                }
                            } else {
                                alert('Campo(s) obrigatorio(s) em branco ou preenchido(s) de forma invalida!');
                            }
                        }
                    }
                });
                /*Listeners*/

                /*Carregar Combos*/
                $('#ID_PAI_DETALHAR_CLASSIFICACAO').combobox('modelos/combos/classificacoes.php');
            });

            function jquery_detalhar_classificacoes(id) {
                $.post("modelos/classificacao/classificacao.php", {
                    acao: 'get',
                    valor: id,
                    campo: '*'
                },
                function(data) {
                    if (data.success == 'true') {
                        $('#ID_DETALHAR_CLASSIFICACAO').val(data.id);
                        $('#DS_DETALHAR_CLASSIFICACAO').val(data.ds_classificacao);
                        $('#NU_DETALHAR_CLASSIFICACAO').val(data.nu_classificacao);
                        $('#ID_PAI_DETALHAR_CLASSIFICACAO').val(data.id_classificacao_pai);
                        $('#box-detalhar-classificacoes').dialog('open');
                    } else {
                        alert('Ocorreu um erro ao tentar detalhar as informacoes da classificacao!');
                    }
                }, "json");

            }

        </script>
    </head>
    <body>
        <div class="cabecalho-caixas">
            <div class="logo-manter-unidades"></div>
            <div class="titulo-manter-unidades">Gerenciamento de Classificação</div>
            <div class="menu-auxiliar">
                <?php Util::montaMenus($controller->botoes, array('class' => 'botao32')); ?>
            </div>
        </div>

        <div id="tabs">
            <ul>
                <li><a title="" href="#tabs-1">Lista de Classificações do Arquivo</a></li>
            </ul>
            <div id="tabs-1">
                <table class="display" border="0" id="tabela_classificacao">
                    <thead>
                        <tr>
                            <th class="style13">Descrição</th>
                            <th class="style13">Classificação Pai</th>
                            <th class="style13">Opções</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!--Detalhar-->
        <div id="box-detalhar-classificacoes" class="div-form-dialog" title="Detalhes da Classificação">
            <fieldset>
                <label class="label">Informações Principais</label>
                <input class="FUNDOCAIXA1" id="ID_DETALHAR_CLASSIFICACAO" type="hidden">
                <div class="row">
                    <label class="label">*DESCRICAO:</label>
                    <span class="conteudo">
                        <input type="text" class="FUNDOCAIXA1" id="DS_DETALHAR_CLASSIFICACAO" onkeyup="DigitaLetraSeguro(this)">
                    </span>
                </div>
                <div class="row">
                    <label class="label">*NUMERO:</label>
                    <span class="conteudo">
                        <input type="text" class="FUNDOCAIXA1" id="NU_DETALHAR_CLASSIFICACAO" maxlength="15" />
                    </span>
                </div>
                <div class="row">
                    <label class="label">CLASSIFICAÇÃO PAI:</label>
                    <span class="conteudo">
                        <select class="FUNDOCAIXA1" id="ID_PAI_DETALHAR_CLASSIFICACAO"></select>
                    </span>
                </div>
            </fieldset>
        </div>
    </body>
</html>
