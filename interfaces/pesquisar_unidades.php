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
        $('#box-pesquisar-unidades').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 600,
            autoHeight: true,
            buttons: {
                Pesquisar: function() {
                    $.post("modelos/unidades/unidades.php", {
                        acao: 'pesquisar',
                        id: $('#ID_PESQUISAR_UNIDADE').val(),
                        nome: $('#NOME_PESQUISAR_UNIDADE').val(),
                        sigla: $('#SIGLA_PESQUISAR_UNIDADE').val(),
                        uaaf: $('#UAAF_PESQUISAR_UNIDADE').val(),
                        cr: $('#CR_PESQUISAR_UNIDADE').val(),
                        superior: $('#SUPERIOR_PESQUISAR_UNIDADE').val(),
                        diretoria: $('#DIRETORIA_PESQUISAR_UNIDADE').val(),
                        id_tipo: $('#TIPO_PESQUISAR_UNIDADE').val(),
                        up: $('#UP_PESQUISAR_UNIDADE').val(),
                        codigo: $('#CODIGO_PESQUISAR_UNIDADE').val(),
                        id_uf: $('#UF_PESQUISAR_UNIDADE').val(),
                        email: $('#EMAIL_PESQUISAR_UNIDADE').val(),
                        uop: $('#PESQUISA_UNIDADE_ORGAO_PRINCIPAL').val()
                    },
                    function(data){
                        if(data.success == 'true'){
                            oTableUnidades.fnDraw(false);
                            $('#box-pesquisar-unidades').dialog("close");
                        }else{
                            alert('Ocorreu um erro ao tentar pesquisar as unidades!['+data.error+']');
                        }
                    }, "json");
                }
            }
        });
        /*Filtro Superior Pesquisar*/
        $('#box-filtro-superior-pesquisar-unidade').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#FILTRO_SUPERIOR_PESQUISAR_UNIDADE").val('');
            }
        });
        /*Combo Superior Pesquisar*/
        $("#FILTRO_SUPERIOR_PESQUISAR_UNIDADE").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox:'SUPERIOR_PESQUISAR_UNIDADE',
            extraParams: {
                action: 'unidades-internas',
                type: 'IN'
            }
        });
        
        /*Filtro Diretoria Pesquisar*/
        $('#box-filtro-diretoria-pesquisar-unidade').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#FILTRO_DIRETORIA_PESQUISAR_UNIDADE").val('');
            }
        });
        /*Combo Diretoria Pesquisar*/
        $("#FILTRO_DIRETORIA_PESQUISAR_UNIDADE").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox:'DIRETORIA_PESQUISAR_UNIDADE',
            extraParams: {
                action: 'unidades-internas',
                type: 'DIR'
            }
        });

        /*Filtro Cr Pesquisar*/
        $('#box-filtro-cr-pesquisar-unidade').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#FILTRO_CR_PESQUISAR_UNIDADE").val('');
            }
        });
        /*Combo Cr Pesquisar*/
        $("#FILTRO_CR_PESQUISAR_UNIDADE").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox:'CR_PESQUISAR_UNIDADE',
            extraParams: {
                action: 'unidades-internas',
                type: 'CR'
            }
        });
        
        /*Filtro Uaaf Pesquisar*/
        $('#box-filtro-uaaf-pesquisar-unidade').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#FILTRO_UAAF_PESQUISAR_UNIDADE").val('');
            }
        });
        /*Combo Uaaf Pesquisar*/
        $("#FILTRO_UAAF_PESQUISAR_UNIDADE").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox:'UAAF_PESQUISAR_UNIDADE',
            extraParams: {
                action: 'unidades-internas',
                type: 'UAAF'
            }
        });
        
        $('#botao-pesquisa-avancada-unidades').click(function(){
            $('#box-pesquisar-unidades').dialog('open');
        });
        /*Filtro Superior Detalhar*/
        $('#botao-filtro-superior-pesquisar-unidade').click(function(){
            $('#box-filtro-superior-pesquisar-unidade').dialog('open');
        });
        /*Filtro Diretoria Detalhar*/
        $('#botao-filtro-diretoria-pesquisar-unidade').click(function(){
            $('#box-filtro-diretoria-pesquisar-unidade').dialog('open');
        });
        /*Filtro Cr Detalhar*/
        $('#botao-filtro-cr-pesquisar-unidade').click(function(){
            $('#box-filtro-cr-pesquisar-unidade').dialog('open');
        });
        /*Filtro Uaaf Detalhar*/
        $('#botao-filtro-uaaf-pesquisar-unidade').click(function(){
            $('#box-filtro-uaaf-pesquisar-unidade').dialog('open');
        });

        $('#UF_PESQUISAR_UNIDADE').combobox('modelos/combos/ufs.php');
        $('#TIPO_PESQUISAR_UNIDADE').combobox('modelos/combos/unidades_tipo.php',{tipo: 'tipos'});
        $('#PESQUISA_UNIDADE_ORGAO_PRINCIPAL').combobox('modelos/combos/orgaos_principais.php');
    });
    
    /*Functions*/
    function jquery_pesquisa_avancada_unidades() {
        $('#box-pesquisa-avancada-unidades').dialog('open');
    }

