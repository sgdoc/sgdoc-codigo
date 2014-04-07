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
<script type="text/javascript">
    $(document).ready(function() {
        /*Calendario*/
        $('#DATA_DESPACHO_PROCESSO').datepicker();

        /*Botao novo despacho*/
        $('#botao-novo-despacho-processo').click(function(){
            $('#form-novo-despacho-processo').dialog('open');
        });

        /*Dialog - Novo Despacho*/
        $('#form-novo-despacho-processo').dialog({
            title: 'Novo Despacho',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 300,
            autoHeight: true,
            close:function(){
                $('#TEXTO_DESPACHO_PROCESSO').val('');
                $('#DATA_DESPACHO_PROCESSO').val('');
                $('#COMPLEMENTO_DESPACHO_PROCESSO').val('');
                $('#ASSINATURA_DESPACHO_PROCESSO').val('');
            },
            buttons:{
                Salvar:function(){
                    if($('#TEXTO_DESPACHO_PROCESSO').val() &&
                        $('#DATA_DESPACHO_PROCESSO').val() &&
                        $('#ASSINATURA_DESPACHO_PROCESSO').val()){
                        inserirDespachoProcesso();
                    }else{
                        alert('Campos com * são obrigatórios!');
                    }
                }
            }
        });

    });
    
    /*Funcoes*/
    function inserirDespachoProcesso(){
        $('#progressbar').show();
        if(confirm('Você tem certeza que inserir este novo despacho?')){
            $.post("modelos/processos/processos.php", {
                acao: 'adicionar-despacho',
                numero_processo: '<?php echo $_GET['numero_processo']; ?>',
                texto: $('#TEXTO_DESPACHO_PROCESSO').val(),
                complemento: $('#COMPLEMENTO_DESPACHO_PROCESSO').val(),
                assinatura: $('#ASSINATURA_DESPACHO_PROCESSO').val(),
                data_despacho: $('#DATA_DESPACHO_PROCESSO').val()
            },
            function(data){
                try{
                    if(data.success == 'true'){
                        $('#form-novo-despacho-processo').dialog('close');
                        oTabelaHistoricos.fnDraw(false);
                        $('#progressbar').hide();
                        alert('Despacho inserido com sucesso!');
                    }else{
                        $('#progressbar').hide();
                        alert(data.error);
                    }
                }catch(e){
                    $('#progressbar').hide();
                    alert('Ocorreu um erro ao inserir o novo despacho do processo!\n['+e+']');
                }
            }, "json");
        }else{
            $('#progressbar').hide();
        }
    }
</script>      

<!--Cadastrar-->
<div id="form-novo-despacho-processo" class="div-form-dialog">
    <div class="row">
        <label class="label">*DESPACHO:</label>
        <span class="conteudo">
            <textarea cols="45" rows="6" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='TEXTO_DESPACHO_PROCESSO'></textarea>
        </span>
    </div>
    <div class="row">
        <label class="label">*DATA:</label>
        <span class="conteudo">
            <input class="FUNDOCAIXA1" id='DATA_DESPACHO_PROCESSO'>
        </span>
    </div>
    <div class="row">
        <label class="label">*ASSINATURA:</label>
        <span class="conteudo">
            <input class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='ASSINATURA_DESPACHO_PROCESSO' maxlength="55">
        </span>
    </div>
    <div class="row">
        <label class="label">COMPLEMENTO:</label>
        <span class="conteudo">
            <textarea cols="45" rows="2" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='COMPLEMENTO_DESPACHO_PROCESSO'></textarea>
        </span>
    </div>
</div>
