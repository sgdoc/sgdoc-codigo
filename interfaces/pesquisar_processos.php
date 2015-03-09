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
$processo  = ($_GET['acao'] == 'processo')  ? 'true' : 'false';
?>
<script type="text/javascript">
var aberta2 = <?php echo $processo; ?>;

$(document).ready(function(){

    /*Acoordion*/
    var icons = {
        header: "ui-icon-circle-arrow-e",
        headerSelected: "ui-icon-circle-arrow-s"
    };
    $( "#accordion-pesquisar-processos" ).accordion({
        autoHeight: false,
        navigation: true,
        icons: icons
    });

    /*Calentarios */
    var datesOther = $( "#P_DT_AUTUACAO, #P_DT_PRAZO, #P_DT_CADASTRO" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1
    });
    var datesInicialFinal = $( "#P_DT_INICIAL, #P_DT_FINAL" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        onSelect: function( selectedDate ) {
            var option = this.id == "P_DT_INICIAL" ? "minDate" : "maxDate",
            instance = $( this ).data( "datepicker" ),
            date = $.datepicker.parseDate( 
                instance.settings.dateFormat || $.datepicker._defaults.dateFormat,
                selectedDate, 
                instance.settings );
            datesInicialFinal.not( this ).datepicker( "option", option, date );
        }
    });

    /*Botao limpar*/
    $('#botao-limpar-data-autuacao-pesquisar-processos').click(function(){
        $('#P_DT_AUTUACAO').val('');
    });
    $('#botao-limpar-data-prazo-pesquisar-processos').click(function(){
        $('#P_DT_PRAZO').val('');
    });
    $('#botao-limpar-data-cadastro-pesquisar-processos').click(function(){
        $('#P_DT_CADASTRO').val('');
    });
    $('#botao-limpar-data-inicial-pesquisar-processos').click(function(){
        $('#P_DT_INICIAL').val('');
    });
    $('#botao-limpar-data-final-pesquisar-processos').click(function(){
        $('#P_DT_FINAL').val('');
    });
    $('#botao-pesquisar-processos').click(function() {
        $('#div-form-pesquisar-processos').dialog('open');
    });

    /*Dialog*/
    $('#div-form-pesquisar-processos').dialog({
        title: 'Pesquisar Processo',
        autoOpen: aberta2,
        resizable: false,
        modal: true,
        width: 660,
        autoHeight: true,
        // height: 450,
        buttons: {
            Limpar:function(){
                $(this).find('input').val('');
            },
            Pesquisar: function() {
                pressSearchButtonProcessos();
            }

        }
    });            

    // Pesquisa ao pressionar ENTER
    $('#div-form-pesquisar-processos > div > div > div > span > input').each(function(){
        $(this).keydown(function(event){
            if(event.which == 13){
                pressSearchButtonProcessos();
            }
        });
    });
});

function pressSearchButtonProcessos()
{
    if($('#P_NUMERO_PROCESSO').val() != "" ||
        $('#P_ORIGEM').val()  != "" ||
        $('#P_INTERESSADO').val() != "" ||
        $('#P_ASSUNTO').val() != "" ||
        $('#P_ASSUNTO_COMPLEMENTAR').val() != "" ||
        $('#P_DT_AUTUACAO').val() != "" ||
        $('#P_DT_CADASTRO').val() != "" ||
        $('#P_DIGITAL').val() != "" ||
        $('#P_DT_INICIAL').val() != "" ||
        $('#P_DT_FINAL').val() != ""
    ){
        jquery_pesquisar_processos();

    }else{
        alert('Preencha pelo Menos um Campo para Pesquisa!');
    }
}


/*Funcoes*/
function jquery_pesquisar_processos()
{
    $.post("modelos/processos/processos.php", {
        acao: 'pesquisar',
        numero_processo: $('#P_NUMERO_PROCESSO').val(),
        ds_origem: $('#P_ORIGEM').val(),
        nm_interessado: $('#P_INTERESSADO').val(),
        nm_assunto: $('#P_ASSUNTO').val(),
        assunto_complementar: $('#P_ASSUNTO_COMPLEMENTAR').val(),
        dt_autuacao: jquery_replace_data($('#P_DT_AUTUACAO').val()),
        dt_prazo: jquery_replace_data($('#P_DT_PRAZO').val()),
        dt_cadastro: jquery_replace_data($('#P_DT_CADASTRO').val()),
        digital: $('#P_DIGITAL').val(),
        dt_inicial: jquery_replace_data($('#P_DT_INICIAL').val()),
        dt_final: jquery_replace_data($('#P_DT_FINAL').val()),
        tp_periodo: $('#P_TP_PERIODO').val(),
        tp_pesquisa: $('#TP_PESQUISA_PROCESSO').val()
    },
    function(data){
        try{
            if(data.success == 'true'){
                window.location = "lista_pesquisa_processos.php";
            }else{
                alert(data.error);
            }
        }catch(e){
            alert('Ocorreu um erro ao tentar pesquisar o processo!\n['+e+']');
        }
    },"json");
}

