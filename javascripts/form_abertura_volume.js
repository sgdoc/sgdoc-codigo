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
$(document).ready(function(){
    
    $( "#button-abertura button:first" ).button({
        icons: {
            primary: 'ui-icon-circle-check'
        }
    });

    $("#data_abertura").datepicker({
        changeMonth: true,
        changeYear: true
    });

    $("#data_abertura").click(function(){
        $(this).removeClass("error-input-value")
    });
    
    $("#data_encerramento").live('click',function(){
        $("#data_encerramento").removeClass("error-input-value");
    });

    $("#folha_final_encerramento").keyup(function(){
        var vlr = $(this).val();
        vlr = vlr.replace( /[^0-9]/, "");
        $(this).val(vlr);
    });

    $(".button-cadastrar-abertura").click(function() {
        openVolume();
    });
    
    
});
    
/**
*Funcoes
*/
function openVolume() {
    if(validAbertura()) {
        $.post("modelos/processos/volume.php", {
            action : "openVolume",
            processo : $("#processo_abertura").val(),
            pedido_abertura : $("#data_abertura").val()
        }, function(response){
            if(response.success == 'true') {
                $('#box-operacao-sucesso').dialog({
                    title: 'Imprimir termo?',
                    autoOpen: true,
                    resizable: false,
                    modal: true,
                    width: 350,
                    height: 150,
                    beforeClose: function() {
                    },
                    close: function(){
                        prepareOpen();
                    },
                    open: function(){
                        $('.ui-dialog-buttonpane').find('button:contains("Imprimir")').button({
                            icons: {
                                primary: 'ui-icon-print'
                            }
                        });
                    },
                    buttons: {
                        Imprimir: function(){
                            location.href = 'termo_abertura_volume.php?numero_processo=' + response.processo;
                            $(this).dialog('close');
                        }
                    }
                });
            }else{
                alert(response.message);
            }
    
        },"json");
    }
}

function validAbertura() {
    var bool = true;
    var campos = $([])
    .add($("#processo_abertura"))
    .add($("#data_abertura"))
    .add($("#volume_abertura"))
    .add($("#folha_abertura"));
    $.each(campos, function(index, data){
        if($(data).val() == "") {
            $(data).addClass("error-input-value");
            bool = false;
        }
    });
    if(!bool) {
        alert("Atencao!, um ou mais campo em vermelho estão preenchidos incorretamente!");
        return false;
    }
      
    return true;
}
   
function closeVolume() {
    if(validEncerrar()) {
        $.post("modelos/processos/volume.php", {
            action : "closeVolume",
            processo : $("#processo_abertura").val(),
            data_encerrar : $("#data_encerramento").val(),
            volume : $("#volume_abertura").val(),
            folha_final : $("#folha_final_encerramento").val()
        }, function(response){
            if(response.success == 'true') {
                $('#box-operacao-sucesso').dialog({
                    title: 'Imprimir termo?',
                    autoOpen: true,
                    resizable: false,
                    modal: true,
                    width: 350,
                    height: 150,
                    beforeClose: function() {                            
                            
                    },
                    close: function(){
                        prepareOpen();
                    },
                    open: function(){
                        $('.ui-dialog-buttonpane').find('button:contains("Imprimir")').button({
                            icons: {
                                primary: 'ui-icon-print'
                            }
                        });
                    },
                    buttons: {
                        Imprimir: function(){
                            location.href = 'termo_encerramento_volume.php?numero_processo=' + response.processo;
                            $(this).dialog('close');
                        }
                    }
                });
            }else{
                alert('Não foi possível encerrar o volume pelo seguinte motivo:\n['+response.message+']');
            }

        },"json");
    }
}

function validEncerrar() {
    var bool = true;
    var campos = $([])
    .add($("#processo_abertura"))
    .add($("#data_abertura"))
    .add($("#volume_abertura"))
    .add($("#folha_abertura"))
    .add($("#data_encerramento"))
    .add($("#folha_final_encerramento"));

    $.each(campos, function(index, data){
        if($(data).val() == "") {
            $(data).addClass("error-input-value");
            bool = false;
        }
    });
    if(!bool) {
        alert("Atencao!, um ou mais campo em vermelho estao preenchido incorretamente!");
        return false;
    }
    var abertura = $.datepicker.parseDate('dd/mm/yy',$("#data_abertura").val());
    var encerramento = $.datepicker.parseDate('dd/mm/yy',$("#data_encerramento").val());
    if(abertura > encerramento) {
        alert("Atencao!\nA data de encerramento deve ser posterior à data de abertura!");
        $("#data_encerramento").addClass("error-input-value");
        return false;
    } else {
        $("#data_encerramento").removeClass("error-input-value");
    }
    if(parseInt($("#folha_final_encerramento").val()) <= parseInt($("#folha_abertura").val())) {
        alert("Atencao!\nA última folha não pode ser menor que a folha inicial!");
        $("#folha_final_encerramento").addClass("error-input-value");
        return false;
    }

    return true;
}

function prepareOpen() {

    $.post("modelos/processos/volume.php", {
        action : "prepareOpen",
        processo : numero_processo
    }, function(response){
        if(response.success == 'true') {
            if(response.action == "open") {
                $("#volume_abertura").val(response.volume);
                $("#folha_abertura").val(response.folha);
                loadForm(false);
            } else {
                loadForm(true);
                $("#data_abertura").val(response.abertura);
                $("#data_abertura").attr('disabled','disabled');

                $("#volume_abertura").val(response.volume);
                $("#folha_abertura").val(response.inicial);
            }
        }
    },"json");
}

function loadForm(bool) {
    if(bool) {
        $("#abertura-volume").append("<div class='row'><label for='data_encerramento'>* Encerramento:</label><div class='conteudo'><input type='text' id='data_encerramento' readonly class='FUNDOCAIXA1' /></div></div>");
        $("#abertura-volume").append("<div class='row'><label for='folha_final_encerramento'>* Ultima folha:</label><div class='conteudo'><input type='text' maxlength='10' id='folha_final_encerramento' onKeyUp='DigitaNumero(this);' class='FUNDOCAIXA1' /></div></div>");
        $("#button-abertura").append("<button class='button-cadastrar-encerrar'>Encerrar volume</button>");
        
        $(".button-cadastrar-abertura").remove();

        $("#data_encerramento").datepicker({
            changeMonth: true,
            changeYear: true
        });

        $("#data_encerramento").click(function(){
            $(this).removeClass("error-input-value")
        });

        $("#folha_final_encerramento").click(function(){
            $(this).removeClass("error-input-value");
        });
            
        $("#folha_final_encerramento").keyup(function(){
            var vlr = $(this).val();
            vlr = vlr.replace( /[^0-9]/, "");
            $(this).val(vlr);
        });

        $(".button-cadastrar-encerrar").button({
            icons : {
                primary: 'ui-icon-circle-check'
            }
        }).unbind('click').click(function(){
            closeVolume();
        });
            
    } else {
        $("#abertura-volume").html("");
        $(".button-cadastrar-encerrar").remove();
        $("#data_abertura").removeAttr('disabled').val("");
        $("#button-abertura").append("<button class='button-cadastrar-abertura'>Abrir volume</button>")
        $(".button-cadastrar-abertura").button({
            icons : {
                primary: 'ui-icon-circle-check'
            }
        }).unbind('click').click(function(){
            openVolume();
        });
    }
}