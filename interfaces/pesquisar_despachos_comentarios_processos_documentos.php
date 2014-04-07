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
include("detalhar_documentos.php");
include("detalhar_processos.php");
$controller = Controlador::getInstance();
?>

<html>
    <head>
        <script type="text/javascript"  src="plugins/datatable/media/js/jquery.dataTables.js"></script>
        <style type="text/css" title="currentStyle">
            @import "plugins/datatable/media/css/demo_table_tabs.css";
        </style>
        <script type="text/javascript">

            var oTableComentariosDocumentos;
            var oTableDespachosDocumentos;
            var oTableComentariosProcessos;
            var oTableDespachosProcessos;

            $(document).ready(function() {

                $("#tabs").tabs();
                $("#tabs-pesquisar").tabs();
                $(".cabecalho-caixas").tabs();

                oTableComentariosDocumentos = $('#tabela_comentarios_documentos').dataTable({
                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    bStateSave: false,
                    bPaginate: true,
                    bProcessing: true,
                    bServerSide: true,
                    bJQueryUI: true,
                    sPaginationType: "full_numbers",
                    aaSorting: [[3, "desc"]],
                    sAjaxSource: "modelos/administrador/listar_comentarios_despachos.php?usercase=comentarios-documentos",
                    aoColumnDefs: [{bSortable: false, aTargets: [6]}],
                    oLanguage: {
                        sProcessing: "Carregando...",
                        sLengthMenu: "_MENU_ por página",
                        sZeroRecords: "Nenhum comentário encontrado.",
                        sInfo: "_START_ a _END_ de _TOTAL_ comentarios.",
                        sInfoEmpty: "Nao foi possivel localizar comentarios com os parametros informados!",
                        sInfoFiltered: "(Total _MAX_ comentarios)",
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
                        var $line = $('td:eq(6)', nRow);
                        $line.html('');

                        if (aData[2] == '') {
                            $('td:eq(2)', nRow).html('<div title=""></div>');
                        }
                        $('td:eq(3)', nRow).html(convertDateToString(aData[3]));

                        $("<img/>", {
                            src: 'imagens/alterar.png',
                            title: 'Detalhar',
                            'class': 'botao32'
                        }).bind("click", function() {
                            jquery_detalhar_documento(aData[1]);
                        }).appendTo($line);
                        return nRow;
                    }
                });


                oTableDespachosDocumentos = $('#tabela_despachos_documentos').dataTable({
                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    bStateSave: false,
                    bPaginate: true,
                    bProcessing: true,
                    bServerSide: true,
                    bJQueryUI: true,
                    sPaginationType: "full_numbers",
                    aaSorting: [[2, "desc"]],
                    "aoColumns": [
                        null,
                        null,
                        {"asSorting": ["desc", "asc"]},
                        null,
                        {"asSorting": ["desc", "asc"]},
                        null,
                        null,
                        null,
                        null
                    ],
                    sAjaxSource: "modelos/administrador/listar_comentarios_despachos.php?usercase=despachos-documentos",
                    aoColumnDefs: [{bSortable: false, aTargets: [8]}],
                    oLanguage: {
                        sProcessing: "Carregando...",
                        sLengthMenu: "_MENU_ por página",
                        sZeroRecords: "Nenhum despacho encontrado.",
                        sInfo: "_START_ a _END_ de _TOTAL_ despachos.",
                        sInfoEmpty: "Não foi possível localizar despachos com os parâmetros informados!",
                        sInfoFiltered: "(Total _MAX_ despachos)",
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
                        var $line = $('td:eq(8)', nRow);
                        $line.html('');

                        /* Complemento Em Branco */
                        if (aData[7] == '') {
                            $('td:eq(7)', nRow).html('Em Branco');
                        }
                        $('td:eq(2)', nRow).html(convertDateToString(aData[2]));
                        $('td:eq(4)', nRow).html(convertDateToString(aData[4]));

                        $("<img/>", {
                            src: 'imagens/alterar.png',
                            title: 'Detalhar',
                            'class': 'botao32'
                        }).bind("click", function() {
                            jquery_detalhar_documento(aData[1]);
                        }).appendTo($line);
                        return nRow;
                    }
                });

                oTableComentariosProcessos = $('#tabela_comentarios_processos').dataTable({
                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    bStateSave: false,
                    bPaginate: true,
                    bProcessing: true,
                    bServerSide: true,
                    bJQueryUI: true,
                    sPaginationType: "full_numbers",
                    aaSorting: [[3, "desc"]],
                    sAjaxSource: "modelos/administrador/listar_comentarios_despachos.php?usercase=comentarios-processos",
                    aoColumnDefs: [{bSortable: false, aTargets: [6]}],
                    oLanguage: {
                        sProcessing: "Carregando...",
                        sLengthMenu: "_MENU_ por página",
                        sZeroRecords: "Nenhum comentário encontrado.",
                        sInfo: "_START_ a _END_ de _TOTAL_ comentarios.",
                        sInfoEmpty: "Nao foi possivel localizar comentarios com os parametros informados!",
                        sInfoFiltered: "(Total _MAX_ comentarios)",
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
                        var $line = $('td:eq(6)', nRow);
                        $line.html('');

                        if (aData[2] == '') {
                            $('td:eq(2)', nRow).html('<div title=""></div>');
                        }
                        $('td:eq(3)', nRow).html(convertDateToString(aData[3]));

                        $("<img/>", {
                            src: 'imagens/alterar.png',
                            title: 'Detalhar',
                            'class': 'botao32'
                        }).bind("click", function() {
                            jquery_detalhar_processo(aData[1]);
                        }).appendTo($line);
                        return nRow;
                    }
                });

                oTableDespachosProcessos = $('#tabela_despachos_processos').dataTable({
                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    bStateSave: false,
                    bPaginate: true,
                    bProcessing: true,
                    bServerSide: true,
                    bJQueryUI: true,
                    sPaginationType: "full_numbers",
                    aaSorting: [[2, "desc"]],
                    "aoColumns": [
                        null,
                        null,
                        {"asSorting": ["desc", "asc"]},
                        null,
                        {"asSorting": ["desc", "asc"]},
                        null,
                        null,
                        null,
                        null
                    ],
                    sAjaxSource: "modelos/administrador/listar_comentarios_despachos.php?usercase=despachos-processos",
                    aoColumnDefs: [{bSortable: false, aTargets: [8]}],
                    oLanguage: {
                        sProcessing: "Carregando...",
                        sLengthMenu: "_MENU_ por página",
                        sZeroRecords: "Nenhum despacho encontrado.",
                        sInfo: "_START_ a _END_ de _TOTAL_ despachos",
                        sInfoEmpty: "Não foi possível localizar despachos com os parâmetros informados!",
                        sInfoFiltered: "(Total _MAX_ despachos)",
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
                        var $line = $('td:eq(8)', nRow);
                        $line.html('');

                        /* Complemento Em Branco */
                        if (aData[7] == '') {
                            $('td:eq(7)', nRow).html('Em Branco');
                        }
                        $('td:eq(2)', nRow).html(convertDateToString(aData[2]));
                        $('td:eq(4)', nRow).html(convertDateToString(aData[4]));

                        $("<img/>", {
                            src: 'imagens/alterar.png',
                            title: 'Detalhar',
                            'class': 'botao32'
                        }).bind("click", function() {
                            jquery_detalhar_processo(aData[1]);
                        }).appendTo($line);
                        return nRow;
                    }
                });

                /*Dialog*/
                /*Filtrar Despachos e Comentarios*/
                $('#box-filtrar-despachos-comentarios').dialog({
                    title: 'Filtrar pesquisa',
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    width: 700,
                    close: function() {
                    },
                    buttons: {
                        Pesquisar: function() {
                            jquery_pesquisar_comentarios_despachos();
                        }
                    }
                });
                /*Listeners*/
                /*Filtrar*/
                $('#botao-pesquisa-avancada-comentarios-despachos').click(function() {
                    $('#box-filtrar-despachos-comentarios').dialog('open');
                });

                $('#tipo-operacao').val('COMENTARIO');

                $('#link-comentario').click(function() {
                    $('#tipo-operacao').val('COMENTARIO');
                });

                $('#link-despacho').click(function() {
                    $('#tipo-operacao').val('DESPACHO');
                });

                /*Filtro Unidades Comentários*/
                $('#box-filtro-unidade-pesquisar-comentarios').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 90,
                    open: function() {
                        $("#FILTRO_UNIDADE_PESQUISAR_COMENTARIOS").val('');
                    }
                });

                /*Filtro Unidade*/
                $('#botao-filtro-unidade-pesquisar-comentarios').click(function() {
                    $('#box-filtro-unidade-pesquisar-comentarios').dialog('open');
                });

                /*Combo Unidades*/
                $("#FILTRO_UNIDADE_PESQUISAR_COMENTARIOS").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox: 'DIRETORIA_COMENTARIO_PESQUISAR',
                    extraParams: {
                        action: 'unidades-internas',
                        type: 'IN'
                    }
                });

                /*Filtro Unidades Despachos*/
                $('#box-filtro-unidade-pesquisar-despachos').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 90,
                    open: function() {
                        $("#FILTRO_UNIDADE_PESQUISAR_DESPACHOS").val('');
                    }
                });

                /*Filtro Unidade*/
                $('#botao-filtro-unidade-pesquisar-despachos').click(function() {
                    $('#box-filtro-unidade-pesquisar-despachos').dialog('open');
                });

                /*Combo Unidades*/
                $("#FILTRO_UNIDADE_PESQUISAR_DESPACHOS").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox: 'DIRETORIA_DESPACHO_PESQUISAR',
                    extraParams: {
                        action: 'unidades-internas',
                        type: 'IN'
                    }
                });
            });

            /*Funcoes*/
            function jquery_pesquisar_comentarios_despachos() {
                if ($('#tipo-operacao').val() == 'COMENTARIO') {
                    $.post('modelos/administrador/pesquisar_comentarios_despachos.php', {
                        operacao: $('#tipo-operacao').val(),
                        tipo: $('#TIPO_COMENTARIO_PESQUISAR').val(),
                        NUMERO: $('#NUMERO_COMENTARIO_PESQUISAR').val(),
                        USUARIO: $('#USUARIO_COMENTARIO_PESQUISAR').val(),
                        DATA_CADASTRO: $('#DATA_COMENTARIO_PESQUISAR').val(),
                        TEXTO_COMENTARIO: $('#COMENTARIO_COMENTARIO_PESQUISAR').val(),
                        ID_UNIDADE: $('#DIRETORIA_COMENTARIO_PESQUISAR').val()
                    },
                    function(data) {
                        if (data.success == 'true') {
                            if ($('#TIPO_COMENTARIO_PESQUISAR').val() == 'DOCUMENTO') {
                                $('#link-comentarios-documentos').click();
                                oTableComentariosDocumentos.fnDraw(false);
                            } else {
                                $('#link-comentarios-processos').click();
                                oTableComentariosProcessos.fnDraw(false);
                            }
                            $('#box-filtrar-despachos-comentarios').dialog('close');
                        } else {
                            alert('Ocorreu um erro ao tentar efetuar a buscar!\[' + data.error + ']');
                        }
                    }, 'json');
                } else {
                    $.post('modelos/administrador/pesquisar_comentarios_despachos.php', {
                        operacao: $('#tipo-operacao').val(),
                        tipo: $('#TIPO_DESPACHO_PESQUISAR').val(),
                        NUMERO: $('#NUMERO_DESPACHO_PESQUISAR').val(),
                        USUARIO: $('#USUARIO_DESPACHO_PESQUISAR').val(),
                        DATA_CADASTRO: $('#DATA_CADASTRO_DESPACHO_PESQUISAR').val(),
                        TEXTO_DESPACHO: $('#DESPACHO_DESPACHO_PESQUISAR').val(),
                        DATA_DESPACHO: $('#DATA_DESPACHO_PESQUISAR').val(),
                        ID_UNIDADE: $('#DIRETORIA_DESPACHO_PESQUISAR').val(),
                        COMPLEMENTO: $('#COMPLEMENTO_DESPACHO_PESQUISAR').val(),
                        ASSINATURA_DESPACHO: $('#ASSINATURA_DESPACHO_PESQUISAR').val()
                    },
                    function(data) {
                        if (data.success == 'true') {
                            if ($('#TIPO_DESPACHO_PESQUISAR').val() == 'DOCUMENTO') {
                                $('#link-despachos-documentos').click();
                                oTableDespachosDocumentos.fnDraw(false);
                            } else {
                                $('#link-despachos-processos').click();
                                oTableDespachosProcessos.fnDraw(false);
                            }
                            $('#box-filtrar-despachos-comentarios').dialog('close');
                        } else {
                            alert('Ocorreu um erro ao tentar efetuar a buscar!\[' + data.error + ']');
                        }
                    }, 'json');
                }

            }
        </script>
    </head>
    <body>
        <div class="cabecalho-caixas">
            <div class="logo-manter-despachos-comentarios"></div>
            <div class="titulo-manter-despachos-comentarios">Despachos e Comentários</div>
            <div class="menu-auxiliar">
                <?php Util::montaMenus($controller->botoes, array('class' => 'botao32')); ?>
            </div>
        </div>
        <div id="tabs">
            <ul>
                <li><a id="link-comentarios-documentos" title="Comentarios - Documentos" href="#tabs-1">Comentarios - Documentos</a></li>
                <li><a id="link-despachos-documentos" title="Despachos - Documentos" href="#tabs-2">Despachos - Documentos</a></li>
                <li><a id="link-comentarios-processos" title="Comentarios - Processos" href="#tabs-3">Comentarios - Processos</a></li>
                <li><a id="link-despachos-processos" title="Despachos - Processos" href="#tabs-4">Despachos - Processos</a></li>
            </ul>
            <div id="tabs-1">
                <table class="display" border="0" id="tabela_comentarios_documentos">
                    <thead>
                        <tr>
                            <th class="style13">#</th>
                            <th class="style13">Digital</th>
                            <th class="style13">Usuario</th>
                            <th class="style13">Data Cadastro</th>
                            <th class="style13">Comentario</th>
                            <th class="style13">Diretoria</th>
                            <th class="style13">Opções</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="tabs-2">
                <table class="display" border="0" id="tabela_despachos_documentos">
                    <thead>
                        <tr class="tabela_arredondada">
                            <th class="style13">#</th>
                            <th class="style13">Digital</th>
                            <th class="style13">Data Cadastro</th>
                            <th class="style13">Usuario</th>
                            <th class="style13">Data Despacho</th>
                            <th class="style13">Assinatura</th>
                            <th class="style13">Despacho</th>
                            <th class="style13">Complemento</th>
                            <th class="style13">Opções</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="tabs-3">
                <table class="display" border="0" id="tabela_comentarios_processos">
                    <thead>
                        <tr>
                            <th class="style13">#</th>
                            <th class="style13">Processo</th>
                            <th class="style13">Usuario</th>
                            <th class="style13">Data Cadastro</th>
                            <th class="style13">Comentario</th>
                            <th class="style13">Diretoria</th>
                            <th class="style13">Opções</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="tabs-4">
                <table class="display" border="0" id="tabela_despachos_processos">
                    <thead>
                        <tr>
                            <th class="style13">#</th>
                            <th class="style13">Processo</th>
                            <th class="style13">Data Cadastro</th>
                            <th class="style13">Usuario</th>
                            <th class="style13">Data Despacho</th>
                            <th class="style13">Assinatura</th>
                            <th class="style13">Despacho</th>
                            <th class="style13">Complemento</th>
                            <th class="style13">Opções</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div id="box-filtrar-despachos-comentarios">
            <div id="tabs-pesquisar">
                <ul>
                    <li><a id="link-comentario" title="Filtrar Comentarios" href="#tab-pesquisar-comentarios">Filtrar Comentarios</a></li>
                    <li><a id="link-despacho" title="Filtrar Despachos" href="#tab-pesquisar-despachos">Filtrar Despachos</a></li>
                </ul>
                <div id="tab-pesquisar-comentarios" class="div-form-dialog">
                    <div class="row">
                        <input type="hidden" id="tipo-operacao">
                        <label class="label">TIPO:</label>
                        <span class="conteudo">
                            <select class="FUNDOCAIXA1" id='TIPO_COMENTARIO_PESQUISAR'>
                                <option value="DOCUMENTO">DOCUMENTO</option>
                                <option value="PROCESSO">PROCESSO</option>
                            </select>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">DIGITAL OU PROCESSO:</label>
                        <span class="conteudo">
                            <input type="text" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='NUMERO_COMENTARIO_PESQUISAR'>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">USUARIO:</label>
                        <span class="conteudo">
                            <input type="text" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='USUARIO_COMENTARIO_PESQUISAR'>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">DATA DO CADASTRO:</label>
                        <span class="conteudo">
                            <input type="text" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='DATA_COMENTARIO_PESQUISAR'>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">COMENTARIO:</label>
                        <span class="conteudo">
                            <input type="text" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='COMENTARIO_COMENTARIO_PESQUISAR'>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">UNIDADE:</label>
                        <span class="conteudo">
                            <select class="FUNDOCAIXA1" id="DIRETORIA_COMENTARIO_PESQUISAR"></select>
                        </span>
                        <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-unidade-pesquisar-comentarios" src="imagens/fam/application_edit.png">
                    </div>
                </div>
                <div id="tab-pesquisar-despachos" class="div-form-dialog">
                    <div class="row">
                        <label class="label">TIPO:</label>
                        <span class="conteudo">
                            <select class="FUNDOCAIXA1" id='TIPO_DESPACHO_PESQUISAR'>
                                <option value="DOCUMENTO">DOCUMENTO</option>
                                <option value="PROCESSO">PROCESSO</option>
                            </select>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">DIGITAL OU PROCESSO:</label>
                        <span class="conteudo">
                            <input type="text" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='NUMERO_DESPACHO_PESQUISAR'>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">DATA DO CADASTRO:</label>
                        <span class="conteudo">
                            <input type="text" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='DATA_CADASTRO_DESPACHO_PESQUISAR'>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">USUARIO:</label>
                        <span class="conteudo">
                            <input type="text" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='USUARIO_DESPACHO_PESQUISAR'>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">DATA DO DESPACHO:</label>
                        <span class="conteudo">
                            <input type="text" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='DATA_DESPACHO_PESQUISAR'>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">ASSINATURA:</label>
                        <span class="conteudo">
                            <input type="text" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='ASSINATURA_DESPACHO_PESQUISAR'>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">DESPACHO:</label>
                        <span class="conteudo">
                            <input type="text" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='DESPACHO_DESPACHO_PESQUISAR'>
                        </span>
                    </div>
                    <div class="row">
                        <label class="label">UNIDADE:</label>
                        <span class="conteudo">
                            <select class="FUNDOCAIXA1" id="DIRETORIA_DESPACHO_PESQUISAR"></select>
                        </span>
                        <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-unidade-pesquisar-despachos" src="imagens/fam/application_edit.png">
                    </div>
                    <div class="row">
                        <label class="label">COMPLEMENTO:</label>
                        <span class="conteudo">
                            <input type="text" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='COMPLEMENTO_DESPACHO_PESQUISAR'>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div id="box-filtro-unidade-pesquisar-comentarios" class="box-filtro">
            <div class="row">
                <label>Unidade:</label>
                <div class="conteudo">
                    <input type="text" id="FILTRO_UNIDADE_PESQUISAR_COMENTARIOS" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>

        <div id="box-filtro-unidade-pesquisar-despachos" class="box-filtro">
            <div class="row">
                <label>Unidade:</label>
                <div class="conteudo">
                    <input type="text" id="FILTRO_UNIDADE_PESQUISAR_DESPACHOS" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>
    </body>
</html>
