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
var iddesentrenhar = null;
var butdesen = false;

$(document).ready(function(){

    $( "#button-desentrenhar button:first" ).button({
        icons: {
            primary: 'ui-icon-circle-check'
        }
    }).next().button({
        icons: {
            primary:  'ui-icon-circle-close'
        }
    });
    
    $("#ajuda").dialog({
        title: "Como preencher o campo",
        autoOpen: false,
        width: 320,
        height:160,
        modal: true,
        open: function(){
            $('.ui-dialog-buttonpane').find('button:contains("Ok")').button({
                icons: {
                    primary: 'ui-icon-circle-check'
                }
            });
        },
        buttons:{
            "Ok":function(){
                $(this).dialog('close');
            }
        }
    });

    $(".button-help").click(function(){
        $("#ajuda").dialog('open');
    });
    
    $("#filtro-diretoria-desetrenhar").autocompleteonline({
        idComboBox: 'diretoria_desentrenhar',
        url: 'modelos/combos/autocomplete.php',
        extraParams: {
            action: 'unidades-internas',
            type: 'IN'
        }
       
    });

    $('.button-editar-desentrenhar').click(function(){
        $('#box-filtro-processo-desentrenhar').dialog('open');
        $('#diretoria_desentrenhar').removeClass("error-input-value");
    });

    $('#box-filtro-processo-desentrenhar').dialog({
        title: 'Filtro',
        autoOpen: false,
        resizable: false,
        modal: false,
        width: 380,
        height: 120
    });

    $(".button-cancelar-desentrenhar").click(function(){
        limparDesentrenhar();
    })

    $(".button-cadastrar-desentrenhar").click(function(){
        if( validForm()) {
            desen();
            limparDesentrenhar();
        }
    });

    $("#pecas_desentrenhar").keyup(function(){
        var vlr = $(this).val();
        vlr = vlr.replace( /[^0-9|,|-]/, "");
        $(this).val(vlr);
    });
    
    function limparDesentrenhar() {
        var campos = $([]).add("#diretoria_desentrenhar")
        .add("#pecas_desentrenhar")
        .add("#texto_justificativa_desentranhamento");
        campos.removeClass("error-input-value");
        campos.val("");
        iddesentrenhar = null;
        $("#diretoria_desentrenhar").empty();
    }
    function validForm() {
        var allFields = $([])
                .add("#processo_desentrenhar")
                .add("#diretoria_desentrenhar")
                .add("#pecas_desentrenhar")
                .add("#texto_justificativa_desentranhamento");
        var bool = false;
        $.each(allFields, function(index, data){
            if( $.trim($(data).val()) == "" || $(data).val() == null) {
                $(data).addClass("error-input-value");
                bool = true;
            }
        });

        if(bool) {
            alert("Atenção!, um ou mais campo em vermelho estão preenchido incorretamente!");
            return false;
        }
        return true;
    }

    function desen() {
        $.post("modelos/processos/inserir_desentranhamento.php", {
            acao : 'desen',
            processo : $("#processo_desentrenhar").val(),
            diretoria :$("#diretoria_desentrenhar").val(),
            peca : $("#pecas_desentrenhar").val(),
            justif : $("#texto_justificativa_desentranhamento").val(),
            iddesen: iddesentrenhar
        }, function(data) {
            try {
                if(data.success == 'true') {
                    $('#box-operacao-sucesso').dialog({
                        title: 'Imprimir termo?',
                        autoOpen: true,
                        resizable: false,
                        modal: true,
                        width: 350,
                        height: 150,
                        beforeClose: function() {
                            location.href = 'termo_desentranhamento.php?numero_processo=' + data.processo;
                        },
                        open: function(){
                            $('.ui-dialog-buttonpane').find('button:contains("Imprimir")').button({
                                icons: {
                                    primary: 'ui-icon-print'
                                }
                            });
                        },
                        buttons: {
                            "Imprimir": function(){
                                $(this).dialog('close');
                            }
                        }
                    });
                } else {
                    alert( data.message);
                }
            } catch(e) {
                alert("Atenção!, ocorreu um erro inesperado em sua solicitação, tente novamente mais tarde ou contate o administrador.");
            }
        }, "json");
    }

    $("#termo-desentrenhar").click(function(){
        if(!butdesen) {
            $.post("modelos/processos/inserir_desentranhamento.php",  {
                acao : 'verif',
                processo : $("#processo_desentrenhar").val()
            },
            function(data){
                if(data.success == 'true') {
                    $("#button-desentrenhar").append("<button id='edit-desen' onclick= 'editardesentrenhar();'>Visualizar termos anteriores</button>");
                    $( "#button-desentrenhar button:last" ).button({
                        icons: {
                            primary: 'ui-icon-wrench'
                        }
                    });
                }
            }, "json");
            butdesen = true;
        }
    })

});

function editardesentrenhar() {

    $('body').append("<div id='box-edit-desen'></div>");
    $("#box-edit-desen").dialog({
        title : "Editar termo desentranhamento",
        modal : true,
        width: 490,
        height: 200,
        autoOpen: true,
        open: function(){
            $.post('modelos/processos/inserir_desentranhamento.php', {
                acao : 'lista',
                processo :  $("#processo_desentrenhar").val()
            }, function(data){
                if(data.success == 'true') {
                    $("#box-edit-desen").append("<b>Ultimos termos gerados:</b><br><br>");
                    $("#box-edit-desen").append("<table>");

                    $.each(data.lista, function(index, data) {
                        ++index;
                        $("#box-edit-desen").append("<tr height='25' onclick='loadedit(" + data.id + ");'><td width='20'>"+ (index) + "</td><td width='150'>" + data.usuario +  "</td><td width='150'><b><a href='#'>"+ data.processo +"</a></b></td><td>" + data.data + "</td></tr>");
                    });
                    
                    $("#box-edit-desen").append("</table>");
                }
            },"json");
        },
        close: function() {
            $("#box-edit-desen").remove();
        }
        
    });

}

function loadedit(index) {
    $("#box-edit-desen").dialog('close');
    $.post('modelos/processos/inserir_desentranhamento.php', {
        iddesen : index,
        acao : 'loadedit'
    }, function(data){
        if(data.success == 'true') {
            $("#diretoria_desentrenhar").empty();
            $("#diretoria_desentrenhar").append("<option value='" + data.termo.idsolicitante + "' >" + data.termo.solicitante + "</option>");
            $("#pecas_desentrenhar").val(data.termo.pecas)
            $("#texto_justificativa_desentranhamento").val(data.termo.justificativa)
            butdesen = index;
        }
    }, "json");
}