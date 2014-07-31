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

        /*Botao limpar*/
        $('#botao-limpar-data-prazo-pesquisar-documentos').click(function() {
            $('#DT_PRAZO').val('');
        });
        $('#botao-limpar-data-resposta-pesquisar-documentos').click(function() {
            $('#DT_RESPOSTA').val('');
        });
        $('#botao-pesquisar-prazos').click(function() {
            $('#box-pesquisar-prazos').dialog('open');
        });

        //Combo
        $('#PRIORIDADE').combobox('modelos/combos/lista_prioridades.php');

        /*Calentarios */
        var dates = $("#DT_PRAZO, #DT_RESPOSTA").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1
        });

        /*Acoordion*/
        var icons = {
            header: "ui-icon-circle-arrow-e",
            headerSelected: "ui-icon-circle-arrow-s"
        };
        $("#accordion-pesquisar-documentos").accordion({
            autoHeight: false,
            navigation: true,
            icons: icons
        });

        $('#box-pesquisar-prazos').dialog({
            title: 'Pesquisar Prazos',
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 650,
            autoHeight: true,
            //height: auto,
            buttons: {
                Limpar: function() {
                    $(this).find('input').val('');
                },
                Pesquisar: function() {
                    if ($('#box-pesquisar-prazos #DIGITAL').val() ||
                            $('#box-pesquisar-prazos #TIPO').val() ||
                            $('#box-pesquisar-prazos #NUMERO').val() ||
                            $('#box-pesquisar-prazos #ASSUNTO').val() ||
                            $('#box-pesquisar-prazos #ASSUNTO_COMPLEMENTAR').val() ||
                            $('#box-pesquisar-prazos #PRIORIDADE').val() ||
                            $('#box-pesquisar-prazos #CARGO').val() ||
                            $('#box-pesquisar-prazos #ASSINATURA').val() ||
                            $('#box-pesquisar-prazos #ORIGEM').val() ||
                            $('#box-pesquisar-prazos #DESTINO').val() ||
                            $('#box-pesquisar-prazos #TECNICO_RESPONSAVEL').val() ||
                            $('#box-pesquisar-prazos #INTERESSADO').val() ||
                            $('#box-pesquisar-prazos #RESPOSTA').val() ||
                            $('#box-pesquisar-prazos #SOLICITACAO').val() ||
                            $('#box-pesquisar-prazos #DT_PRAZO').val() ||
                            $('#box-pesquisar-prazos #DT_RESPOSTA').val()
                            ) {
                        jquery_pesquisar_prazos();
                    } else {
                        alert('Preencha pelo menos um campo para pesquisar!');
                    }
                }
            }
        });

    });

    /*Funcoes*/
    function jquery_pesquisar_prazos() {

        $.post("modelos/prazos/prazos.php", {
            acao: 'pesquisar',
            assunto: $('#box-pesquisar-prazos #ASSUNTO').val(),
            prioridade: $('#box-pesquisar-prazos #PRIORIDADE').val(),
            nu_ref: $('#box-pesquisar-prazos #DIGITAL').val(),
            tipo: $('#box-pesquisar-prazos #TIPO').val(),
            dt_prazo: $('#box-pesquisar-prazos #DT_PRAZO').val(),
            dt_resposta: $('#box-pesquisar-prazos #DT_RESPOSTA').val(),
            nm_unidade_origem: $('#box-pesquisar-prazos #ORIGEM').val(), //nm_usuario_origem: $('#ORIGEM').val(),//o campo ORIGEM remete a UND DE ORIGEM
            nm_unidade_destino: $('#box-pesquisar-prazos #DESTINO').val(),
            interessado: $('#box-pesquisar-prazos #INTERESSADO').val(),
            tp_periodo: $('#box-pesquisar-prazos #TP_PERIODO').val(),
            st_resposta: $('#box-pesquisar-prazos #STATUS_RESPOSTA').val(),
            tx_solicitacao: $('#box-pesquisar-prazos #SOLICITACAO').val(),
            tx_resposta: $('#box-pesquisar-prazos #RESPOSTA').val()
        }, function(data) {
            try {
                if (data.success == 'true') {
                    //oTablePesquisaPrazos.fnDraw(false);
                    $('#box-pesquisar-prazos').dialog("close");
                    $('#tabs-prazos-pesq').click();
                } else {
                    alert(data.error);
                }
            } catch (e) {
                alert('Ocorreu um erro ao tentar pesquisar documentos!\n[' + e + ']');
            }
        }, "json");
    }