</script>      

<!--Pesquisar-->
<div id="box-pesquisar-unidades" class="div-form-dialog" title="Pesquisar Unidade">
    <fieldset>
        <label class="label">Informações Principais</label>
        <div class="row">
            <label class="label">NOME:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="NOME_PESQUISAR_UNIDADE" onkeyup="DigitaLetraSeguro(this)">
            </span>
        </div>
        <div class="row">
            <label class="label">SIGLA:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="SIGLA_PESQUISAR_UNIDADE" onkeyup="DigitaLetraSeguro(this)">
            </span>
        </div>
        <div class="row">
            <label class="label">TIPO DE UNIDADE:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="TIPO_PESQUISAR_UNIDADE"></select>
            </span>
        </div>
        <div class="row">
            <label class="label">UF:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="UF_PESQUISAR_UNIDADE"></select>
            </span>
        </div>
        <div class="row">
            <label class="label">EMAIL:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="EMAIL_PESQUISAR_UNIDADE">
            </span>
        </div>
        <div class="row">
            <label class="label">UP:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="UP_PESQUISAR_UNIDADE">
                    <option value=""></option>
                    <option value="1">Sim</option>
                    <option value="0">Nao</option>
                </select>
            </span>
        </div>
        <div class="row">
            <label class="label">CODIGO:</label>
            <span class="conteudo">
                <input type="text" maxlength="5" onkeyup="DigitaNumero(this)" class="FUNDOCAIXA1" id="CODIGO_PESQUISAR_UNIDADE">
            </span>
        </div>
        <div class="row">
            <label class="label">U.O.P:</label>
            <span class="conteudo">
                <select id="PESQUISA_UNIDADE_ORGAO_PRINCIPAL" class="select">
                    <option value="0">Selecione</option>
                </select>
            </span>
        </div>
    </fieldset>
    <fieldset>
        <label class="label">Informacoes Hierarquicas</label>
        <div class="row">
            <label class="label">UAAF:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="UAAF_PESQUISAR_UNIDADE"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-uaaf-pesquisar-unidade" src="imagens/fam/application_edit.png">
        </div>
        <div class="row">
            <label class="label">CR:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="CR_PESQUISAR_UNIDADE"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-cr-pesquisar-unidade" src="imagens/fam/application_edit.png">
        </div>
        <div class="row">
            <label class="label">SUPERIOR:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="SUPERIOR_PESQUISAR_UNIDADE"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-superior-pesquisar-unidade" src="imagens/fam/application_edit.png">
        </div>
        <div class="row">
            <label class="label">DIRETORIA:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="DIRETORIA_PESQUISAR_UNIDADE"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-diretoria-pesquisar-unidade" src="imagens/fam/application_edit.png">
        </div>
    </fieldset>
</div>

<!-- pesquisar superior-->
<div id="box-filtro-superior-pesquisar-unidade" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_SUPERIOR_PESQUISAR_UNIDADE" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>
<!-- cadastrar diretoria-->
<div id="box-filtro-diretoria-pesquisar-unidade" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_DIRETORIA_PESQUISAR_UNIDADE" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>
<!-- cadastrar cr-->
<div id="box-filtro-cr-pesquisar-unidade" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_CR_PESQUISAR_UNIDADE" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>
<!-- cadastrar uaaf-->
<div id="box-filtro-uaaf-pesquisar-unidade" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_UAAF_PESQUISAR_UNIDADE" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>