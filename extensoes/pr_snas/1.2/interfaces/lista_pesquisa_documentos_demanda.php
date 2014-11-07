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
include(__BASE_PATH__ . "/interfaces/verificador_caixas.php");
include(__BASE_PATH__ . '/extensoes/pr_snas/1.2/interfaces/form_novo_prazo.php');
include(__BASE_PATH__ . "/extensoes/pr_snas/1.2/interfaces/detalhar_documentos.php");

$controller = Controlador::getInstance();
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

        <script type="text/javascript" charset="utf-8">

            var oTableDocumentos = null;
            var acao_pesquisa = '<?php echo $_REQUEST['acao_pesquisa']; ?>';
            var documentoPai = '<?php echo $_REQUEST['digital']; ?>';

            $(document).ready(function() {


                $("#tabs").tabs();
                $(".cabecalho-caixas").tabs();

                $('#botao-voltar-form-pesquisar').click(function() {
                    window.location = 'pesquisar_documento.php?acao=documento';
                });

                // Configura o botão de adicionar demanda
                $('.botao-adicionar-demanda').die('click').live('click', function(e) {
                    e.preventDefault();
                    if (confirm('Esta ação fará com que o documento ' + documentoPai + ' seja assocaido à demanda ' + $(this).attr('demanda') + '.\n Confirma ação?')) {
                        jquery_salvar_demanda_pesquisa($(this).attr('demanda'));
                    }
                });

                oTableDocumentos = $('#TabelaDocumentos').dataTable({
                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    bStateSave: false,
                    bPaginate: true,
                    bProcessing: true,
                    bServerSide: true,
                    bJQueryUI: true,
                    sPaginationType: "full_numbers",
                    sAjaxSource: "modelos/documentos/listar_documentos_pesquisa_demanda.php",
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
                        /*Opcoes*/
                        $('td:eq(0)', nRow).html(iDisplayIndex + 1);
                        var $line = $('td:eq(11)', nRow);
                        $line.html('<div title="">');

                        /*Flags de Prazo e Vinculacao - Inicio*/
                        if (aData[1] != '') {
                            //$('td:eq(1)', nRow).html('<div onclick=jquery_listar_vinculacao_documento("' + aData[3] + '"); class="flag-possui-relacao" title="Este documento possui relacao com outros documentos."></div>');
                        } else {
                            // $('td:eq(1)', nRow).html('<div></div>');
                        }

                        /*
                         if (aData[2] != '') {
                         if (aData[2] == 1 || aData[2] == 2) {
                         $('td:eq(2)', nRow).html('<div class="flag-prazo-vermelho" title="Atenção! Falta(m)' + (aData[2]) + ' dia(s)"></div>');
                         } else if (aData[2] <= 0) {
                         if (aData[2] == 0) {
                         $('td:eq(2)', nRow).html('<div class="flag-prazo-vermelho" title="Atenção! Esgota hoje."></div>');
                         } else {
                         $('td:eq(2)', nRow).html('<div class="flag-prazo-vermelho" title="Atenção! Esgotado a ' + (-1 * aData[2]) + ' dia(s)"></div>');
                         }
                         } else if (aData[2] <= 7) {
                         $('td:eq(2)', nRow).html('<div class="flag-prazo-amarelo" title="Falta(m) ' + (aData[2]) + ' dia(s)"></div>');
                         } else if (aData[2] <= 15) {
                         $('td:eq(2)', nRow).html('<div class="flag-prazo-verde" title="Falta(m) ' + (aData[2]) + ' dia(s)"></div>');
                         } else if (aData[2]) {
                         $('td:eq(2)', nRow).html('<div class="flag-prazo-verde" title="Falta(m) ' + (aData[2]) + ' dia(s)"></div>');
                         }
                         } else {
                         $('td:eq(2)', nRow).html('<div></div>');
                         }*/
                        if (acao_pesquisa != null && acao_pesquisa == 'demanda')
                        {
                            //$('td:eq(10)', nRow).html('<button class="botao-adicionar-demanda" demanda="' + aData[3] + '">Adicionar Demanda</button>');

                            // Botão adicionar demanda pesquisada
                            $("<img/>", {
                                src: '/extensoes/pr_snas/1.2/imagens/adicionar.png',
                                title: 'Adicionar esta demanda ao documento principal',
                                'class': 'botao30 botao-adicionar-demanda',
                                demanda: aData[1]
                            }).bind("click", function() {
                                //jquery_salvar_demanda_pesquisa(aData[3]);
                            }).appendTo($line);
                        }

                        /*Converter formato Date para String (dd/mm/aaaa)*/
                        if (aData[7] != "") {
                            $('td:eq(7)', nRow).html(aData[7].replace(" -  -", ""));
                        }

                        if (aData[10] != "") {
                            $('td:eq(10)', nRow).html(convertDateToString(aData[10]));
                        } else {
                            $('td:eq(10)', nRow).html("");
                        }


<?php
// verifica a existencia da permissao para visualizar anexos/apensos
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(3101))) {
    ?>
                            // Anexos/Apensos
                            $("<img/>", {
                                src: 'imagens/lista_anexos.png',
                                title: 'Anexos/Apensos',
                                'class': 'botao30'
                            }).bind("click", function() {
                                jquery_listar_vinculacao_documento(aData[1], false);
                            }).appendTo($line);

    <?php
}
// verifica a existencia da permissao para detalhar documentos
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(3102))) {
    ?>
                            // Alterar
                            $("<img/>", {
                                src: 'imagens/alterar.png',
                                title: 'Detalhar',
                                'class': 'botao30'
                            }).bind("click", function() {
                                jquery_detalhar_documento(aData[1]);
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
                            visualizar_imagens_documento(aData[1]);
                        }).appendTo($line);

                        $("</div>").appendTo($line);

                        return nRow;
                    },
                    fnDrawCallback: function(oSettings, nRow) {
                    },
                    aoColumnDefs: [
                        {bSortable: false, "aTargets": [0, 9]}
                    ]
                });

            });

            // Cadastrar demanda existente para o ducumento selecioando
            function jquery_salvar_demanda_pesquisa(filho) {
                $.post("modelos/documentos/vinculacao.php", {
                    acao: 'vincular',
                    pai: '<?php echo $_REQUEST['digital']; ?>',
                    filho: '' + filho + '',
                    vinculacao: 3,
                }, function(data) {
                    try {
                        if (data.success == 'true') {
                            if (confirm('Deseja criar um novo prazo para esta demanda associada?'))
                            {
                                // Abrir a tela de prazo quando selecionado o botão adiconar demanda
                                $('#box-novo-prazo').dialog('open');
                                $('#nu_proc_dig_ref').val(filho);
                            }
                        } else {
                            alert(data.error);
                        }
                    } catch (e) {
                        alert('Ocorreu um erro ao tentar associar essa demanda!\n[' + e + ']');
                    }
                }, "json");
            }
        </script>
    </head>
    <body>
        <div class="cabecalho-caixas">
            <div class="logo-pesquisa-documentos"></div>
            <div class="titulo-pesquisa-documentos">Resultado da Pesquisa</div>
            <div class="menu-auxiliar">
                <a id="botao-voltar-form-pesquisar" href="area_trabalho.php">
                    <img class="botao32" src="imagens/voltar.png" title="Voltar para a Área de trabalho">
                </a>
                <?php Util::montaMenus($controller->botoes, array('class' => 'botao32')); ?>
            </div>
        </div>
        <div id="tabs">
            <ul>
                <li><a href="#tabs-1">Lista de Documentos</a></li>
            </ul>
            <div id="tabs-1">
                <table class="display" id="TabelaDocumentos">
                    <thead>
                        <tr>
                            <th class="style13 column-checkbox">#</th>
                            <th class="style13 column-digital">Digital</th>
                            <th class="style13 column-assunto">Assunto</th>
                            <th class="style13 column-numero">Número</th>
                            <th class="style13 column-tipo">Tipo</th>
                            <th class="style13 column-origem">Origem</th>
                            <th class="style13 column-movimentacao">Movimentação</th>
                            <th class="style13 column-checkbox">Interessado</th>
                            <th title="Prazo" class="style13 column-checkbox">Solicitação</th>
                            <th title="Prazo" class="style13 column-checkbox">Resposta</th>
                            <th class="style13 column-data">Data</th>
                            <th class="style13 column-options-2">Opções</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <span class="style13 rodape"><?php echo __RODAPE__; ?></span>

    </body>
</html>