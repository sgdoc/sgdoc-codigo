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
/*Variaveis globais*/
$(document).ready(function(){

    /*Listeners*/
    /*Lista modelos termos*/
    $("#lista_modelo_termos").click(function() {

        /*Setar Processo*/
        $("#processo_abertura").val($('#NUMERO_DETALHAR_PROCESSO').val());
        $("#processo_apensar").val($('#NUMERO_DETALHAR_PROCESSO').val());
        $("#processo_desapensar").val($('#NUMERO_DETALHAR_PROCESSO').val());
        $("#processo_desentrenhar").val($('#NUMERO_DETALHAR_PROCESSO').val());
        $("#processo_desmembrar").val($('#NUMERO_DETALHAR_PROCESSO').val());
        $("#processo_desanexar").val($('#NUMERO_DETALHAR_PROCESSO').val());
   
        /*Accordion*/
        $("#lista-modelos-termos").accordion({
            autoHeight: false
        }).dialog({
            title: 'Modelo de termos',
            width: 620,
            autoHeight: true,
            resizable: false,
            modal : true,
            autoOpen: true,
            open: function() {
                $.post("modelos/processos/volume.php", {
                    action : "prepareOpen",
                    processo : $('#NUMERO_DETALHAR_PROCESSO').val()
                }, function(response){
                    if(response.success == 'true') {
                        if(response.action == "open") {
                            $("#volume_abertura").val(response.volume);
                            $("#folha_abertura").val(response.folha);
                        } else {
                            loadFormTermo(true);
                            $("#data_abertura").val(response.abertura);
                            $("#data_abertura").attr('disabled','disabled');

                            $("#volume_abertura").val(response.volume);
                            $("#folha_abertura").val(response.inicial);
                        }
                    }
                },"json");
            },
            close: function() {}
        });

    });

});

/*Funcoes*/
/*Carregar o formulario de termos de processos*/
function loadFormTermo(bool) {
    if(bool) {
        if($('#abertura-volume').find('input').attr('id', 'data_encerramento').length==0){
           
            $("#abertura-volume").append("<p><strong>* Encerramento: </strong><input type='text' id='data_encerramento' readonly /></p>");
            $("#abertura-volume").append("<p><strong>* Ultima folha: </strong><input type='text' maxlength = '10' id='folha_final_encerramento' onKeyUp='DigitaNumero(this)'; /></p>");
            $("#button-abertura").append("<button class='button-cadastrar-encerrar'>Encerrar volume</button>");
            
            $("#data_encerramento").datepicker({
                changeMonth: true,
                changeYear: true
            });
            
            $(".button-cadastrar-encerrar").button({
                icons : {
                    primary: 'ui-icon-circle-check'
                }
            }).click(function(){
                closeVolume();
            });
                            
            $(".button-cadastrar-encerrar").click(function(){
                closeVolume();
            });
            
            $("#folha_final_encerramento").click(function(){
                $(this).removeClass("error-input-value");
            });
            
            $(".button-cadastrar-abertura").remove();
        }    
    } else {
        $("#button-abertura").append("<button class='button-cadastrar-abertura'>Abrir volume</button>");
        $("#data_encerramento").remove();
        $("#folha_final_encerramento").remove();
    }
}

