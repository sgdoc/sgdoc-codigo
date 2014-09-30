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
?>
<link rel="stylesheet" type="text/css" href="plugins/tree/css/style.css" />
<script type="text/javascript" src="plugins/tree/js/jquery.simple.tree.js"></script>
<script type="text/javascript" src="plugins/tree/js/langManager.js"></script>
<script type="text/javascript" src="plugins/tree/js/treeOperations.js"></script>

<!-- Javascript Documentos da Área de Trabalho-->
<script type="text/javascript">

    var oTableDocumentos;
    var total_documentos;

    $(document).ready(function() {
        /*DataTable*/
        oTableDocumentos = $('#TabelaDocumentos').dataTable({
            aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: true,
            bProcessing: false,
            bServerSide: true,
            bJQueryUI: true,
            sPaginationType: "full_numbers",
            sAjaxSource: "modelos/documentos/listar_documentos_trabalho.php",
            oLanguage: {
                sProcessing: "Carregando...",
                sLengthMenu: "_MENU_ por página",
                sZeroRecords: "Nenhum documento encontrado.",
                sInfo: "_START_ a _END_ de _TOTAL_ documentos",
                sInfoEmpty: "Não foi possível localizar documentos com o parametros informados!",
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
            fnFooterCallback: function(nRow, aaData, iStart, iEnd, aiDisplay) {
<?php if (Session::exists('digitalPesquisarDemandasPR')): ?>
                    jquery_detalhar_documento('<?php print(Session::get('digitalPesquisarDemandasPR')); ?>');
<?php endif; ?>
            },
            fnServerData: function(sSource, aoData, fnCallback) {
                $.getJSON(sSource, aoData, function(json) {
                    fnCallback(json);
                    if (json.iTotalRecords == 0) {
                        jquery_datatable_complementa_mensagem_vazia('TabelaDocumentos');
                    }
                });
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                total_documentos = (iDisplayIndex + 1);

                $('td:eq(0)', nRow).html('<input type="checkbox" id="DIGITAL[' + iDisplayIndex + ']" value="' + aData[3] + '">');

                /* Flags de Prazo e Vinculacao - Inicio */
                if (aData[1] != '') {
                    $('td:eq(1)', nRow).html('<div onclick=jquery_listar_vinculacao_documento("' + aData[3] + '"); class="flag-possui-relacao" title="Este documento possui relacao com outros documentos."></div>');
                } else {
                    $('td:eq(1)', nRow).html('<div title=""></div>');
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
                    $('td:eq(2)', nRow).html('<div title=""></div>');
                }

                /* Converter formato Date para String (dd/mm/aaaa) */
                $('td:eq(4)', nRow).html(convertDateToString(aData[4])); /*DT_CADASTRO*/
                $('td:eq(5)', nRow).html(convertDateToString(aData[5])); /*DT_DOCUMENTO*/
                
                $('td:eq(6)', nRow).html(aData[6]);

                if (aData[8] == '') {
                    $('td:eq(8)', nRow).html('<div title=""></div>');
                }

                if (aData[9] != '') {
                    $('td:eq(9)', nRow).html(aData[9]);
                } else {
                    $('td:eq(9)', nRow).html('<div title=""></div>');
                }

                if (aData[10] == '') {
                    $('td:eq(10)', nRow).html('<div title=""></div>');
                }

                var $line = $('td:eq(11)', nRow);
                $line.html('<div title="">');
                
<?php
// verifica a existencia da permissao para autuar processos
if (array_key_exists('309', Controlador::getInstance()->recurso->dependencias)) {
    ?>
                    // Autuar
                    $("<img/>", {
                        src: 'imagens/autuar.png',
                        title: 'Autuar',
                        'class': 'botao30'
                    }).bind("click", function() {
                        jquery_autuar(aData[3]);
                    }).appendTo($line);

    <?php
}
// verifica a existencia da permissao para tramitar documentos
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(307))) {
    ?>
    			if (aData[12] == 'S') {
                    // Tramitar
                    $("<img/>", {
                        src: 'imagens/tramitar.png',
                        title: 'Tramitar Documento',
                        'class': 'botao30'
                    }).bind("click", function() {
                        jquery_tramitar_documento(aData[3]);
                    }).appendTo($line);
    			}
    <?php
}
// verifica a existencia da permissao para visualizar anexos/apensos
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(3101))) {
    ?>
                    // Anexos/Apensos
                    $("<img/>", {
                        src: 'imagens/lista_anexos.png',
                        title: 'Anexos/Apensos',
                        'class': 'botao30'
                    }).bind("click", function() {
                        jquery_listar_vinculacao_documento(aData[3], true);
                    }).appendTo($line);

    <?php
}
// verifica a existencia da permissao para detalhar documentos
if (array_key_exists('3102', Controlador::getInstance()->recurso->dependencias)) {
    ?>
                    // Alterar
                    $("<img/>", {
                        src: 'imagens/alterar.png',
                        title: 'Detalhar',
                        'class': 'botao30'
                    }).bind("click", function() {
                        jquery_detalhar_documento(aData[3], aData[12]);
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
                    visualizar_imagens_documento(aData[3]);
                }).appendTo($line);

//                $("<img/>", {
//                    src: 'imagens/checkbox_48x48.png',
//                    title: 'Qualificar Demanda',
//                    'class': 'botao30'
//                }).bind("click", function() {
//                    fnQualificarDemandaPPA(aData[3]);
//                }).appendTo($line);

                $("</div>").appendTo($line);
                return nRow;
            },
            fnDrawCallback: function(oSettings, nRow) {
            },
            aoColumnDefs: [
                {bSortable: false, aTargets: [0, 11, 12]},
            	{bVisible: false, aTargets: [12] }
            ]

        });

    });

