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
$documento = ($_GET['acao'] == 'documento') ? 'true' : 'false';
?>
<script type="text/javascript">
    var aberta1 = <?php echo $documento; ?>;
    var consultaDemanda = false;
    $(document).ready(function() {

        /*Botao limpar*/
        $('#botao-limpar-data-inicial-pesquisar-documentos').click(function() {
            $('#DT_INICIAL').val('');
        });
        $('#botao-limpar-data-final-pesquisar-documentos').click(function() {
            $('#DT_FINAL').val('');
        });

        $('#fieldDemanda').hide();

        // Quando a pesquisa vier da consulta demanda o tipo MONIOTORAMENTO não pode ser mudado
        // Somente documentos do tipo MONITORAMENTO podem ser associados
        $('#botao-pesquisar-documentos').die('click').live('click', function(e) {
            e.preventDefault();
            if ($(this).attr('digital') != null)
            {
                consultaDemanda = true;
                $('#TIPO').val('MONITORAMENTO').attr('disabled', true);
                $('#TIPOFAKE').val('MONITORAMENTO');
            }
            $('#div-form-pesquisar-documentos').dialog('open');
        });


        /*Calentarios */
        var dates = $("#DT_INICIAL, #DT_FINAL").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            onSelect: function(selectedDate) {
                var option = this.id == "DT_INICIAL" ? "minDate" : "maxDate",
                        instance = $(this).data("datepicker"),
                        date = $.datepicker.parseDate(
                        instance.settings.dateFormat ||
                        $.datepicker._defaults.dateFormat,
                        selectedDate, instance.settings);
                dates.not(this).datepicker("option", option, date);
            }
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

        $('#div-form-pesquisar-documentos').dialog({
            title: 'Pesquisar Documentos',
            autoOpen: aberta1,
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
                    if ($('#DIGITAL').val() != "" ||
                            $('#TIPO').val() != "" ||
                            $('#NUMERO').val() != "" ||
                            $('#ASSUNTO').val() != "" ||
                            $('#ASSUNTO_COMPLEMENTAR').val() != "" ||
                            $('#DATA_ENTRADA').val() != "" ||
                            $('#DATA_CADASTRO').val() != "" ||
                            $('#DATA_DOCUMENTO').val() != "" ||
                            $('#PRAZO').val() != "" ||
                            $('#CARGO').val() != "" ||
                            $('#ASSINATURA').val() != "" ||
                            $('#ORIGEM').val() != "" ||
                            $('#DESTINO').val() != "" ||
                            $('#TECNICO_RESPONSAVEL').val() != "" ||
                            $('#RECIBO').val() != "" ||
                            $('#INTERESSADO').val() != "" ||
                            $('#DT_INICIAL').val() != "" ||
                            $('#DT_FINAL').val() != ""
                            ) {
                        jquery_pesquisar_documentos();

                    } else {
                        alert('Preenchaa pelo Menos um Campo para Pesquisa!');
                    }
                }
            },
            open: function() {
                if (consultaDemanda) {
                    $('#fieldDemanda').show();
                }
            }
        });
    });

    /*Funcoes*/

    function jquery_pesquisar_documentos() {

        $.post("modelos/documentos/documentos.php", {
            acao: 'pesquisar',
            digital: $('#DIGITAL').val(),
            tipo: $('#TIPO').val(),
            numero: $('#NUMERO').val(),
            assunto: $('#ASSUNTO').val(),
            assunto_complementar: $('#ASSUNTO_COMPLEMENTAR').val(),
            dt_entrada: jquery_replace_data($('#DATA_ENTRADA').val()),
            dt_cadastro: jquery_replace_data($('#DATA_CADASTRO').val()),
            dt_documento: jquery_replace_data($('#DATA_DOCUMENTO').val()),
            dt_prazo: $('#PRAZO').val(),
            cargo: $('#CARGO').val(),
            assinatura: $('#ASSINATURA').val(),
            origem: $('#ORIGEM').val(),
            destino: $('#DESTINO').val(),
            tecnico_responsavel: $('#TECNICO_RESPONSAVEL').val(),
            interessado: $('#INTERESSADO').val(),
            recibo: $('#RECIBO').val(),
            dt_inicial: jquery_replace_data($('#DT_INICIAL').val()),
            dt_final: jquery_replace_data($('#DT_FINAL').val()),
            tp_periodo: $('#TP_PERIODO').val(),
            tp_pesquisa: $('#TP_PESQUISA_DOCUMENTO').val(),
            solicitacao_demanda: $('#DEMANDA_SOLICITACAO').val(),
            resposta_demanda: $('#DEMANDA_RESPOSTA').val(),
        }, function(data) {
            try {
                if (data.success == 'true') {
                    var consultaComDemanda = '';

                    if ($('#botao-pesquisar-documentos').attr('digital') != null) {
                        consultaComDemanda = "?acao_pesquisa=demanda&digital=" + $('#FAKE_DIGITAL_DETALHAR_DOCUMENTO').val();

                        window.location = "lista_pesquisa_documentos_demanda.php" + consultaComDemanda;

                    } else {
                        window.location = "lista_pesquisa_documentos.php";
                    }

                } else {
                    alert(data.error);
                }
            } catch (e) {
                alert('Ocorreu um erro ao tentar pesquisar o documento!\n[' + e + ']');
            }
        }, "json");
    }

