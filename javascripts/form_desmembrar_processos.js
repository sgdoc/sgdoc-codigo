;/*
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
var iddesmembrar = null;
var butdesmem = false;

$(document).ready(function() {

    $("#button-desmembrar button:first").button({
        icons: {
            primary: 'ui-icon-circle-check'
        }
    }).next().button({
        icons: {
            primary: 'ui-icon-circle-close'
        }
    });


    $("#filtro-diretoria-desmembrar").autocompleteonline({
        idComboBox: 'diretoria_desmembrar',
        url: 'modelos/combos/autocomplete.php',
        extraParams: {
            action: 'unidades-internas',
            type: 'IN'
        }
    });

    $('.button-editar-desmembrar').click(function() {
        $('#diretoria_desmembrar').removeClass("error-input-value");
    });


    $(".button-cancelar-desmembrar").click(function() {
        cancelarDesmembrar();
    })

    $(".button-cadastrar-desmembrar").click(function() {
        if (validFormDesmembrar()) {
            desmembrar();
            cancelarDesmembrar();
        }
    });

    $("#pecas-desmembrar").keyup(function() {
        var vlr = $(this).val();
        vlr = vlr.replace(/[^0-9|,|-]/, "");
        $(this).val(vlr);
    });

    function cancelarDesmembrar() {
        var campos = $([]).add("#pecas-desmembrar").add("#diretoria_desmembrar").add("#novo_processo_desmembrar");
        campos.removeClass("error-input-value");
        campos.val("");
        iddesmembrar = null;
        $("#diretoria_desmembrar").empty();
    }

    function validFormDesmembrar() {
        var allFields = $([]).add("#pecas-desmembrar").add("#diretoria_desmembrar").add("#novo_processo_desmembrar").add("#processo_desmembrar");
        var bool = false;

        $.each(allFields, function(index, data) {
            if ($(data).val() == "" || $(data).val() == null) {
                $(data).addClass("error-input-value");
                bool = true;
            }
        });

        if (!validar_digito_verificador_processo(document.getElementById('novo_processo_desmembrar'))) {
            bool = true;
            $("#novo_processo_desmembrar").addClass("error-input-value");
            alert('O número do processo inválido!');
        }

        if (bool) {
            alert("Atenção! Um ou mais campo em vermelho estão preenchidos incorretamente!");
            return false;
        }
        return true;
    }

    function desmembrar() {
        $.post("modelos/processos/inserir_desmembramento.php", {
            acao: 'desmem',
            processo: $("#processo_desmembrar").val(),
            diretoria: $("#diretoria_desmembrar").val(),
            peca: $("#pecas-desmembrar").val(),
            nprocesso: $("#novo_processo_desmembrar").val(),
            iddesmembrar: iddesmembrar
        }, function(data) {
            try {
                if (data.success == 'true') {
                    $('#box-operacao-sucesso').dialog({
                        title: 'Imprimir termo?',
                        autoOpen: true,
                        resizable: false,
                        modal: true,
                        width: 350,
                        height: 150,
                        beforeClose: function() {
                        },
                        open: function() {
                            $('.ui-dialog-buttonpane').find('button:contains("Imprimir")').button({
                                icons: {
                                    primary: 'ui-icon-print'
                                }
                            });
                        },
                        buttons: {
                            Imprimir: function() {
                                location.href = 'termo_desmembramento.php?numero_processo=' + data.processo;
                                $(this).dialog('close');
                            }
                        }
                    });
                } else {
                    alert(data.message);
                }
            } catch (e) {
                alert("Atenção!, Ocorreu um erro inesperado em sua solicitação, tente novamente mais tarde ou contate o administrador.");
            }
        }, "json");
    }

    $("#termo-desmembrar").click(function() {
        if (!butdesmem) {
            $.post("modelos/processos/inserir_desentranhamento.php", {
                acao: 'verif',
                processo: $("#processo_desmembrar").val()
            },
            function(data) {
                if (data.success == 'true') {
                    $("#button-desmembrar").append("<button id='edit-desmem' onclick= 'editdesmem();'>Visualizar termos anteriores</button>");
                    $("#button-desmembrar button:last").button({
                        icons: {
                            primary: 'ui-icon-wrench'
                        }
                    });
                }
            }, "json");
            butdesmem = true;
        }
    })

});

/*Funcoes*/
function editdesmem() {
    $('body').append("<div id='box-edit-desen'></div>");

    $("#box-edit-desen").dialog({
        title: "Editar termo desmembramento",
        modal: true,
        width: 490,
        height: 200,
        autoOpen: true,
        open: function() {
            $.post('modelos/processos/inserir_desmembramento.php', {
                acao: 'lista',
                processo: $("#processo_desmembrar").val()
            }, function(data) {
                if (data.success == 'true') {
                    $("#box-edit-desen").append("<b>Ultimos termos gerados:</b><br><br>");
                    $("#box-edit-desen").append("<table>");
                    ++index;
                    $.each(data.lista, function(index, data) {
                        $("#box-edit-desen").append("<tr height='25' onclick='loadeditDesmem(" + data.id + ");'><td width='20'>" + (index) + "</td><td width='150'>" + data.usuario + "</td><td width='150'><b><a href='#'>" + data.processo + "</a></b></td><td>" + data.data + "</td></tr>");
                    });

                    $("#box-edit-desen").append("</table>");
                }
            }, "json");
        },
        close: function() {
            $("#box-edit-desen").remove();
        }

    });
}

function loadeditDesmem(index) {

    $("#box-edit-desen").dialog('close');
    $.post('modelos/processos/inserir_desmembramento.php', {
        iddesmembrar: index,
        acao: 'loadedit'
    }, function(data) {
        if (data.success == 'true') {
            $("#diretoria_desmembrar").empty();
            $("#diretoria_desmembrar").append("<option value='" + data.termo.idsolicitante + "' >" + data.termo.solicitante + "</option>");
            $("#pecas-desmembrar").val(data.termo.pecas)
            $("#novo_processo_desmembrar").val(data.termo.nprocesso)
            iddesmembrar = index;
        }
    }, "json");
}