</script>
<div id="box-pesquisar-prazos" class="div-form-dialog" title="Pesquisar Prazo">
    <div id="accordion-pesquisar-documentos">
        <h3><a href="#">Informacoes basicas</a></h3>
        <div>
            <div class="row">
                <label class="label">DIGITAL:</label>
                <span class="conteudo">
                    <input type="text" id="DIGITAL" maxlength="7" onkeyup="DigitaNumeroSeguro(this);" class="FUNDOCAIXA1">
                </span>
            </div>

            <div class="row">
                <label class="label">TIPO:</label>
                <span class="conteudo">
                    <input type="text" id="TIPO" onKeyUp="DigitaLetraSeguro(this);" class="FUNDOCAIXA1">
                </span>
            </div>

            <div class="row">
                <label class="label">ASSUNTO:</label>
                <span class="conteudo">
                    <input type="text" id="ASSUNTO" class="FUNDOCAIXA1" onKeyUp="DigitaLetraSeguro(this)">
                </span>
            </div>

            <div class="row">
                <label>PRIORIDADE:</label>
                <span class="conteudo">
                    <select type="text" id="PRIORIDADE"></select>
                </span>
            </div>

            <div class="row">
                <label class="label">ORIGEM:</label>
                <span class="conteudo">
                    <input type="text" id="ORIGEM" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </span>
            </div>

            <div class="row">
                <label class="label">INTERESSADO:</label>
                <span class="conteudo">
                    <input type="text" id="INTERESSADO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </span>
            </div>

            <div class="row">
                <label class="label">DESTINO:</label>
                <span class="conteudo">
                    <input type="text" id="DESTINO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </span>
            </div>

            <div class="row">
                <label class="label">STATUS DE RESPOSTA:</label>
                <span class="conteudo">
                    <select id="STATUS_RESPOSTA">
                        <option value="TODOS" selected>TODOS</option>
                        <option value="RESP">RESPONDIDOS</option>
                        <option value="NRESP">NÃO RESPONDIDOS</option>
                    </select>
                </span>
            </div>

            <div class="row">
                <label class="label">SOLICITACAO:</label>
                <span class="conteudo">
                    <input type="text" id="SOLICITACAO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </span>
            </div>

            <div class="row">
                <label class="label">RESPOSTA:</label>
                <span class="conteudo">
                    <input type="text" id="RESPOSTA" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </span>
            </div>

        </div>

        <h3><a href="#">Datas</a></h3>
        <div>
            <div class="row">
                <label class="label">DATA DO PRAZO:</label>
                <span class="conteudo">
                    <input type="text" readonly id="DT_PRAZO" class="FUNDOCAIXA1">
                </span>
                <img alt=""  title="Limpar" class="botao-auxiliar" src="imagens/fam/delete.png" id="botao-limpar-data-prazo-pesquisar-documentos">
            </div>
            <div class="row">
                <label class="label">DATA RESPOSTA:</label>
                <span class="conteudo">
                    <input type="text" readonly id="DT_RESPOSTA" class="FUNDOCAIXA1">
                </span>
                <img alt=""  title="Limpar" class="botao-auxiliar" src="imagens/fam/delete.png" id="botao-limpar-data-resposta-pesquisar-documentos">
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
                    <select id="TP_PESQUISA_DOCUMENTO" class="FUNDOCAIXA1">
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