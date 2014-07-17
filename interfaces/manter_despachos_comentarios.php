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
        </style>
        <script type="text/javascript">

            $(document).ready(function() {

                $("#tabs").tabs();
                $(".cabecalho-caixas").tabs();

                var oTableComentariosDocumentos = $('#tabela_comentarios_documentos').dataTable({
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
                        nRow.setAttribute('title', aData[1]);

                        var $line = $('td:eq(6)', nRow);
                        $line.html('');

                        if (aData[2] == '') {
                            $('td:eq(2)', nRow).html('<div title=""></div>');
                        }
                        $('td:eq(3)', nRow).html(convertDateToString(aData[3]));

                        var $bEditar = $("<img/>", {
                            src: 'imagens/alterar.png',
                            title: 'Editar',
                            'class': 'botao32'
                        }).bind("click", function() {
                            editar_comentario_documento(aData[0], iDisplayIndex);
                        }).appendTo($line);

                        var $bExcluir = $("<img/>", {
                            src: 'imagens/excluir.png',
                            title: 'Excluir',
                            'class': 'botao32'
                        }).bind("click", function() {
                            excluir_comentario_documento(aData[0], iDisplayIndex);
                        }).appendTo($line);

                        return nRow;
                    }
                });


                var oTableDespachosDocumentos = $('#tabela_despachos_documentos').dataTable({
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
                        nRow.setAttribute('title', aData[1]);

                        var $line = $('td:eq(8)', nRow);
                        $line.html('');

                        /* Complemento Em Branco */
                        if (aData[7] == '') {
                            $('td:eq(7)', nRow).html('Em Branco');
                        }
                        $('td:eq(2)', nRow).html(convertDateToString(aData[2]));
                        $('td:eq(4)', nRow).html(convertDateToString(aData[4]));

                        var $bEditar = $("<img/>", {
                            src: 'imagens/alterar.png',
                            title: 'Editar',
                            'class': 'botao32'
                        }).bind("click", function() {
                            editar_despacho_documento(aData[0], iDisplayIndex);
                        }).appendTo($line);

                        var $bExcluir = $("<img/>", {
                            src: 'imagens/excluir.png',
                            title: 'Excluir',
                            'class': 'botao32'
                        }).bind("click", function() {
                            excluir_despacho_documento(aData[0], iDisplayIndex);
                        }).appendTo($line);

                        return nRow;
                    }
                });

                var oTableComentariosProcessos = $('#tabela_comentarios_processos').dataTable({
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
                        nRow.setAttribute('title', aData[1]);

                        var $line = $('td:eq(6)', nRow);
                        $line.html('');

                        if (aData[2] == '') {
                            $('td:eq(2)', nRow).html('<div title=""></div>');
                        }
                        $('td:eq(3)', nRow).html(convertDateToString(aData[3]));

                        var $bEditar = $("<img/>", {
                            src: 'imagens/alterar.png',
                            title: 'Editar',
                            'class': 'botao32'
                        }).bind("click", function() {
                            editar_comentario_processo(aData[0], iDisplayIndex);
                        }).appendTo($line);

                        var $bExcluir = $("<img/>", {
                            src: 'imagens/excluir.png',
                            title: 'Excluir',
                            'class': 'botao32'
                        }).bind("click", function() {
                            excluir_comentario_processo(aData[0], iDisplayIndex);
                        }).appendTo($line);

                        return nRow;
                    }
                });

                var oTableDespachosProcessos = $('#tabela_despachos_processos').dataTable({
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
                        nRow.setAttribute('title', aData[1]);

                        var $line = $('td:eq(8)', nRow);
                        $line.html('');

                        /* Complemento Em Branco */
                        if (aData[7] == '') {
                            $('td:eq(7)', nRow).html('Em Branco');
                        }
                        $('td:eq(2)', nRow).html(convertDateToString(aData[2]));
                        $('td:eq(4)', nRow).html(convertDateToString(aData[4]));

                        var $bEditar = $("<img/>", {
                            src: 'imagens/alterar.png',
                            title: 'Editar',
                            'class': 'botao32'
                        }).bind("click", function() {
                            editar_despacho_processo(aData[0], iDisplayIndex);
                        }).appendTo($line);

                        var $bExcluir = $("<img/>", {
                            src: 'imagens/excluir.png',
                            title: 'Excluir',
                            'class': 'botao32'
                        }).bind("click", function() {
                            excluir_despacho_processo(aData[0], iDisplayIndex);
                        }).appendTo($line);

                        return nRow;
                    }
                });

                /*LISTENERS EDITAR COMENTARIOS E PROCESSOS*/

                $('#editar_comentario_documento').dialog({
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    buttons: {
                        Salvar: function() {
                            var c = confirm('Você tem certeza que deseja salvar este comentario?');
                            if (c) {
                                $.post("modelos/documentos/comentarios.php", {
                                    acao: 'alterar-comentario',
                                    id: $('#id_comentario_documento').val(),
                                    texto: $('#texto_comentario_documento').val()
                                },
                                function(data) {
                                    if (data.success == 'true') {
                                        $('#editar_comentario_documento').dialog("close");
                                        oTableComentariosDocumentos.fnUpdate($('#texto_comentario_documento').val(), $('#line_comentario_documento').val(), 4);
                                        alert(data.message);
                                    } else {
                                        alert('Ocorreu um erro ao tentar salvar o texto do comentario!');
                                    }
                                }, "json");
                            }
                        },
                        Cancelar: function() {
                            $(this).dialog("close");
                        }
                    }
                });

                $('#editar_despacho_documento').dialog({
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    buttons: {
                        Salvar: function() {
                            var c = confirm('Você tem certeza que deseja salvar este despacho?');
                            if (c) {
                                $.post("modelos/documentos/despachos.php", {
                                    acao: 'alterar-despacho',
                                    id: $('#id_despacho_documento').val(),
                                    texto: $('#texto_despacho_documento').val()
                                },
                                function(data) {
                                    if (data.success == 'true') {
                                        $('#editar_despacho_documento').dialog("close");
                                        oTableDespachosDocumentos.fnUpdate($('#texto_despacho_documento').val(), $('#line_despacho_documento').val(), 6);
                                    } else {
                                        alert('Ocorreu um erro ao tentar salvar o texto do despacho!');
                                    }
                                }, "json");
                            }
                        },
                        Cancelar: function() {
                            $(this).dialog("close");
                        }
                    }
                });

                $('#editar_comentario_processo').dialog({
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    buttons: {
                        Salvar: function() {
                            var c = confirm('Você tem certeza que deseja salvar este comentario?');
                            if (c) {
                                $.post("modelos/processos/comentarios.php", {
                                    acao: 'alterar-comentario',
                                    id: $('#id_comentario_processo').val(),
                                    texto: $('#texto_comentario_processo').val()
                                },
                                function(data) {
                                    if (data.success == 'true') {
                                        $('#editar_comentario_processo').dialog('close');
                                        oTableComentariosProcessos.fnUpdate($('#texto_comentario_processo').val(), $('#line_comentario_processo').val(), 4);
                                    } else {
                                        alert('Ocorreu um erro ao tentar salvar o texto do comentario!');
                                    }
                                }, "json");
                            }
                        },
                        Cancelar: function() {
                            $(this).dialog("close");
                        }
                    }
                });

                $('#editar_despacho_processo').dialog({
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    buttons: {
                        Salvar: function() {
                            var c = confirm('Você tem certeza que deseja salvar este despacho?');
                            if (c) {
                                $.post("modelos/processos/despachos.php", {
                                    acao: 'alterar-despacho',
                                    id: $('#id_despacho_processo').val(),
                                    texto: $('#texto_despacho_processo').val()
                                },
                                function(data) {
                                    if (data.success == 'true') {
                                        $('#editar_despacho_processo').dialog("close");
                                        oTableDespachosProcessos.fnUpdate($('#texto_despacho_processo').val(), $('#line_despacho_processo').val(), 6);
                                    } else {
                                        alert('Ocorreu um erro ao tentar salvar o texto do despacho!');
                                    }
                                }, "json");
                            }
                        },
                        Cancelar: function() {
                            $(this).dialog("close");
                        }
                    }
                });

                /*FUNCOES EDITAR*/

                function editar_comentario_documento(id, line) {
                    $.post("modelos/documentos/comentarios.php", {
                        acao: 'carregar-comentario',
                        id: id
                    },
                    function(data) {
                        if (data.success == 'true') {
                            if (data.texto) {
                                $('#texto_comentario_documento').val(data.texto);
                                $('#id_comentario_documento').val(id);
                                $('#line_comentario_documento').val(line);
                                $('#editar_comentario_documento').dialog('open');
                            } else {
                                alert('Ocorreu um erro ao tentar carregar o texto do comentario!');
                            }
                        } else {
                            alert(data.error);
                        }
                    }, "json");
                    return true;
                }

                function editar_despacho_documento(id, line) {
                    $.post("modelos/documentos/despachos.php", {
                        acao: 'carregar-despacho',
                        id: id
                    },
                    function(data) {
                        if (data.texto) {
                            $('#texto_despacho_documento').val(data.texto);
                            $('#id_despacho_documento').val(id);
                            $('#line_despacho_documento').val(line);
                            $('#editar_despacho_documento').dialog('open');
                        } else {
                            alert('Ocorreu um erro ao tentar carregar o texto do despacho!');
                        }
                    }, "json");
                    return false;
                }

                function editar_comentario_processo(id, line) {
                    $.post("modelos/processos/comentarios.php", {
                        acao: 'carregar-comentario',
                        id: id
                    },
                    function(data) {
                        if (data.texto) {
                            $('#texto_comentario_processo').val(data.texto);
                            $('#id_comentario_processo').val(id);
                            $('#line_comentario_processo').val(line);
                            $('#editar_comentario_processo').dialog('open');
                        } else {
                            alert('Ocorreu um erro ao tentar carregar o texto do comentario!');
                        }
                    }, "json");
                    return false;
                }

                function editar_despacho_processo(id, line) {
                    $.post("modelos/processos/despachos.php", {
                        acao: 'carregar-despacho',
                        id: id
                    },
                    function(data) {
                        if (data.texto) {
                            $('#texto_despacho_processo').val(data.texto);
                            $('#id_despacho_processo').val(id);
                            $('#line_despacho_processo').val(line);
                            $('#editar_despacho_processo').dialog('open');
                        } else {
                            alert('Ocorreu um erro ao tentar carregar o texto do despacho!');
                        }
                    }, "json");
                    return false;
                }

                /*FUNCOES EXCLUIR*/

                function excluir_comentario_documento(id, line) {
                    var c = confirm('Você tem certeza que deseja excluir este comentario?');
                    if (c) {
                        $.post("modelos/documentos/comentarios.php", {
                            acao: 'remover-comentario',
                            id: id
                        },
                        function(data) {
                            if (data.success == 'true') {
                                oTableComentariosDocumentos.fnDeleteRow(line);
                                alert(data.message)
                            } else {
                                alert('Ocorreu um erro ao tentar excluir o comentario!');
                            }
                        }, "json");
                    }
                    return true;
                }

                function excluir_despacho_documento(id, line) {
                    var c = confirm('Você tem certeza que deseja excluir este despacho?');
                    if (c) {
                        $.post("modelos/documentos/despachos.php", {
                            acao: 'remover-despacho',
                            id: id
                        },
                        function(data) {
                            if (data.success == 'true') {
                                oTableDespachosDocumentos.fnDeleteRow(line);
                                alert(data.message)
                            } else {
                                alert('Ocorreu um erro ao tentar excluir o despacho!');
                            }
                        }, "json");
                    }
                    return true;
                }


                function excluir_comentario_processo(id, line) {
                    var c = confirm('Você tem certeza que deseja excluir este comentario?');
                    if (c) {
                        $.post("modelos/processos/comentarios.php", {
                            acao: 'remover-comentario',
                            id: id
                        },
                        function(data) {
                            if (data.success == 'true') {
                                oTableComentariosProcessos.fnDeleteRow(line);
                                alert(data.message)
                            } else {
                                alert('Ocorreu um erro ao tentar excluir o comentario!');
                            }
                        }, "json");
                    }
                    return true;
                }

                function excluir_despacho_processo(id, line) {
                    var c = confirm('Você tem certeza que deseja excluir este despacho?');
                    if (c) {
                        $.post("modelos/processos/despachos.php", {
                            acao: 'remover-despacho',
                            id: id
                        },
                        function(data) {
                            if (data.success == 'true') {
                                oTableDespachosProcessos.fnDeleteRow(line);
                                alert(data.message)
                            } else {
                                alert('Ocorreu um erro ao tentar excluir o despacho!');
                            }
                        }, "json");
                    }
                    return true;
                }

            });
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
                <li><a title="Comentarios - Documentos" href="#tabs-1">Comentarios - Documentos</a></li>
                <li><a title="Despachos - Documentos" href="#tabs-2">Despachos - Documentos</a></li>
                <li><a title="Comentarios - Processos" href="#tabs-3">Comentarios - Processos</a></li>
                <li><a title="Despachos - Processos" href="#tabs-4">Despachos - Processos</a></li>
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
                            <th class="style13">Data</th>
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
                            <th class="style13">Data</th>
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

            <!-- CAIXA DE DIALOGO PARA ALTERACAO DE DADOS-->

            <div id="editar_comentario_documento" title="Editar Comentario Documento">
                <fieldset>
                    <label for="texto_comentario_documento">*Comentario:</label>
                    <textarea cols="1" rows="1"  id="texto_comentario_documento" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1" style="height: 200px; width: 100%;"></textarea>
                    <input type="hidden" id="id_comentario_documento"/>
                    <input type="hidden" id="line_comentario_documento"/>
                </fieldset>
            </div>

            <div id="editar_despacho_documento" title="Editar Despacho Documento">
                <fieldset>
                    <label for="texto_despacho_documento">*Despacho:</label>
                    <textarea cols="1" rows="1"  id="texto_despacho_documento"  onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1" style="height: 200px; width: 100%;"></textarea>
                    <input type="hidden" id="id_despacho_documento"/>
                    <input type="hidden" id="line_despacho_documento"/>
                </fieldset>
            </div>

            <div id="editar_comentario_processo" title="Editar Comentario Processo">
                <fieldset>
                    <label for="texto_comentario_processo">*Comentario:</label>
                    <textarea cols="1" rows="1"  id="texto_comentario_processo" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1" style="height: 200px; width: 100%;"></textarea>
                    <input type="hidden" id="id_comentario_processo"/>
                    <input type="hidden" id="line_comentario_processo"/>
                </fieldset>
            </div>

            <div id="editar_despacho_processo" title="Editar Despacho Processo">
                <fieldset>
                    <label for="texto_despacho_processo">*Despacho:</label>
                    <textarea cols="1" rows="1"  id="texto_despacho_processo" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1" style="height: 200px; width: 100%;"></textarea>
                    <input type="hidden" id="id_despacho_processo"/>
                    <input type="hidden" id="line_despacho_processo"/>
                </fieldset>
            </div>
        </div>
    </body>
</html>