</script>
<div class="div-form-dialog" id="div-form-pesquisar-documentos">
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
                    <input type="hidden" id="TIPOFAKE" class="FUNDOCAIXA1">
                </span>
            </div>
            <div class="row">
                <label class="label">NUMERO:</label>
                <span class="conteudo">
                    <input type="text" id="NUMERO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </span>
            </div>
            <div class="row">
                <label class="label">ASSUNTO:</label>
                <span class="conteudo">
                    <input type="text" id="ASSUNTO" class="FUNDOCAIXA1" onKeyUp="DigitaLetraSeguro(this)" >
                </span>
            </div>
            <div class="row">
                <label class="label">ASSUNTO COMPLEMENTAR:</label>
                <span class="conteudo">
                    <input type="text" id="ASSUNTO_COMPLEMENTAR" class="FUNDOCAIXA1" onKeyUp="DigitaLetraSeguro(this)" >
                </span>
            </div>
            <div class="row">
                <label class="label">CARGO:</label>
                <span class="conteudo">
                    <input type="text" id="CARGO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </span>
            </div>
            <div class="row">
                <label class="label">ASSINATURA:</label>
                <span class="ASSINATURA">
                    <input type="text" id="ASSINATURA" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
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
                <label class="label">ENCAMINHADO PARA:</label>
                <span class="conteudo">
                    <input type="text" id="TECNICO_RESPONSAVEL" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </span>
            </div>
            <div class="row">
                <label class="label">RECEBIDO POR:</label>
                <span class="conteudo">
                    <input type="text" id="RECIBO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </span>
            </div>
            <fieldset id="fieldDemanda">
                <legend>Demandas</legend>
                <div class="row">
                    <label class="label">Solcitação:</label>
                    <span class="conteudo">
                        <input type="text" id="DEMANDA_SOLICITACAO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                    </span>
                </div>
                <div class="row">
                    <label class="label">Resposta:</label>
                    <span class="conteudo">
                        <input type="text" id="DEMANDA_RESPOSTA" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                    </span>
                </div>
            </fieldset>
        </div>
        <h3><a href="#">Datas</a></h3>
        <div>
            <div class="row">
                <label class="label">DATA ENTRADA:</label>
                <span class="conteudo">
                    <input type="text" id="DATA_ENTRADA" class="FUNDOCAIXA1 data-portugues" maxlength="10">
                </span>
            </div>
            <div class="row">
                <label class="label">DATA DO CADASTRO:</label>
                <span class="conteudo">
                    <input type="text" id="DATA_CADASTRO" class="FUNDOCAIXA1 data-portugues" maxlength="10">
                </span>
            </div>
            <div class="row">
                <label class="label">DATA DOCUMENTO:</label>
                <span class="conteudo">
                    <input type="text" id="DATA_DOCUMENTO" class="FUNDOCAIXA1 data-portugues" maxlength="10">
                </span>
            </div>
            <div class="row">
                <label class="label">DATA DO PRAZO:</label>
                <span class="conteudo">
                    <input type="text" id="PRAZO" class="FUNDOCAIXA1 data-portugues" maxlength="10">
                </span>
            </div>
            <div class="row">
                <label class="label">BUSCAR PERIODO POR:</label>
                <span class="conteudo">
                    <select id="TP_PERIODO" class="FUNDOCAIXA1">
                        <option value="DT_CADASTRO">DATA DO CADASTRO</option>
                        <option value="DT_PRAZO">DATA DO PRAZO</option>
                        <option value="DT_ENTRADA">DATA DA ENTRADA</option>
                        <option value="DT_DOCUMENTO">DATA DO DOCUMENTO</option>
                    </select>
                </span>
            </div>
            <div class="row">
                <label class="label">DATA INICIO:</label>
                <span class="conteudo">
                    <input type="text" readonly id="DT_INICIAL" class="FUNDOCAIXA1">
                </span>
                <img alt=""  title="Limpar" class="botao-auxiliar" src="imagens/fam/delete.png" id="botao-limpar-data-inicial-pesquisar-documentos">
            </div>
            <div class="row">
                <label class="label">DATA FINAL:</label>
                <span class="conteudo">
                    <input type="text" readonly id="DT_FINAL" class="FUNDOCAIXA1">
                </span>
                <img alt=""  title="Limpar" class="botao-auxiliar" src="imagens/fam/delete.png" id="botao-limpar-data-final-pesquisar-documentos">
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