</script>

<!--HTML dos Documentos da Área de Trabalho-->
<table class="display" border="0" id="TabelaDocumentos">
    <thead>
        <tr>
            <th class="column-checkbox"><input type="checkbox" id="marcadorD" onChange="marcar_todos_documentos(total_documentos);"></th>
            <th title="Anexos/Apensos" class="style13 column-checkbox"></th>
            <th title="Prazos" class="style13 column-checkbox"></th>
            <th class="style13 column-digital">Digital</th>
            <th class="style13 column-numero">Cadastro</th>
            <th class="style13 column-numero">Data Documento</th>
            <th class="style13 column-assunto">Assunto</th>
            <th class="style13 column-numero">Número</th>
            <th class="style13 column-tipo">Tipo</th>
            <th class="style13 column-origem">Origem</th>
            <th class="style13 column-movimentacao">Interessado</th>
            <th class="style13 column-opcao-5">Opções</th>
            <th></th>
        </tr>
    </thead>
</table>

<!--Estilo Personalizado-->
<style type="text/css">

    /*#div-form-detalhar-documentos{
        position: static;
        text-align: center;
        margin-top: 10px;
    }*/

    div .qualificacao_demanda_nav{
        left: 0;
        position: absolute;
        width: 200px;
        height: 600px;
    }
    div .qualificacao_demanda_search{
        left: 250px;
        position: absolute;
        width: 900px;
        text-align: left;
    }
    .dataTables_filter input {
        /*display: none;*/
    }

</style>

<!--HTML da Qualificação de Demanda-->
<div id="div-form-qualificar-demanda" class="div-form-dialog" >

    <div class="qualificacao_demanda_nav">
        <div class="row" >
            <div class="contextMenu" id="menu-tree-qualificados">
<!--		<li class="expandAll"><img alt="" src="plugins/tree/css/images/expand.png"/></li>
                <li class="collapseAll"><img alt="" src="plugins/tree/css/images/collapse.png"/></li>-->
            </div>
            <ul id="tree-qualificados" class="arvoreDocumentos">
                <li class='root root-ausente' id='1'><span title="Documento mais relevante da arvore">PPA</span>
                    <ul>
                        <li class='text' title='titulo' id='2'><span class=''>nofilho</span>
                            <ul class='ajax'>"
                                <li id='3'>valorurl</li>"
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    <!--Pesquisa-->
    <div class="qualificacao_demanda_search">
        <div class="row" >
            <label class="label">Demanda:</label>
            <label class="label">1233211</label>
        </div>
        <div class="row" >
            <label class="label">Texto do Prazo:</label>
            <label class="label">qualificacao_demanda_tx_prazo</label>
        </div>
        <div class="row" >
            <label class="label">Data do Prazo:</label>
            <label class="label">qualificacao_demanda_dt_prazo</label>
        </div>
        <div class="row" >
            <label class="label" for="qualificacao_demanda_pesquisa" >Pesquisa:</label>
            <span class="conteudo">
                <input type="text" id="qualificacao_demanda_pesquisa">
                <input type="button" value="Pesquisar" >
            </span>
        </div>

        <!-- Abas com todos os resultados da pesquisa -->
        <div id="qualificacao_abas_resultado">
            <ul>
                <li><a id="" title="" href="#div_programa_qualificacao_resultado_pesquisa">Programa</a></li>
                <li><a id="" title="" href="#div_objetivo_qualificacao_resultado_pesquisa">Objetivo</a></li>
                <li><a id="" title="" href="#div_meta_qualificacao_resultado_pesquisa">Meta</a></li>
                <li><a id="" title="" href="#div_iniciativa_qualificacao_resultado_pesquisa">Iniciativa</a></li>
            </ul>

            <div id="div_programa_qualificacao_resultado_pesquisa">
                <table class="display" border="0" id="tb_programa_qualificacao_resultado_pesquisa">
                    <thead>
                        <tr>
                            <th class="style13">Programa Campo 01</th>
                            <th class="style13">Programa Campo 02</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="div_objetivo_qualificacao_resultado_pesquisa">
                <table class="display" border="0" id="tb_objetivo_qualificacao_resultado_pesquisa">
                    <thead>
                        <tr>
                            <th class="style13">Objetivo Campo 01</th>
                            <th class="style13">Objetivo Campo 02</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="div_meta_qualificacao_resultado_pesquisa">
                <table class="display" border="0" id="tb_meta_qualificacao_resultado_pesquisa">
                    <thead>
                        <tr>
                            <th class="style13">Meta Campo 01</th>
                            <th class="style13">Meta Campo 02</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="div_iniciativa_qualificacao_resultado_pesquisa">
                <table class="display" border="0" id="tb_iniciativa_qualificacao_resultado_pesquisa">
                    <thead>
                        <tr>
                            <th class="style13">Iniciativa Campo 01</th>
                            <th class="style13">Iniciativa Campo 02</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>

    </div>