</script>
<div class="div-form-dialog" id="div-form-pesquisar-processos">
    <div id="accordion-pesquisar-processos">
        <h3><a href="#">Informacoes basicas</a></h3>
        <div>
            <div class="row">
                <label class="label">NUMERO PROCESSO:</label>
                <span class="conteudo">
                    <input type="text" id="P_NUMERO_PROCESSO" class="FUNDOCAIXA1">
                </span>
            </div>
            <div class="row">
                <label class="label">ASSUNTO:</label>
                <span class="conteudo">
                    <input type="text" id="P_ASSUNTO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </span>
            </div>
            <div class="row">
                <label class="label">ASSUNTO COMPLEMENTAR:</label>
                <span class="conteudo">
                    <input type="text" id="P_ASSUNTO_COMPLEMENTAR" class="FUNDOCAIXA1" onKeyUp="DigitaLetraSeguro(this)" >
                </span>
            </div>
            <div class="row">
                <label class="label">INTERESSADO:</label>
                <span class="conteudo">
                    <input type="text" id="P_INTERESSADO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </span>
            </div>
            <div class="row">
                <label class="label">ORIGEM:</label>
                <span class="conteudo">
                    <input type="text" id="P_ORIGEM" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </span>
            </div>
            <div class="row">
                <label class="label">DIGITAL ABERTURA:</label>
                <span class="conteudo">
                    <input type="text" maxlength="7" onkeyup="DigitaNumeroSeguro(this);" id="P_DIGITAL" class="FUNDOCAIXA1">
                </span>
            </div>
        </div>
        <h3><a href="#">Datas</a></h3>
        <div>
            <div class="row">
                <label class="label">DATA AUTUACAO: </label>
                <span class="conteudo">
                    <input type="text" maxlength="10" id="P_DT_AUTUACAO" class="FUNDOCAIXA1">
                </span>
                <img alt=""  title="Limpar" class="botao-auxiliar" src="imagens/fam/delete.png" id="botao-limpar-data-autuacao-pesquisar-processos">
            </div>
            <div class="row">
                <label class="label">DATA PRAZO:</label>
                <span class="conteudo">
                    <input type="text" maxlength="10" id="P_DT_PRAZO" class="FUNDOCAIXA1">
                </span>
                <img alt=""  title="Limpar" class="botao-auxiliar" src="imagens/fam/delete.png" id="botao-limpar-data-prazo-pesquisar-processos">
            </div>
            <div class="row">
                <label class="label">DATA CADASTRO:</label>
                <span class="conteudo">
                    <input type="text" maxlength="10" id="P_DT_CADASTRO" class="FUNDOCAIXA1">
                </span>
                <img alt=""  title="Limpar" class="botao-auxiliar" src="imagens/fam/delete.png" id="botao-limpar-data-cadastro-pesquisar-processos">
            </div>
            <div class="row">
                <label class="label">BUSCAR PERIODO POR:</label>
                <span class="conteudo">
                    <select id="P_TP_PERIODO" class="FUNDOCAIXA1">
                        <option value="DT_AUTUACAO">DATA DA AUTUACAO</option>
                        <option value="DT_CADASTRO">DATA DO CADASTRO</option>
                        <option value="DT_PRAZO">DATA DO PRAZO</option>
                    </select>
                </span>
            </div>
            <div class="row">
                <label class="label">DATA INICIAL:</label>
                <span class="conteudo">
                    <input type="text" maxlength="10" id="P_DT_INICIAL" class="FUNDOCAIXA1">
                </span>
                <img alt=""  title="Limpar" class="botao-auxiliar" src="imagens/fam/delete.png" id="botao-limpar-data-inicial-pesquisar-processos">
            </div>
            <div class="row">
                <label class="label">DATA FINAL:</label>
                <span class="conteudo">
                    <input type="text" maxlength="10" id="P_DT_FINAL" class="FUNDOCAIXA1">
                </span>
                <img alt=""  title="Limpar" class="botao-auxiliar" src="imagens/fam/delete.png" id="botao-limpar-data-final-pesquisar-processos">
            </div>
        </div>
        
        <h3><a href="#">Tipo de Pesquisa</a></h3>
        <div>
            <div style="text-align: left;">                
            Escolha o tipo de pesquisa que deseja utilizar na sua busca:
            </div> <br />
            <div class="row">
                <label class="label">TIPO DE PESQUISA:</label>
                <span class="conteudo">
                    <select id="TP_PESQUISA_PROCESSO" class="FUNDOCAIXA1">
                        <option value="FTS">Por palavra (palavra completa)</option>
                        <option value="OTHER">Por fragmento (parte da palavra)</option>
                    </select>
                </span>
            </div>
            <br />
            <div style="text-align: left;">                
            OBSERVAÇÃO: Caso a palavra não esteja escrita corretamente no registro,
            não será possível sua localização
            </div> 
        </div>
                
        
    </div>
</div>