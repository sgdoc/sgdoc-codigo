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
        /*Pesquisar*/
        $('#box-pesquisar-caixas').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 600,
            autoHeight: true,
            buttons: {
                Pesquisar: function() {
                    $.post("modelos/caixas/caixas.php", {
                        acao: 'pesquisar',
                        id_classificacao: $('#ID_CLASSIFICACAO_PESQUISAR_CAIXA').val(),
                        nu_caixa: $('#NU_PESQUISAR_CAIXA').val(),
                        id_unidade: $('#ID_UNIDADE_PESQUISAR_CAIXA').val(),
                        nu_ano_caixa: $('#NU_ANO_PESQUISAR_CAIXA').val(),
                        st_finalizada: $('#ST_FINALIZADA_PESQUISAR_CAIXA').val()
                    },
                    function(data){
                        if(data.success == 'true'){
                            oTableCaixas.fnDraw(false);
                            $('#box-pesquisar-caixas').dialog("close");
                        }else{
                            alert('Ocorreu um erro ao tentar pesquisar as caixas!['+data.error+']');
                        }
                    }, "json");
                }
            }
        });

        $('#botao-pesquisa-avancada-caixas').click(function(){
            $('#box-pesquisar-caixas').dialog('open');
        });

        $('#ID_CLASSIFICACAO_PESQUISAR_CAIXA').combobox('modelos/combos/classificacoes.php', {'tipo':'pai'});
        
        /*Filtro Unidade*/
        $('#box-filtro-unidade-pesquisar-caixa').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#FILTRO_UNIDADE_PESQUISAR_CAIXA").val('');
            }
        });
        
        $('#botao-filtro-unidade-pesquisar-caixa').click(function(){
            $('#box-filtro-unidade-pesquisar-caixa').dialog('open');
        });
        /*Combo Unidades*/
        $("#FILTRO_UNIDADE_PESQUISAR_CAIXA").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox:'ID_UNIDADE_PESQUISAR_CAIXA',
            extraParams: {
                action: 'unidades-internas',
                type: 'IN'
            }
        });
        
    });
    
    /*Functions*/
    function jquery_pesquisa_avancada_caixas(){
        $('#box-pesquisa-avancada-caixas').dialog('open');
    }


</script>      

<!--Pesquisar-->
<div id="box-pesquisar-caixas" class="div-form-dialog" title="Pesquisar Caixa">
    <fieldset>
        <label>Informações Principais</label>
        <div class="row">
            <label class="label">NUMERO:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="NU_PESQUISAR_CAIXA" maxlength="7" onkeyup="DigitaNumero(this);" />
            </span>
        </div>
        <div class="row">
            <label class="label">ANO CAIXA:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="NU_ANO_PESQUISAR_CAIXA" onkeyup="DigitaNumero(this)" maxlength="4" />
            </span>
        </div>
        <div class="row">
            <label class="label">CLASSIFICACAO:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="ID_CLASSIFICACAO_PESQUISAR_CAIXA"></select>
            </span>
        </div>
        <div class="row">
            <label class="label">UNIDADE:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="ID_UNIDADE_PESQUISAR_CAIXA"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-unidade-pesquisar-caixa" src="imagens/fam/application_edit.png">
        </div>
        <div class="row">
            <label class="label">SITUACAO:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="ST_FINALIZADA_PESQUISAR_CAIXA">
                    <option value="" selected="selected"></option>
                    <option value="1">Fechada</option>
                    <option value="0">Aberta</option>
                </select>
            </span>
        </div>
    </fieldset>
</div>

<div id="box-filtro-unidade-pesquisar-caixa" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_UNIDADE_PESQUISAR_CAIXA" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>