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
var indice = 0;
var processo;
var anexos_apensar = new Array();

/* controlar margin-top div buttoes*/
var divbutton = 0;
var divpanel = 290;
/***********************************/

$(document).ready(function() {

    $("#div-form-apensar button:first").button({
        icons: {
            primary: 'ui-icon-circle-check'
        }
    }).next().button({
        icons: {
            primary: 'ui-icon-circle-close'
        }
    });

    $("input").focus(function() {
        $(this).removeClass("error-input-value");
    });

    $("select").focus(function() {
        $(this).removeClass("error-input-value");
    });

    $("#data_apensar").datepicker({
        changeMonth: true,
        changeYear: true
    });

    $(".button-adicionar-apensar").click(function() {
        if ($("#processo_" + indice).val() == "") {
            alert("Atenção! Preencha o campo vazio para inserir um novo processo.")
            $("#processo_" + indice).focus();

        } else {
            indice++;
            if (indice >= 1) {
                divbutton += 35;
                divpanel += 35;
                $("#button-apensar").css("margin-top", divbutton + "px");
                $("#div-form-apensar").css("height", divpanel + "px");
            }
            $("#apensar-processos").append("<div class='row'><label></label><div class='conteudo'><input type='text' maxlength='20' onKeyUp='formatar_numero_processo(this)' class='FUNDOCAIXA1' id='processo_" + indice + "' /><img title='Remover campo' class='button-deletar-processo' id='button_delete_" + indice + "' onclick='remov(" + indice + "); 'src='imagens/fam/delete.png' /></div></div>");
            $("#proceso_" + indice).focus();
        }
    });


    $(".button-cadastrar-apensar").click(function() {
        if (checkFields()) {
            if (loadInput(true)) {
                var conf = confirm("Você tem certeza que deseja " + $('#tip_vincular').val() + " o(s) processo(s) informados ao processo escolhido?");
                if (conf) {
                    apensarProcessos()
                }
            }
        } else {
            alert("Atenção! Um ou mais campo em vermelho estão preenchido incorretamente!");
        }
    });

    $(".button-cancelar-apensar").click(function() {
        clearFields();
    });

    $("#filtro-diretoria-apensar").autocompleteonline({
        idComboBox: 'diretoria_apensar',
        url: 'modelos/combos/autocomplete.php',
        extraParams: {
            action: 'unidades-internas',
            type: 'IN'
        }
    });

    $('#box-filtro-processo-apensar').dialog({
        title: 'Filtro',
        autoOpen: false,
        resizable: false,
        modal: false,
        width: 380,
        height: 120
    });

    $('#botao-filtrar-diretoria-apensar').click(function() {
        $('#box-filtro-processo-apensar').dialog('open');
        $('#diretoria').removeClass("error-input-value");
    });

});

function showResul() {
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
                location.href = 'termo_juntada_apensacao_anexacao.php?numero_processo=' + processo;
                $(this).dialog('close');
            }
        }
    });
}

function checkFields() {
    var campos = $([]).add($('#processo_apensar'))
            .add($('#data_apensar'))
            .add($('#diretoria_apensar'))
            .add($("#processo_0"));

    var bool = true;
    $.each(campos, function(index, data) {
        if (data.value == "") {
            data.setAttribute("class", "error-input-value");
            bool = false;
        }
    });
    return bool;
}

function remov(i) {
    loadInput(false);
    for (var j = 0; j < anexos_apensar.length; j++) {
        if ($("#processo_" + i).val() == anexos_apensar[j]) {
            anexos_apensar.splice(j, 1);
        }
    }
    $("#processo_" + i).remove();
    $("#button_delete_" + i).remove();
}

function apensarProcessos() {
    $.post("modelos/processos/inserir_vincular_processos.php", {
        acao: $("#tip_vincular").val(),
        numero_processo: $('#processo_apensar').val(),
        data_cadastro: $('#data_apensar').val(),
        diretoria: $('#diretoria_apensar').val(),
        processos: anexos_apensar.toString()
    },
    function(data) {
        try {
            if (data.success == 'false') {
                /*Destacar os processos com erro*/
                $.each(data, function(index, value) {
                    for (var j = 0; j <= indice; j++) {
                        if ($("#processo_" + j).val() == value) {
                            $("#processo_" + j).addClass("error-input-value");
                        }
                    }
                });
                alert(data.message);
            } else {
                processo = data.processo;
                showResul();
            }
        } catch (e) {
            alert('Atenção! Ocorreu um erro inesperado em sua solicitação, tente novamente mais tarde ou contate o administrador.\n[' + e + ']');
        }
    }, "json");
}

