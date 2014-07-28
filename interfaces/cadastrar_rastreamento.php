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

<!--ValidarDigitalCadastrada-->
<!--InserirHistoricoTramite-->

<script type="text/javascript">
            
    $(document).ready(function(){
               
        $('#div-form-cadastrar-rastreamento-documentos').dialog({
            title: 'Novo Rastreamento',
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 650,
            height: 200,
            buttons: {
                Salvar: function() {
                    
                    if($('#DIGITAL_CADASTRAR_RASTREAMENTO').val().length != 7 || 
                        $('#CODIGO_CADASTRAR_RASTREAMENTO').val().length != 13 || 
                        $('#SERVICO_CADASTRAR_RASTREAMENTO').val() == 0){
                        return false;
                    }
                    
                    $("#progressbar").show();
                    
                    $.post('modelos/documentos/rastreamento.php',{
                        digital:$('#DIGITAL_CADASTRAR_RASTREAMENTO').val(),
                        codigo:$('#CODIGO_CADASTRAR_RASTREAMENTO').val(),
                        servico:$('#SERVICO_CADASTRAR_RASTREAMENTO').val()
                    },function(response){
                        if(response.status=='success'){
                            $('#DIGITAL_CADASTRAR_RASTREAMENTO').val('');
                            $('#CODIGO_CADASTRAR_RASTREAMENTO').val('');
                            $('#SERVICO_CADASTRAR_RASTREAMENTO').val(0);
                        }
                        alert(response.message);
                    },'json');
                  
                    $("#progressbar").hide();
                },
                Cancelar: function() {
                    $('#DIGITAL_CADASTRAR_RASTREAMENTO').val('');
                    $('#CODIGO_CADASTRAR_RASTREAMENTO').val('');
                    $('#SERVICO_CADASTRAR_RASTREAMENTO').val(0);
                    $(this).dialog('close');
                }
            }
        });
        /*Ativar filtros*/
        $('#botao-form-cadastrar-rastreamento-documentos').click(function(){
            $('#div-form-cadastrar-rastreamento-documentos').dialog('open');
        });
  
    });

</script>

<!--Formulario-->
<div class="div-form-dialog" id="div-form-cadastrar-rastreamento-documentos">

    <div class="row">
        <label class="label">*DIGITAL:</label>
        <span class="conteudo">
            <input class='FUNDOCAIXA1' type="text" id="DIGITAL_CADASTRAR_RASTREAMENTO" maxlength="7" onKeyPress="DigitaNumero(this);">
        </span>
    </div>

    <div class="row">
        <label class="label">*CODIGO:</label>
        <span class="conteudo">
            <input class='FUNDOCAIXA1' type="text" id='CODIGO_CADASTRAR_RASTREAMENTO' maxlength="13">
        </span>
    </div>

    <div class="row">
        <label class="label">*SERVICO:</label>
        <span class="conteudo">
            <select class="FUNDOCAIXA1" id="SERVICO_CADASTRAR_RASTREAMENTO">
                <option value="0" selected>---- Escolha um Servi&ccedil;o ----</option>
                <option value="CARTA REGISTRADA">CARTA REGISTRADA</option>
                <option value="CARTA SIMPLES">CARTA SIMPLES</option>
                <option value="IMPRESSO ESPECIAL">IMPRESSO ESPECIAL</option>
                <option value="IMPRESSO ESPECIAL LOCAL">IMPRESSO ESPECIAL LOCAL</option>
                <option value="SEDEX 10">SEDEX 10</option>
                <option value="SEDEX HOJE">SEDEX HOJE</option>
                <option value="SEDEX CONVENCIONAL">SEDEX CONVENCIONAL</option>
                <option value="DOCUMENTO ECONOMICO">DOCUMENTO ECONOMICO</option>
                <option value="DOCUMENTO EXPRESSO EMS">DOCUMENTO EXPRESSO EMS</option>
                <option value="DOCUMENTO PRATICO">DOCUMENTO PRATICO</option>
                <option value="EMS MERCADORIA">EMS MERCADORIA</option>
                <option value="LEVE ECONOMICO">LEVE ECONOMICO</option>
                <option value="LEVE PRIORITARIO">LEVE PRIORITARIO</option>
                <option value="MERCADORIA ECONOMICA">MERCADORIA ECONOMICA</option>
            </select>
        </span>
    </div>

</div>