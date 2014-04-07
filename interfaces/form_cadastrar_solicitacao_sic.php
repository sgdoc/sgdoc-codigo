
<!--/*
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
 * */-->

<html>
    <head>
        <?php
        print(Util::autoLoadJavascripts(
                        array('javascripts/jquery.form.js')
        ));
        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                // Método que cria o ajaxForm

                var bar = $('.bar');
                var percent = $('.percent');
                $('.progress').hide();
                //var status = $('#status');

                $('#solicitacao_sic').ajaxForm({
                    dataType: 'json',
                    beforeSend: function() {
                        //status.empty();
                        if ($("#FILES_CADASTRAR_SOLICITACOES").val() != '') {
                            $('.progress').show();
                            $('#progressbar p').html('Aguarde, pode levar alguns minutos por ter anexos...');
                            var percentVal = '0%';
                            bar.width(percentVal)
                            percent.html(percentVal);
                        } else {
                            $('#progressbar').show();
                        }
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        var percentVal = percentComplete + '%';
                        bar.width(percentVal)
                        percent.html(percentVal);
                        //console.log(percentVal, position, total);
                        if (percentComplete > 80) {
                            $('#progressbar').show();
                        }
                    },
                    beforeSubmit: function(a, f, o) {
                    },
                    success: function(data) {
                        $('.progress').hide();
                        $('#progressbar').hide();
                        bar.width(0);
                        percent.html('0%');
                        if (data.success == 'true') {
                            /*Limpar Campos*/
                            $('#NUMERO_CADASTRAR_SOLICITACOES').val('');
                            $('#DATA_DOCUMENTO_CADASTRAR_SOLICITACOES').val('');
                            $('#ASSUNTO_CADASTRAR_SOLICITACOES').empty()
                            $('#CPF_INTERESSADO_CADASTRAR_SOLICITACOES').val('');
                            $('#NOME_INTERESSADO_CADASTRAR_SOLICITACOES').val('');
                            $('#EMAIL_INTERESSADO_CADASTRAR_SOLICITACOES').val('');
                            $('#PRAZO_CADASTRAR_SOLICITACOES').val('');
                            $('#TRAMITAR_PARA_CADASTRAR_SOLICITACOES').empty();
                            $('#OCR_CADASTRAR_SOLICITACOES').val('');
                            $('#FILES_CADASTRAR_SOLICITACOES').val('');
                            /*Alert*/
                            $('#div-form-cadastrar-solicitacoes').dialog('close');
                            alert(data.message);
                        } else {
                            alert(data.error);
                        }
                    }
                });


                /*Abrir form novo documento*/
                $('#botao-documento-novo-cadastrar-solicitacoes').click(function() {
                    $('#div-form-cadastrar-solicitacoes').dialog('open');
                });

                $("#cpf_cnpj_sic").hide();

                $('#combo_cpf_interessado_sic').change(function() {
                    switch ($(this).val()) {
                        case '0':
                            // Nem CPF, nem CNPJ
                            $("#cpf_cnpj_sic").hide();
                            break;
                        case 'cnpj':
                            $("#CPF_INTERESSADO_CADASTRAR_SOLICITACOES").mask('99.999.999/9999-99');
                            $("#cpf_cnpj_sic").show();
                            $("#cpf_cnpj_label_sic").text('CNPJ INTERESSADO');
                            break;

                        case 'cpf':
                            $("#CPF_INTERESSADO_CADASTRAR_SOLICITACOES").mask('999.999.999-99');
                            $("#cpf_cnpj_sic").show();
                            $("#cpf_cnpj_label_sic").text('CPF INTERESSADO');
                            break;

                        default:
                            break;
                    }
                    if ($(this).val() != '0') {
                        $("#CPF_INTERESSADO_CADASTRAR_SOLICITACOES").focus();
                    } else {
                        $("#NOME_INTERESSADO_CADASTRAR_SOLICITACOES").focus();
                    }
                });

                $('#NUMERO_CADASTRAR_SOLICITACOES').mask('99999.999999/9999-99', {placeholder: " "});

            });

            /*Validar Campos Cadastro Documento*/
            function jquery_validar_campos_cadastrar_solicitacoes() {
                /*Validar tipo de origem procuradorias*/
                /*Validar campos*/
                if (
                        $('#NUMERO_CADASTRAR_SOLICITACOES').val() &&
                        $('#DATA_DOCUMENTO_CADASTRAR_SOLICITACOES').val() &&
                        $('#ASSUNTO_CADASTRAR_SOLICITACOES').val() &&
                        $('#PRAZO_CADASTRAR_SOLICITACOES').val() &&
                        $('#NOME_INTERESSADO_CADASTRAR_SOLICITACOES').val() &&
                        $('#TRAMITAR_PARA_CADASTRAR_SOLICITACOES').val() &&
                        ($('#TRAMITAR_PARA_CADASTRAR_SOLICITACOES').val() != '0') &&
                        $('#OCR_CADASTRAR_SOLICITACOES').val()
                        ) {
                    /*Validar o cpf/cnpj antes de submeter*/
                    var tipo = $('#combo_cpf_interessado_sic').val();
                    if (tipo != '0') {
                        var cpf = $('#CPF_INTERESSADO_CADASTRAR_SOLICITACOES').val();
                        if (cpf.length > 0) {
                            if (!jquery_validar_cpf_cnpj(cpf)) {
                                alert($("#cpf_cnpj_label_sic").text() + ' invalido!');
                                return false;
                            }
                        }
                    }
                    return jquery_cadastrar_solicitacao();
                } else {
                    alert('Campo(s) obrigatório(s) em branco ou preenchidos de forma inválida!');
                }
            }

            /*Inserir documento*/
            function jquery_cadastrar_solicitacao() {
                if (confirm('Você tem certeza que deseja cadastrar esta solicitação SIC?')) {
                    $('#solicitacao_sic').submit();
                }
            }

            $(document).ready(function() {
                $("#FILTRO_ASSUNTO_CADASTRAR_SOLICITACOES").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox: 'ASSUNTO_CADASTRAR_SOLICITACOES',
                    extraParams: {
                        action: 'documentos-assuntos'
                    }
                });

                var prazo = $("#PRAZO_CADASTRAR_SOLICITACOES").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    numberOfMonths: 1
                });

                $("#DATA_DOCUMENTO_CADASTRAR_SOLICITACOES").datepicker({
                    defaultDate: new Date(),
                    changeMonth: true,
                    changeYear: true,
                    numberOfMonths: 1,
                    onSelect: function(selectedDate) {
                        var instance = $(this).data("datepicker");
                        var date = $.datepicker.parseDate(
                                instance.settings.dateFormat ||
                                $.datepicker._defaults.dateFormat,
                                selectedDate, instance.settings);
                        var defDate = new Date(),
                                maxDate = new Date();
                        defDate.setDate(date.getDate() + <?php print Config::factory()->getParam('config.sic.data.padrao'); ?>);
                        maxDate.setDate(date.getDate() + <?php print Config::factory()->getParam('config.sic.data.limite'); ?>);
                        prazo.datepicker("option", "maxDate", maxDate);
                        prazo.datepicker("setDate", defDate);
                    }
                });

                $('#box-filtro-assunto-cadastrar-solicitacoes').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 90,
                    close: function(event, ui) {
                        $("#FILTRO_ASSUNTO_CADASTRAR_SOLICITACOES").val('');
                    }
                });

                $('#div-form-cadastrar-solicitacoes').dialog({
                    title: 'Nova Solicitação SIC',
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    width: 650,
                    height: 650,
                    open: function(event, ui) {
                        // verifica se temos digital disponivel
                        $('#progressbar').show();
                        $('#TRAMITAR_PARA_CADASTRAR_SOLICITACOES').combobox('modelos/combos/lista_tramite.php', {'tipo': 'json'});
                        var the_dialog = $(this);
                        $.post("modelos/sic/checar_digital.php", {},
                                function(data) {
                                    $('#progressbar').hide();
                                    if (data.success == 'true') {
                                        return true;
                                    } else {
                                        the_dialog.dialog('close');
                                        alert(data.error);
                                    }
                                }, "json");
                    },
                    buttons: {
                        Salvar: function() {
                            jquery_validar_campos_cadastrar_solicitacoes();
                        },
                        Cancelar: function() {
                            $('#box-filtro-assunto-cadastrar-solicitacoes').dialog('close');
                            /*Liberar campos tipo e numero possivelmente bloqueados*/
                            $('#NUMERO_CADASTRAR_SOLICITACOES').removeAttr('disabled').val('');
                            $(this).dialog('close');
                        }
                    },
                    close: function(event, ui) {
                        $('#box-filtro-tramitar-para-cadastrar-solicitacoes').dialog('close');
                        $('#box-filtro-assunto-cadastrar-solicitacoes').dialog('close');
                    }
                });

                $('#botao-filtro-assunto-cadastrar-solicitacoes').click(function() {
                    $('#box-filtro-assunto-cadastrar-solicitacoes').dialog('open');
                });

                $('#botao-filtro-tramitar-para-cadastrar-solicitacoes').click(function() {
                    $('#box-filtro-tramitar-para-cadastrar-solicitacoes').dialog('open');
                });
            });

        </script>

    </head>
    <body>
        <!--Formulario-->
        <div class="div-form-dialog" id="div-form-cadastrar-solicitacoes">
            <form id="solicitacao_sic" enctype="multipart/form-data" method="POST" action="modelos/sic/adicionar.php">
                <div class="row">
                    <label class="label">*PROTOCOLO CGU:</label>
                    <span class="conteudo">
                        <input type="text" id="NUMERO_CADASTRAR_SOLICITACOES" name="numero" maxlength="20" onKeyUp="DigitaLetraSeguro(this)">
                    </span>
                </div>

                <div class="row">
                    <label>*DATA DO DOCUMENTO:</label>
                    <span class="conteudo">
                        <input type="text" id="DATA_DOCUMENTO_CADASTRAR_SOLICITACOES" name="data_documento" maxlength="10" readonly="true">
                    </span>
                </div>

                <div class="row">
                    <label>*ASSUNTO:</label>
                    <span class="conteudo">
                        <select id="ASSUNTO_CADASTRAR_SOLICITACOES" name="assunto"></select>
                    </span>
                    <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-assunto-cadastrar-solicitacoes" src="imagens/fam/application_edit.png">
                </div>

                <div class="row">
                    <label>CPF ou CNPJ:</label>
                    <span class="conteudo">
                        <select class="FUNDOCAIXA1" id="combo_cpf_interessado_sic">
                            <option selected value="0">Nenhum</option>
                            <option value="cpf">CPF</option>
                            <option value="cnpj">CNPJ</option>
                        </select>
                    </span>
                </div>

                <div class="row" id="cpf_cnpj_sic">
                    <label id="cpf_cnpj_label_sic">CPF INTERESSADO:</label>
                    <span class="conteudo">
                        <input type="text" maxlength="100" id="CPF_INTERESSADO_CADASTRAR_SOLICITACOES" name="cpf">
                    </span>
                </div>

                <div class="row">
                    <label>*NOME INTERESSADO:</label>
                    <span class="conteudo">
                        <input type="text" onKeyUp="DigitaLetraSeguro(this)" maxlength="100" id="NOME_INTERESSADO_CADASTRAR_SOLICITACOES" name="nome">
                    </span>
                </div>

                <div class="row">
                    <label>EMAIL INTERESSADO:</label>
                    <span class="conteudo">
                        <input type="text" maxlength="100" id="EMAIL_INTERESSADO_CADASTRAR_SOLICITACOES" name="email">
                    </span>
                </div>

                <div class="row">
                    <label>*DATA DO PRAZO:</label>
                    <span class="conteudo">
                        <input type="text" id="PRAZO_CADASTRAR_SOLICITACOES" maxlength="10" readonly="true" name="prazo">
                    </span>
                </div>

                <div class="row">
                    <label>*TRAMITAR PARA:</label>
                    <span class="conteudo">
                        <select id="TRAMITAR_PARA_CADASTRAR_SOLICITACOES" class="FUNDOCAIXA1" name="id_unidade_destino"></select>
                    </span>
                </div>

                <div class="row">
                    <label style="display: inline-block; vertical-align: top;">*CONTEÚDO DA SOLICITAÇÃO:</label>
                    <span class="conteudo">
                        <textarea cols="1" rows="1"  id="OCR_CADASTRAR_SOLICITACOES" class="FUNDOCAIXA1" style="height: 180; width: 64%" name="conteudo"></textarea>
                    </span>
                </div>

                <div class="row">
                    <label>ARQUIVOS ANEXOS (PDF):</label>
                    <span class="conteudo">
                        <input type="file" multiple="multiple" id="FILES_CADASTRAR_SOLICITACOES" class="FUNDOCAIXA1" style="width: 401px;" name="anexos[]" />
                    </span>
                </div>

                <div class="progress">
                    <div class="bar"></div>
                    <div class="percent">0%</div>
                </div>
            </form>
        </div>

        <div id="box-filtro-assunto-cadastrar-solicitacoes" class="box-filtro">
            <div class="row">
                <label>ASSUNTO:</label>
                <div class="conteudo">
                    <input id="FILTRO_ASSUNTO_CADASTRAR_SOLICITACOES" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>
    </body>
</html>