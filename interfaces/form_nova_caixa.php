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
        /*Cadastrar*/
        $('#box-nova-caixa').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 600,
            autoHeight: true,
            open: function(event, ui) {
                $('#ID_CLASSIFICACAO_CADASTRAR_CAIXA').val('');
                $('#ID_UNIDADE_CADASTRAR_CAIXA').val('');
                $('#NU_CADASTRAR_CAIXA').val('');
                $('#NU_ANO_CADASTRAR_CAIXA').val('');
            },
            buttons: {
                Salvar: function() {
                    var validou = jquery_validar_nova_caixa('CADASTRAR');
                    if (validou == true){
                        if(confirm('Você tem certeza que deseja salvar esta nova caixa agora?')){
                            $.post("modelos/caixas/caixas.php", {
                                acao: 'cadastrar',
                                id_classificacao: $('#ID_CLASSIFICACAO_CADASTRAR_CAIXA').val(),
                                nu_caixa: $('#NU_CADASTRAR_CAIXA').val(),
                                id_unidade: $('#ID_UNIDADE_CADASTRAR_CAIXA').val(),
                                nu_ano_caixa: $('#NU_ANO_CADASTRAR_CAIXA').val()
                            },
                            function(data){
                                if(data.success == 'true'){
                                    $('#box-nova-caixa').dialog("close");
                                    oTableCaixas.fnDraw(false);
                                    alert('Caixa cadastrada com sucesso!');
                                }else{
                                    alert('Ocorreu um erro ao tentar cadastrar a nova caixa!\n['+data.error+']');
                                }
                            }, "json");
                        }
                    }else{
                        alert(validou);
                    }
                }
            }
        });

        // listener
        
        $('#botao-nova-caixa').click(function(){
            $('#box-nova-caixa').dialog('open');
        });

        $('#ID_CLASSIFICACAO_CADASTRAR_CAIXA').combobox('modelos/combos/classificacoes.php', {'tipo':'pai'});
      
    });
    
    /*Functions*/
    function jquery_validar_nova_caixa(formulario){
        if(
            ($('#ID_UNIDADE_'+formulario+'_CAIXA').val() != '0') && 
            ($('#ID_UNIDADE_'+formulario+'_CAIXA').val() != null) && 
            $('#NU_'+formulario+'_CAIXA').val() &&
            $('#NU_ANO_'+formulario+'_CAIXA').val() &&
            ($('#ID_CLASSIFICACAO_'+formulario+'_CAIXA').val() != '0') ){
           
            // Foi tudo preenchido, fazer verificação ajax para validar se pode cadastrar ou não
            var id_caixa = 0;
            if (formulario == 'DETALHAR') {
                id_caixa = $('#ID_'+formulario+'_CAIXA').val();
            }
            $.ajaxSetup({async:false});
            var retorno = 0;
            $.post("modelos/caixas/caixas.php", {
                acao: 'unique',
                id: id_caixa,
                id_classificacao: $('#ID_CLASSIFICACAO_'+formulario+'_CAIXA').val(),
                nu_caixa: $('#NU_'+formulario+'_CAIXA').val(),
                id_unidade: $('#ID_UNIDADE_'+formulario+'_CAIXA').val(),
                nu_ano_caixa: $('#NU_ANO_'+formulario+'_CAIXA').val()
            },
            function(data){
                if(data.success == 'true'){
                    retorno = true;
                }else{
                    retorno = data.error;
                }
            }, "json");
            return retorno;
        } else {
            return 'Campo(s) obrigatório(s) em branco ou preenchido(s) de forma inválida!';
        }

    }

    /**
     ** Correcao 31/01/2013
     */
    $(document).ready(function(){
        /*Filtro Assunto*/
        $('#box-filtro-unidade-nova-caixa').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#FILTRO_UNIDADE_NOVA_CAIXA").val('');
            }
        });
        /*Filtro Assunto*/
        $('#botao-filtro-unidade-nova-caixa').click(function(){
            $('#box-filtro-unidade-nova-caixa').dialog('open');
        });
        /*Combo Unidades*/
        $("#FILTRO_UNIDADE_NOVA_CAIXA").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox:'ID_UNIDADE_CADASTRAR_CAIXA',
            extraParams: {
                action: 'unidades-internas',
                type: 'IN'
            }
        });
    });

</script>      

<!--Cadastrar-->
<div id="box-nova-caixa" class="div-form-dialog" title="Nova Caixa">
    <fieldset>
        <label>Informações Principais</label>
        <div class="row">
            <label class="label">*NUMERO:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="NU_CADASTRAR_CAIXA" maxlength="8" onkeyup="zeroFill(this, 7);" />
            </span>
        </div>
        <div class="row">
            <label class="label">*ANO CAIXA:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="NU_ANO_CADASTRAR_CAIXA" onkeyup="DigitaNumero(this)" maxlength="4" />
            </span>
        </div>
        <div class="row">
            <label class="label">CLASSIFICACAO:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="ID_CLASSIFICACAO_CADASTRAR_CAIXA"></select>
            </span>
        </div>
        <div class="row">
            <label class="label">*UNIDADE:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="ID_UNIDADE_CADASTRAR_CAIXA"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-unidade-nova-caixa" src="imagens/fam/application_edit.png">
        </div>
    </fieldset>
</div>

<div id="box-filtro-unidade-nova-caixa" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_UNIDADE_NOVA_CAIXA" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>