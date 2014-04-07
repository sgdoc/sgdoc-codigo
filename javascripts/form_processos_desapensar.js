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
var count = 0;
var processos = new Array();
var procremov = new Array();
var processo;

$(document).ready(function() {
    $("#button-desapensar button:first").button({
        icons: {
            primary: 'ui-icon-circle-check'
        }
    }).next().button({
        icons: {
            primary: 'ui-icon-circle-close'
        }
    });

    $("#data_desapensar").datepicker({
        changeMonth: true,
        changeYear: true
    });

    $(".button-cadastrar-desapensar").click(function() {
        if (checkForm()) {
            if (procremov.length == 0) {
                alert("Atenção! Nenhum processo foi selecionado para desapensação. Clique no botão remover campo, para que o processo seja desapensado.");
            } else {
                var conf = confirm("Deseja desapensar o(s) processo(s) deste processo?");
                if (conf) {
                    desapensarProcessos()
                }
            }
        }
    })

    $("#termo-desapensar").click(function() {
        $("body").append("<div id='box-filtro-processo-desapensar'></div>")
        /* limpar campos para garantir a atualizacao dos processos */
        clearProc()
        $.get("modelos/processos/inserir_vincular_processos.php", {
            acao: "vinculados",
            numero_processo: $("#processo_desapensar").val()
        },
        function(data) {
            try {
                loadData(data);
            } catch (e) {
                alert('Atenção!, ocorreu um erro inesperado em sua solicitação, tente novamente mais tarde ou contate o administrador.');
            }
        }, "json");
    });


    $("#filtro-diretoria-desapensar").autocompleteonline({
        idComboBox: 'diretoria_desapensar',
        url: 'modelos/combos/autocomplete.php',
        extraParams: {
            action: 'unidades-internas',
            type: 'IN'
        }

    });

    $('#box-filtro-processo-desapensar').dialog({
        title: 'Filtro',
        autoOpen: false,
        resizable: false,
        modal: false,
        width: 380,
        height: 120
    });




});

function checkForm() {
    var campos = $([]).add($('#diretoria_desapensar'))
            .add($('#data_desapensar'));

    var bool = true;
    $.each(campos, function(index, data) {
        if (data.value == "") {
            data.setAttribute("class", "error-input-value");
            bool = false;
        }
    });

    if (!bool) {
        alert("Atenção!, um ou mais campo em vermelho estao preenchido incorretamente!");
        return false;
    }
    return bool;
}

function loadData(data) {
    if (data != null) {
        $.each(data, function(index, data) {
            processos.push(data);
            $("#desapensar-processos").append("<p><label></label><input type='text' maxlength = '20' readonly disabled value=" + data + " id='processo_d_" + count + "'/><img title='Remover campo' class='button-delete-desapensar' id = 'bprocesso_d_" + count + "' onclick='removProc(" + count + "); 'src='imagens/fam/delete.png'></p>");
            count++;
        });
    }
}

function clearProc() {
    if (count != 0) {
        for (var j = 0; j < count; j++) {
            processos.splice(j, 1);
            $("#processo_d_" + j).remove();
            $("#bprocesso_d_" + j).remove();
        }
    }
    count = 0;
}

function removProc(i) {
    for (var j = 0; j < count; j++) {
        if ($("#processo_d_" + i).val() == processos[j]) {
            procremov.push(processos[j]);
            processos.splice(j, 1);
        }
    }
    $("#processo_d_" + i).remove();
    $("#bprocesso_d_" + i).remove();
}


function desapensarProcessos() {
    $.post('modelos/processos/inserir_vincular_processos.php', {
        acao: 'desapensar',
        numero_processo: $('#processo_desapensar').val(),
        data_exclusao: $('#data_desapensar').val(),
        diretoria: $('#diretoria_desapensar').val(),
        processos: procremov.toString()
    },
    function(data) {
        try {
            if (data.success == 'true') {
                processo = data.processo;
                procremov = new Array();
                $("#diretoria_d").empty();
                $('#dpedido_d').val("");
                $('#box-operacao-sucesso').dialog({
                    title: 'Imprimir termo?',
                    autoOpen: true,
                    resizable: false,
                    modal: true,
                    width: 350,
                    height: 150,
                    beforeClose: function() {
                        clearFields();
                    },
                    close: function() {

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
                            location.href = 'termo_juntada_desapensacao.php?numero_processo=' + processo;
                            $(this).dialog('close');

                        }
                    }
                });

            } else {
                alert(data.message);
            }

        } catch (e) {
            alert('Atenção!, ocorreu um erro inesperado em sua solicitação, tente novamente mais tarde ou contate o administrador.');
        }
    }, "json");
}