</div>

<!--Javascript da Qualificação de Demanda-->
<script type="text/javascript">

//var treeQualificados = null;

    $(document).ready(function() {

        $('#div-form-qualificar-demanda').dialog({
            title: 'Qualificar Demanda',
            bgiframe: true,
            position: 'center',
            draggable: false,
            resizable: false,
            width: $(window).width() - 20,
            height: $(window).height(),
            stack: true,
            zIndex: 99999,
            modal: true,
            autoOpen: false
        });

        $("#qualificacao_abas_resultado").tabs();

        oDataTableQualificacaoPrograma = $('#tb_programa_qualificacao_resultado_pesquisa').dataTable({
//	aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: true,
//	bProcessing: true,
//	bServerSide: true,
            bJQueryUI: true,
            sPaginationType: "full_numbers",
//	sAjaxSource: "modelos/demanda/pesquisa.php?acao=minha-caixa-de-demandas",
//	aoColumnDefs: [{ bSortable: false, aTargets: [10] }],
            oLanguage: {
                sProcessing: "Carregando...",
                sLengthMenu: "_MENU_ por página",
                sZeroRecords: "Nenhum programa encontrado.",
                sInfo: "_START_ a _END_ de _TOTAL_ programas.",
                sInfoEmpty: "Não foi possível localizar programas com os parâmetros informados!",
                sInfoFiltered: "(Total _MAX_ programas)",
                sInfoPostFix: "",
                sSearch: "",
                oPaginate: {
                    sFirst: "Primeiro",
                    sPrevious: "Anterior",
                    sNext: "Próximo",
                    sLast: "Último"
                }
            }
        });
        oDataTableQualificacaoObjetivo = $('#tb_objetivo_qualificacao_resultado_pesquisa').dataTable({
//	aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: true,
//	bProcessing: true,
//	bServerSide: true,
            bJQueryUI: true,
            sPaginationType: "full_numbers",
//	sAjaxSource: "modelos/demanda/pesquisa.php?acao=minha-caixa-de-demandas",
//	aoColumnDefs: [{ bSortable: false, aTargets: [10] }],
            oLanguage: {
                sProcessing: "Carregando...",
                sLengthMenu: "_MENU_ por página",
                sZeroRecords: "Nenhum objetivo encontrado.",
                sInfo: "_START_ a _END_ de _TOTAL_ objetivos.",
                sInfoEmpty: "Não foi possível localizar objetivos com os parâmetros informados!",
                sInfoFiltered: "(Total _MAX_ objetivos)",
                sInfoPostFix: "",
                sSearch: "",
                oPaginate: {
                    sFirst: "Primeiro",
                    sPrevious: "Anterior",
                    sNext: "Próximo",
                    sLast: "Último"
                }
            }
        });
        oDataTableQualificacaoMeta = $('#tb_meta_qualificacao_resultado_pesquisa').dataTable({
//	aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: true,
//	bProcessing: true,
//	bServerSide: true,
            bJQueryUI: true,
            sPaginationType: "full_numbers",
//	sAjaxSource: "modelos/demanda/pesquisa.php?acao=minha-caixa-de-demandas",
//	aoColumnDefs: [{ bSortable: false, aTargets: [10] }],
            oLanguage: {
                sProcessing: "Carregando...",
                sLengthMenu: "_MENU_ por página",
                sZeroRecords: "Nenhuma meta encontrada.",
                sInfo: "_START_ a _END_ de _TOTAL_ metas.",
                sInfoEmpty: "Não foi possível localizar metas com os parâmetros informados!",
                sInfoFiltered: "(Total _MAX_ metas)",
                sInfoPostFix: "",
                sSearch: "",
                oPaginate: {
                    sFirst: "Primeiro",
                    sPrevious: "Anterior",
                    sNext: "Próximo",
                    sLast: "Último"
                }
            }
        });
        oDataTableQualificacaoIniciativa = $('#tb_iniciativa_qualificacao_resultado_pesquisa').dataTable({
//	aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: true,
//	bProcessing: true,
//	bServerSide: true,
            bJQueryUI: true,
            sPaginationType: "full_numbers",
//	sAjaxSource: "modelos/demanda/pesquisa.php?acao=minha-caixa-de-demandas",
//	aoColumnDefs: [{ bSortable: false, aTargets: [10] }],
            oLanguage: {
                sProcessing: "Carregando...",
                sLengthMenu: "_MENU_ por página",
                sZeroRecords: "Nenhuma iniciativa encontrada.",
                sInfo: "_START_ a _END_ de _TOTAL_ iniciativa.",
                sInfoEmpty: "Não foi possível localizar iniciativas com os parâmetros informados!",
                sInfoFiltered: "(Total _MAX_ iniciativas)",
                sInfoPostFix: "",
                sSearch: "",
                oPaginate: {
                    sFirst: "Primeiro",
                    sPrevious: "Anterior",
                    sNext: "Próximo",
                    sLast: "Último"
                }
            }
        });

//    treeQualificados = new TreeOperations( $('#tree-qualificados'), '' );

        /*Arvore */
//    $('#tree-qualificados').simpleTree({
//	animate:true,
//	autoclose: false,
//	restoreTreeState: false,
//	afterClick:function(node){
////	    jquery_detalhar_documento($('span:first',node).text());
//	},
//	afterDblClick:function(node){
//	    //jquery_detalhar_documento($('span:first',node).text());
//	},
//	afterMove:function(destination, source, pos) {},
//	afterAjax:function(node){
//
//	    /*Documento Selecionado*/
//	    if(node.attr('idElemento')=='<?php print($digital); ?>'){
//		$('#'+node.attr('idElemento')).attr('class','folder-open-target');
//		$('#'+node.attr('idElemento')).attr('title','Documento selecionado');
//		if (node.html().length == 1) {
//		    $('#'+node.attr('idElemento')).attr('class','folder-close-target');
//		}
//	    }
//
//	    /*Documento ausente*/
//	    if(node.attr('stAusente')=='true'){
//		$('#'+node.attr('idElemento')).attr('class','folder-open-ausente');
//		$('#'+node.attr('idElemento')).attr('title','Este documento nao esta na sua area de trabalho');
//		if (node.html().length == 1) {
//		    $('#'+node.attr('idElemento')).attr('class','folder-close-ausente');
//		}
//	    }
//	}
//	,afterContextMenu: function(element, event){
//	    var className = element.attr('class');
//	    if (className.indexOf('root') >= 0) {
//		$('#menu-tree-qualificados .edit, #menu-tree-qualificados .delete').hide();
//		$('#menu-tree-qualificados .expandAll, #menu-tree-qualificados .collapseAll').show();
//	    }
//	    else {
//		$('#menu-tree-qualificados .expandAll, #menu-tree-qualificados .collapseAll').hide();
//	    }
//	    $('#menu-tree-qualificados').css('top',event.pageY).css('left',event.pageX).show();
//
//	    $('*').click(function() {
//		$('#menu-tree-qualificados').hide();
//	    });
//	}

        //});

        /*Carregar todos os vinculos*/
//    treeQualificados.expandAll($('#tree-qualificados > .root > ul'));
//
//    /*Menus*/
//    $('#menu-tree-qualificados .expandAll').click(function (){
//	treeQualificados.expandAll($('#tree-qualificados > .root > ul'));
//    });
//    $('#menu-tree-qualificados .collapseAll').click(function (){
//	treeQualificados.collapseAll();
//    });
//
//    $('#menu-tree-qualificados .expandAll').append(langManager.expandAll);
//    $('#menu-tree-qualificados .collapseAll').append(langManager.collapseAll);

    });

    function fnQualificarDemandaPPA(parDigital) {
//    Carrega informações de Prazo - Data do Prazo e Texto do Prazo

        $('#div-form-qualificar-demanda').dialog('open');
    }

</script>