function loadInput(message) {
    var temp = new Array();
    var bool = false;
    var error = '';

    for (var j = 0; j <= indice; j++) {
        if ($("#processo_" + j).val()) {/*previnir referencia invalida para javacritp puro!*/
            if (validar_digito_verificador_processo(document.getElementById("processo_" + j))) {
                if ($("#processo_" + j).val() != $('#processo_apensar').val()) {
                    temp.push($("#processo_" + j).val());
                    $("#processo_" + j).removeClass("error-input-value");
                } else {
                    error = 'Você nao pode apensar um processo a ele mesmo!';
                    bool = true;
                    $("#processo_" + j).addClass("error-input-value");
                }
            } else {
                error = 'Numero invalido';
                bool = true;
                $("#processo_" + j).addClass("error-input-value");
            }
        }
    }

    anexos_apensar = temp;
    if (bool) {
        if (message) {
            alert("Atenção! Um ou mais campo em vermelho estão preenchido incorretamente!\nErro: " + error);
        }
        return false;
    }
    return true;
}

function clearFields() {

    var campos = $([]).add($('#data_apensar'))
            .add($('#diretoria_apensar'))
            .add($('#filtro-diretoria-apensar'))
            .add($("#processo_0"));

    campos.removeClass("error-input-value");
    campos.val("");
    $("#diretoria_apensar").empty();

    for (var j = 1; j <= indice; j++) {
        $("#processo_" + j).remove();
        $("#button_delete_" + j).remove();
    }
    indice = 0;
}

//function formatar_numero_processo(e) {
//    var s = FiltraCampo(e.value);
//    var tam =  s.length;
//    var ano_dig;
//
//    if (tam>15){
//        ano_dig = 4;
//    }else{
//        ano_dig = 2;
//    }
//
//    var r = s.substring(0,5) + "." + s.substring(5,11) + "/";
//    r+= s.substring(11,11+ano_dig)  + "-" + s.substring(11+ano_dig,13+ano_dig);
//
//    if (tam<6){
//        s = r.substring(0,tam);
//    }else if ( tam < 12 ){
//        s = r.substring(0,tam+1);
//    }else if ( tam < 12 + ano_dig ){
//        s = r.substring(0,tam+2);
//    }else{
//        s = r.substring(0,tam+3);
//    }
//    e.value = s;
//    return s;
//}
//
//function validar_digito_verificador_processo(e) {
//    var s = FiltraCampo(e);
//    var tam = s.length;
//
//    if ( tam == 15 ) {
//        var num = s.substring(0,tam-2);
//        for ( i = 0; i < 2; i++ ) {
//            var soma = 0;
//            var mult = num.length + 1;
//            //            for ( k = 0; k < num.length ; k++ )
//            soma += num.substring(k,k+1)*(mult-k);
//            var mod11 = 11 - (soma % 11);
//            if ( mod11 < 10 )  dv_proc="0"+mod11;
//            else  dv_proc = mod11 + "";
//            var dv_proc = dv_proc.substring(1,2);
//            num+= dv_proc;
//        }
//
//        if (num==s){
//            return true;
//        } else {
//            return false;
//        }
//    }
//
//    if (tam==17) {
//        num = s.substring(0,tam-2);
//        for ( i = 0; i < 2; i++ ) {
//            soma = 0;
//            mult = num.length + 1;
//            for ( k = 0; k < num.length ; k++ ){
//                soma += num.substring(k,k+1)*(mult-k);
//                mod11 = 11 - (soma % 11);
//                if ( mod11 < 10 ){
//                    dv_proc="0"+mod11;
//                }else{
//                    dv_proc = mod11 + "";
//                }
//            }
//            dv_proc = dv_proc.substring(1,2);
//            num+= dv_proc;
//        }
//        //var dig_v = num.substr(13,16);
//
//        if (num == s){
//            return true;
//        }
//    }
//    return false;
//}
