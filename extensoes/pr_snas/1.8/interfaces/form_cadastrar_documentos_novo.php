
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
<?php
$auth = Zend_Auth::getInstance()->getStorage()->read();
?>
<html>
    <head>

        <script type="text/javascript">

            $(document).ready(function() {
                /*Listeners*/
                /*Bind Dialog Close*/
                $("#box-filtro-origem-cadastrar-documentos").bind("dialogclose", function(event, ui) {
                    var exists = false;
                    var c = false;
                    if ($('#TIPO_ORIGEM_CADASTRAR_DOCUMENTOS').val() != 'IN' && $('#TIPO_ORIGEM_CADASTRAR_DOCUMENTOS').val() != 'PR') {
                        $("#ORIGEM_CADASTRAR_DOCUMENTOS option").each(function() {
                            if ($('#FILTRO_ORIGEM_CADASTRAR_DOCUMENTOS').val() == $(this).text()) {
                                exists = true;
                            }
                        });
                        if (!exists) {

                            if ($('#ORIGEM_CADASTRAR_DOCUMENTOS option').length > 0) {
                                c = confirm('Atenção: ' + $('#ORIGEM_CADASTRAR_DOCUMENTOS option').length + ' Origem(s) localizada(s). Mas nao sao identicas a origem informada (' + $('#FILTRO_ORIGEM_CADASTRAR_DOCUMENTOS').val() + ') !\nDeseja adiciona-la na base de dados de origens de documentos?');
                            } else {
                                c = confirm('Esta origem nao foi encontrada!\nDeseja adiciona-la na base de dados de origens de documentos?');
                            }


                            if (c) {
                                $.post("modelos/documentos/pessoa.php", {
                                    acao: 'adicionar',
                                    pessoa: $('#FILTRO_ORIGEM_CADASTRAR_DOCUMENTOS').val(),
                                    tipo: $('#TIPO_ORIGEM_CADASTRAR_DOCUMENTOS').val()
                                },
                                function(data) {
                                    try {
                                        if (data.success == 'true') {
                                            var options = $('#ORIGEM_CADASTRAR_DOCUMENTOS').attr('options');
                                            $('option', '#ORIGEM_CADASTRAR_DOCUMENTOS').remove();
                                            options[0] = new Option(data.pessoa, data.pessoa);
                                        } else {
                                            alert(data.error);
                                        }
                                    } catch (e) {
                                        alert('Ocorreu um erro ao tentar adicionar esta origem!\n[' + e + ']');
                                    }
                                }, "json");
                            }
                        }
                    }
                });

                $("#box-filtro-destino-cadastrar-documentos").bind("dialogclose", function(event, ui) {
                    var exists = false;
                    var c = false;
                    if ($('#TIPO_DESTINO_CADASTRAR_DOCUMENTOS').val() != 'IN' && $('#TIPO_DESTINO_CADASTRAR_DOCUMENTOS').val() != 'PR') {
                        $("#DESTINO_CADASTRAR_DOCUMENTOS option").each(function() {
                            if ($('#FILTRO_DESTINO_CADASTRAR_DOCUMENTOS').val() == $(this).text()) {
                                exists = true;
                            }
                        });
                        if (!exists) {

                            if ($('#DESTINO_CADASTRAR_DOCUMENTOS option').length > 0) {
                                c = confirm('Atenção: ' + $('#DESTINO_CADASTRAR_DOCUMENTOS option').length + ' Destino(s) localizado(s). Mas nao sao identicos ao destino informado (' + $('#FILTRO_DESTINO_CADASTRAR_DOCUMENTOS').val() + ') !\nDeseja adiciona-lo na base de dados de destinos de documentos?');
                            } else {
                                c = confirm('Este destino nao foi encontrado!\nDeseja adiciona-lo na base de dados de destinos de documentos?');
                            }


                            if (c) {
                                $.post("modelos/documentos/pessoa.php", {
                                    acao: 'adicionar',
                                    pessoa: $('#FILTRO_DESTINO_CADASTRAR_DOCUMENTOS').val(),
                                    tipo: $('#TIPO_DESTINO_CADASTRAR_DOCUMENTOS').val()
                                },
                                function(data) {
                                    try {
                                        if (data.success == 'true') {
                                            var options = $('#DESTINO_CADASTRAR_DOCUMENTOS').attr('options');
                                            $('option', '#DESTINO_CADASTRAR_DOCUMENTOS').remove();
                                            options[0] = new Option(data.pessoa, data.pessoa);
                                        } else {
                                            alert(data.error);
                                        }
                                    } catch (e) {
                                        alert('Ocorreu um erro ao tentar adicionar esta destino!\n[' + e + ']');
                                    }
                                }, "json");
                            }
                        }
                    }
                });

                $('#DIGITAL_CADASTRAR_DOCUMENTOS').keyup(function() {
                    if ($(this).val().length == 7) {
                        try {
                            if (jquery_validar_digital($(this).val())) {
                                if ($('#TIPO_CADASTRAR_DOCUMENTOS').find('option').length < 1) {
                                    /*Carregar o combo de tipologias de documentos*/
                                    $('#TIPO_CADASTRAR_DOCUMENTOS').combobox('modelos/combos/tipologias_documentos.php');
                                }
                            } else {
                                alert('Digital inválida!');
                                $(this).val('');
                            }
                            ;
                        } catch (e) {
                            alert(e);
                        }
                    }
                });

                $('#PROCEDENCIA_CADASTRAR_DOCUMENTOS').change(function() {
                    switch ($(this).val()) {
                        case 'I':
                            $('#RECEBIDO_POR_CADASTRAR_DOCUMENTOS').attr('disabled', 'disabled');
                            $('#DATA_ENTRADA_CADASTRAR_DOCUMENTOS').attr('disabled', 'disabled');
                            $('#RECEBIDO_POR_CADASTRAR_DOCUMENTOS').html('');
                            $('#DATA_ENTRADA_CADASTRAR_DOCUMENTOS').val('');
                            break;

                        case 'E':
                            $('#RECEBIDO_POR_CADASTRAR_DOCUMENTOS').removeAttr('disabled');
                            $('#RECEBIDO_POR_CADASTRAR_DOCUMENTOS').append("<option selected='selected'><?php print_r($auth->NOME); ?></option>");
                            $('#DATA_ENTRADA_CADASTRAR_DOCUMENTOS').removeAttr('disabled');
                            break;

                        default:
                            break;
                    }
                });
            });

            /*Validar Campos Cadastro Documento*/
            function jquery_validar_campos_cadastrar_documentos() {
                /*Validar tipo de origem procuradorias*/
                if ($('#TIPO_ORIGEM_CADASTRAR_DOCUMENTOS').val() == 'PR' && $('#PRAZO_CADASTRAR_DOCUMENTOS').val().length != 10) {
                    return 'Ao escolher o tipo de origem "Procuradorias Federais" a campo prazo torna-se obrigatorio!';
                }
                /*Validar campos*/
                if (
                        (
                                $('#DIGITAL_CADASTRAR_DOCUMENTOS').val().length == 7 &&
                                $('#TIPO_CADASTRAR_DOCUMENTOS').val() &&
                                jQuery.trim($('#NUMERO_CADASTRAR_DOCUMENTOS').val()) &&
                                $('#ORIGEM_CADASTRAR_DOCUMENTOS').val() &&
                                jQuery.trim($('#DATA_DOCUMENTO_CADASTRAR_DOCUMENTOS').val()) &&
                                $('#DESTINO_CADASTRAR_DOCUMENTOS').val() &&
                                jQuery.trim($('#TECNICO_RESPONSAVEL_CADASTRAR_DOCUMENTOS').val()) &&
                                $('#ASSUNTO_CADASTRAR_DOCUMENTOS').val() &&
                                $('#PROCEDENCIA_CADASTRAR_DOCUMENTOS').val() == 'I'
                                ) || (
                        $('#DIGITAL_CADASTRAR_DOCUMENTOS').val().length == 7 &&
                        $('#TIPO_CADASTRAR_DOCUMENTOS').val() &&
                        jQuery.trim($('#NUMERO_CADASTRAR_DOCUMENTOS').val()) &&
                        $('#ORIGEM_CADASTRAR_DOCUMENTOS').val() &&
                        jQuery.trim($('#DATA_DOCUMENTO_CADASTRAR_DOCUMENTOS').val()) &&
                        $('#DESTINO_CADASTRAR_DOCUMENTOS').val() &&
                        jQuery.trim($('#TECNICO_RESPONSAVEL_CADASTRAR_DOCUMENTOS').val()) &&
                        $('#ASSUNTO_CADASTRAR_DOCUMENTOS').val() &&
                        $('#PROCEDENCIA_CADASTRAR_DOCUMENTOS').val() == 'E' &&
                        $('#DATA_ENTRADA_CADASTRAR_DOCUMENTOS').val() &&
                        $('#RECEBIDO_POR_CADASTRAR_DOCUMENTOS').val()
                        )
                        ) {
                    /*Validar duplicidade de documento*/
                    var id_doc = 0;
                    $.ajaxSetup({async: false});
                    var retorno = false;
                    $.post("modelos/documentos/documentos.php", {
                        acao: 'unique',
                        id: id_doc,
                        tipo: $('#TIPO_CADASTRAR_DOCUMENTOS').val(),
                        numero: $('#NUMERO_CADASTRAR_DOCUMENTOS').val(),
                        origem: $('#ORIGEM_CADASTRAR_DOCUMENTOS').val()
                    },
                    function(data) {
                        if (data.success == 'true') {
                            retorno = true;
                        } else {
                            retorno = data.error;
                        }
                    }, "json");
                    if (retorno == true) {
                        /*Validar o digital*/
                        if (jquery_validar_digital($('#DIGITAL_CADASTRAR_DOCUMENTOS').val())) {
                            return true;
                        } else {
                            return 'Digital Invalido!';
                        }
                    } else {
                        return retorno;
                    }

                } else {
                    alert('Campo(s) obrigatório(s) em branco ou preenchidos de forma inválidas!');

                    if ($('#DIGITAL_CADASTRAR_DOCUMENTOS').val().length !== 7) {
                        $('#DIGITAL_CADASTRAR_DOCUMENTOS').focus();
                        return null;
                    }
                    if ($('#TIPO_CADASTRAR_DOCUMENTOS').val() == '') {
                        $('#TIPO_CADASTRAR_DOCUMENTOS').focus();
                        return null;
                    }
                    if (jQuery.trim($('#NUMERO_CADASTRAR_DOCUMENTOS').val()) == '') {
                        $('#NUMERO_CADASTRAR_DOCUMENTOS').focus();
                        return null;
                    }
                    if ($('#ORIGEM_CADASTRAR_DOCUMENTOS').val() == null) {
                        $('#ORIGEM_CADASTRAR_DOCUMENTOS').focus();
                        return null;
                    }
                    if (jQuery.trim($('#DATA_DOCUMENTO_CADASTRAR_DOCUMENTOS').val()) == '') {
                        $('#DATA_DOCUMENTO_CADASTRAR_DOCUMENTOS').focus();
                        return null;
                    }
                    if ($('#DESTINO_CADASTRAR_DOCUMENTOS').val() == null) {
                        $('#DESTINO_CADASTRAR_DOCUMENTOS').focus();
                        return null;
                    }
                    if (jQuery.trim($('#TECNICO_RESPONSAVEL_CADASTRAR_DOCUMENTOS').val()) == '') {
                        $('#TECNICO_RESPONSAVEL_CADASTRAR_DOCUMENTOS').focus();
                        return null;
                    }
                    if ($('#ASSUNTO_CADASTRAR_DOCUMENTOS').val() == null) {
                        $('#ASSUNTO_CADASTRAR_DOCUMENTOS').focus();
                        return null;
                    }
                    if ($('#PROCEDENCIA_CADASTRAR_DOCUMENTOS').val() == '') {
                        $('#PROCEDENCIA_CADASTRAR_DOCUMENTOS').focus();
                        return null;
                    }
                    if ($('#PROCEDENCIA_CADASTRAR_DOCUMENTOS').val() == 'E') {
                        if ($('#DATA_ENTRADA_CADASTRAR_DOCUMENTOS').val() == '') {
                            $('#DATA_ENTRADA_CADASTRAR_DOCUMENTOS').focus();
                            return null;
                        }
                        if (jQuery.trim($('#RECEBIDO_POR_CADASTRAR_DOCUMENTOS').val()) == null) {
                            $('#RECEBIDO_POR_CADASTRAR_DOCUMENTOS').focus();
                            return null;
                        }
                    }

                    return null;
                }
            }// \function jquery_validar_campos_cadastrar_documentos()

            /*Validar digital*/
            function jquery_validar_digital(digital) {

                var r = $.ajax({
                    type: 'POST',
                    url: 'modelos/documentos/validar_digital.php',
                    data: 'digital=' + digital,
                    async: false,
                    success: function() {
                    },
                    failure: function() {
                    }
                }).responseText;

                r = eval('(' + r + ')');

                if (r.success == 'true') {
                    return r.valid;
                } else {
                    throw 'Ocorreu um erro ao tentar validar digital!\n[' + r.error + ']';
                    return false;
                }

            }
            /*Inserir documento*/
            function jquery_cadastrar_documento() {
                if (confirm('Você tem certeza que deseja cadastrar este documento?')) {
                    $("#progressbar").show();
                    $.post("modelos/documentos/cadastrar.php", {
                        digital: $('#DIGITAL_CADASTRAR_DOCUMENTOS').val(),
                        tipo: $('#TIPO_CADASTRAR_DOCUMENTOS').val(),
                        numero: $('#NUMERO_CADASTRAR_DOCUMENTOS').val(),
                        origem: $('#ORIGEM_CADASTRAR_DOCUMENTOS').val(),
                        data_documento: $('#DATA_DOCUMENTO_CADASTRAR_DOCUMENTOS').val(),
                        destino: $('#DESTINO_CADASTRAR_DOCUMENTOS').val(),
                        tecnico_responsavel: $('#TECNICO_RESPONSAVEL_CADASTRAR_DOCUMENTOS').val(),
                        assunto: $('#ASSUNTO_CADASTRAR_DOCUMENTOS').val(),
                        assunto_complementar: $('#ASSUNTO_COMPLEMENTAR_CADASTRAR_DOCUMENTOS').val(),
                        assinatura: $('#ASSINATURA_CADASTRAR_DOCUMENTOS').val(),
                        interessado: $('#INTERESSADO_CADASTRAR_DOCUMENTOS').val(),
                        procedencia: $('#PROCEDENCIA_CADASTRAR_DOCUMENTOS').val(),
                        data_entrada: $('#DATA_ENTRADA_CADASTRAR_DOCUMENTOS').val(),
                        recibo: $('#RECEBIDO_POR_CADASTRAR_DOCUMENTOS').val(),
                        cargo: $('#CARGO_CADASTRAR_DOCUMENTOS').val(),
                        prazo: $('#PRAZO_CADASTRAR_DOCUMENTOS').val()
                    },
                    function(data) {
                        try {
                            if (data.success == 'true') {
                                /*Liberar Campos*/
                                $('#TIPO_CADASTRAR_DOCUMENTOS').removeAttr('disabled');
                                $('#NUMERO_CADASTRAR_DOCUMENTOS').removeAttr('disabled', 'disabled');

                                /*Limpar Campos*/
                                $('#DIGITAL_CADASTRAR_DOCUMENTOS').val('');
                                //$('#TIPO_CADASTRAR_DOCUMENTOS').empty();
                                $('#NUMERO_CADASTRAR_DOCUMENTOS').val('');
                                $('#ORIGEM_CADASTRAR_DOCUMENTOS').empty();
                                $('#DATA_DOCUMENTO_CADASTRAR_DOCUMENTOS').val('');
                                $('#DESTINO_CADASTRAR_DOCUMENTOS').empty();
                                $('#TECNICO_RESPONSAVEL_CADASTRAR_DOCUMENTOS').val('');
                                $('#ASSUNTO_CADASTRAR_DOCUMENTOS').empty()
                                $('#ASSUNTO_COMPLEMENTAR_CADASTRAR_DOCUMENTOS').val('');
                                $('#INTERESSADO_CADASTRAR_DOCUMENTOS').val('');
                                $('#ASSINATURA_CADASTRAR_DOCUMENTOS').val('');
                                $('#CARGO_CADASTRAR_DOCUMENTOS').val('');
                                $('#PRAZO_CADASTRAR_DOCUMENTOS').val('');
                                $('#TIPO_ORIGEM_CADASTRAR_DOCUMENTOS').val('IN');
                                $('#FILTRO_ORIGEM_CADASTRAR_DOCUMENTOS').val('');
                                $('#TIPO_DESTINO_CADASTRAR_DOCUMENTOS').val('IN');
                                $('#FILTRO_DESTINO_CADASTRAR_DOCUMENTOS').val('');
                                $('#FILTRO_ASSUNTO_CADASTRAR_DOCUMENTOS').val('');
                                $('#FILTRO_RECEBIDO_POR_CADASTRAR_DOCUMENTOS').val('');

                                $('#PROCEDENCIA_CADASTRAR_DOCUMENTOS').val('I');
                                $('#DATA_ENTRADA_CADASTRAR_DOCUMENTOS').val('');
                                $('#RECEBIDO_POR_CADASTRAR_DOCUMENTOS').val('');
                                /*Alert*/
                                $('#div-form-cadastrar-documentos').dialog('close');
                                oTableDocumentos.fnDraw(false);
                                if (confirm('Documento cadastrado com sucesso! \n Deseja cadastrar demandas?')) {
                                    jquery_detalhar_documento(data.digital);
                                }
                            } else {
                                alert(data.error);
                            }
                        } catch (e) {
                            alert('Ocorreu um erro ao tentar validar o digital!\n[' + e + ']');
                        }
                        $("#progressbar").hide();
                    }, "json");
                }
            }

            /*Gerar numero documento*/
            function jquery_gerar_numero_documento(tipo) {
                if (confirm('Você tem certeza que deseja gerar um novo numero para a tipologia "' + tipo + '"?\nAtenção! Este procedimento nao pode ser desfeito!')) {
                    $.post("modelos/documentos/gerar_numeracao.php", {
                        tipologia: tipo
                    },
                    function(data) {
                        try {
                            if (data.success == 'true') {
                                if (data.numero) {
                                    $('#NUMERO_CADASTRAR_DOCUMENTOS').val(data.numero);
                                    $('#NUMERO_CADASTRAR_DOCUMENTOS').attr('disabled', 'disabled');
                                    $('#TIPO_CADASTRAR_DOCUMENTOS').attr('disabled', 'disabled');
                                    // $('#botao-gerar-numero-cadastrar-documentos').attr('disabled','disabled');
                                }
                            } else {
                                alert(data.error);
                            }
                        } catch (e) {
                            alert('Ocorreu um erro ao tentar inclementar o numero da tipologia escolhidal!\n[' + e + ']');
                        }
                    }, "json");
                }
            }

            $(document).ready(function() {
                /*Auto Completes*/
                $("#FILTRO_DESTINO_CADASTRAR_DOCUMENTOS").autocompleteonline({
                    idTypeField: 'TIPO_DESTINO_CADASTRAR_DOCUMENTOS',
                    idComboBox: 'DESTINO_CADASTRAR_DOCUMENTOS',
                    url: 'modelos/combos/autocomplete.php',
                    paramTypeName: 'type',
                    extraParams: {
                        action: 'documentos-origens'
                    }
                });

                $("#FILTRO_ORIGEM_CADASTRAR_DOCUMENTOS").autocompleteonline({
                    idTypeField: 'TIPO_ORIGEM_CADASTRAR_DOCUMENTOS',
                    idComboBox: 'ORIGEM_CADASTRAR_DOCUMENTOS',
                    url: 'modelos/combos/autocomplete.php',
                    paramTypeName: 'type',
                    extraParams: {
                        action: 'documentos-origens'
                    }
                });

                $("#FILTRO_ASSUNTO_CADASTRAR_DOCUMENTOS").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox: 'ASSUNTO_CADASTRAR_DOCUMENTOS',
                    extraParams: {
                        action: 'documentos-assuntos'
                    }
                });

                $("#FILTRO_RECEBIDO_POR_CADASTRAR_DOCUMENTOS").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox: 'RECEBIDO_POR_CADASTRAR_DOCUMENTOS',
                    extraParams: {
                        action: 'documentos-recebido-por'
                    }
                });
                /*Calendarios*/
                $("#PRAZO_CADASTRAR_DOCUMENTOS").datepicker({
                    changeMonth: true,
                    changeYear: true
                });

                $("#DATA_DOCUMENTO_CADASTRAR_DOCUMENTOS").datepicker({
                    defaultDate: new Date(),
                    changeMonth: true,
                    changeYear: true,
                    onClose: function(selectedDate) {
                        $("#DATA_ENTRADA_CADASTRAR_DOCUMENTOS").datepicker("option", "minDate", selectedDate);
                    }
                });

                $("#DATA_ENTRADA_CADASTRAR_DOCUMENTOS").datepicker({
                    defaultDate: new Date(),
                    changeMonth: true,
                    changeYear: true,
                });

                $('#box-filtro-origem-cadastrar-documentos').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 120
                });

                $('#box-filtro-destino-cadastrar-documentos').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 120
                });

                $('#box-filtro-assunto-cadastrar-documentos').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 90
                });

                $('#box-filtro-recebido-por-cadastrar-documentos').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 90
                });

                $('#div-form-cadastrar-documentos').dialog({
                    title: 'Novo Documento',
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    width: 650,
                    height: 600,
                    buttons: {
                        Salvar: function() {
                            var validou = jquery_validar_campos_cadastrar_documentos();
                            if (validou == true) {
                                jquery_cadastrar_documento();
                            } else {
                                if (validou) {
                                    alert(validou);
                                }
                            }
                        },
                        Cancelar: function() {
                            $('#box-filtro-origem-cadastrar-documentos').dialog('close');
                            $('#box-filtro-destino-cadastrar-documentos').dialog('close');
                            $('#box-filtro-assunto-cadastrar-documentos').dialog('close');
                            /*Liberar campos tipo e numero possivelmente bloqueados*/
                            $('#NUMERO_CADASTRAR_DOCUMENTOS').removeAttr('disabled').val('');
                            $('#TIPO_CADASTRAR_DOCUMENTOS').removeAttr('disabled').val('');
                            $(this).dialog('close');
                        }
                    }
                });
                /*Ativar filtros*/
                $('#botao-filtro-origem-cadastrar-documentos').click(function() {
                    $('#box-filtro-origem-cadastrar-documentos').dialog('open');
                });

                $('#botao-filtro-assunto-cadastrar-documentos').click(function() {
                    $('#box-filtro-assunto-cadastrar-documentos').dialog('open');
                });

                $('#botao-filtro-recebido-por-cadastrar-documentos').click(function() {
                    $('#box-filtro-recebido-por-cadastrar-documentos').dialog('open');
                });

                $('#botao-filtro-destino-cadastrar-documentos').click(function() {
                    $('#box-filtro-destino-cadastrar-documentos').dialog('open');
                });
                /*Abrir form novo documento*/
                $('#botao-documento-novo-cadastrar-documentos').click(function() {
                    /*Previnir que a solicitacao ocorra sem necessidade*/
                    //                    if($('#TIPO_CADASTRAR_DOCUMENTOS').find('option').length<1){
                    //                        /*Carregar o combo de tipologias de documentos*/
                    //                        $('#TIPO_CADASTRAR_DOCUMENTOS').combobox('modelos/combos/tipologias_documentos.php');
                    //                    }
                    $('#div-form-cadastrar-documentos').dialog('open');
                });
                /*Botao limpar*/
                $('#botao-limpar-data-entrada-cadastrar-documentos').click(function() {
                    $('#DATA_ENTRADA_CADASTRAR_DOCUMENTOS').val('');
                });
                $('#botao-limpar-prazo-cadastrar-documentos').click(function() {
                    $('#PRAZO_CADASTRAR_DOCUMENTOS').val('');
                });
                /*Botao gerar numero*/
                $('#botao-gerar-numero-cadastrar-documentos').click(function() {
                    if ($('#TIPO_CADASTRAR_DOCUMENTOS').val()) {
                        jquery_gerar_numero_documento($('#TIPO_CADASTRAR_DOCUMENTOS').val());
                    } else {
                        alert('Primeiro selecione uma tipologia valida!');
                    }
                });
                /*Mudar procedencia*/
            });

        </script>

    </head>

    <body>
        <!--Formulario-->
        <div class="div-form-dialog" id="div-form-cadastrar-documentos">

            <div class="row">
                <label class="label">*DIGITAL:</label>
                <span class="conteudo">
                    <input type="text" id="DIGITAL_CADASTRAR_DOCUMENTOS" maxlength="7" onKeyPress="DigitaNumero(this);">
                </span>
            </div>

            <div class="row">
                <label class="label">*TIPO:</label>
                <span class="conteudo">
                    <select class='FUNDOCAIXA1' id='TIPO_CADASTRAR_DOCUMENTOS'></select>
                </span>
            </div>

            <div class="row">
                <label class="label">*NUMERO:</label>
                <span class="conteudo">
                    <input type="text" id="NUMERO_CADASTRAR_DOCUMENTOS" maxlength="60" <?php isset($GERADO) ? print $GERADO  : ''; ?> onKeyUp="DigitaLetraSeguro(this)">
                </span>
                <img title="Gerar Numero" class="botao-auxiliar" id="botao-gerar-numero-cadastrar-documentos" src="imagens/fam/add.png">
            </div>

            <div class="row">
                <label>*ORIGEM:</label>
                <span class="conteudo">
                    <select id="ORIGEM_CADASTRAR_DOCUMENTOS"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-origem-cadastrar-documentos" src="imagens/fam/application_edit.png">
            </div>

            <div class="row">
                <label>*DATA DO DOCUMENTO:</label>
                <span class="conteudo">
                    <input type="text" id="DATA_DOCUMENTO_CADASTRAR_DOCUMENTOS" maxlength="10" readonly="true" />
                    <input type="hidden" id="DIRETORIA" value="<?php print($auth->ID_UNIDADE); ?>" maxlength="120" <?php isset($GERADO) ? print $GERADO  : ''; ?> />
                </span>
            </div>

            <div class="row">
                <label>*DESTINO:</label>
                <span class="conteudo">
                    <select id="DESTINO_CADASTRAR_DOCUMENTOS"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-destino-cadastrar-documentos" src="imagens/fam/application_edit.png">
            </div>

            <div class="row">
                <label>*ENCAMINHADO PARA:</label>
                <span class="conteudo">
                    <input type="text" onKeyUp="DigitaLetraSeguro(this)" maxlength="60" id="TECNICO_RESPONSAVEL_CADASTRAR_DOCUMENTOS">
                </span>
            </div>

            <div class="row">
                <label>*ASSUNTO:</label>
                <span class="conteudo">
                    <select id="ASSUNTO_CADASTRAR_DOCUMENTOS"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-assunto-cadastrar-documentos" src="imagens/fam/application_edit.png">
            </div>

            <div class="row">
                <label>ASSUNTO COMPLEMENTAR:</label>
                <span class="conteudo">
                    <input type="text" onKeyUp="DigitaLetraSeguro(this)" id="ASSUNTO_COMPLEMENTAR_CADASTRAR_DOCUMENTOS">
                </span>
            </div>

            <div class="row">
                <label>INTERESSADO:</label>
                <span class="conteudo">
                    <input type="text" onKeyUp="DigitaLetraSeguro(this)" maxlength="100" id="INTERESSADO_CADASTRAR_DOCUMENTOS">
                </span>
            </div>

            <div class="row">
                <label>ASSINATURA:</label>
                <span class="conteudo">
                    <input type="text" onKeyUp="DigitaLetraSeguro(this)" id="ASSINATURA_CADASTRAR_DOCUMENTOS" maxlength="60">
                </span>
            </div>

            <div class="row">
                <label>CARGO:</label>
                <span class="conteudo">
                    <input type="text" onKeyUp="DigitaLetraSeguro(this)" id="CARGO_CADASTRAR_DOCUMENTOS" maxlength="60">
                </span>
            </div>

            <div class="row">
                <label>DATA DO PRAZO:</label>
                <span class="conteudo">
                    <input type="text" id="PRAZO_CADASTRAR_DOCUMENTOS" maxlength="10" readonly="true">
                </span>
                <img title="Limpar" class="botao-auxiliar" src="imagens/fam/delete.png" id="botao-limpar-prazo-cadastrar-documentos">
            </div>

            <div class="row">
                <label>*PROCEDENCIA:</label>
                <span class="conteudo">
                    <select id="PROCEDENCIA_CADASTRAR_DOCUMENTOS">
                        <option value="I">Interno</option>
                        <option value="E">Externo</option>
                    </select>
                </span>
            </div>

            <div class="row">
                <label>**DATA ENTRADA:</label>
                <span class="conteudo">
                    <input type="text" disabled id="DATA_ENTRADA_CADASTRAR_DOCUMENTOS" maxlength="10" readonly="true">
                </span>
                <img title="Limpar" class="botao-auxiliar" src="imagens/fam/delete.png" id="botao-limpar-data-entrada-cadastrar-documentos">
            </div>

            <div class="row">
                <label>**RECEBIDO POR:</label>
                <span class="conteudo">
                    <select id="RECEBIDO_POR_CADASTRAR_DOCUMENTOS"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-recebido-por-cadastrar-documentos" src="imagens/fam/application_edit.png">
            </div>
        </div>

        <!-- Filtros -->
        <div id="box-filtro-origem-cadastrar-documentos" class="box-filtro">
            <div class="row">
                <label>Tipo de Origem:</label>
                <div class="conteudo">
                    <select id="TIPO_ORIGEM_CADASTRAR_DOCUMENTOS" class="FUNDOCAIXA1">
                        <option value="IN">Unidades Organizacionais</option>
                        <option value="PR">Procuradorias Federais</option>
                        <option value="PF">Pessoa Fisica</option>
                        <option value="PJ">Pessoa Juridica</option>
                        <option value="OF">Outros Orgaos</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <label>Origem:</label>
                <div class="conteudo">
                    <input id="FILTRO_ORIGEM_CADASTRAR_DOCUMENTOS" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>

        <div id="box-filtro-destino-cadastrar-documentos" class="box-filtro">
            <div class="row">
                <label>Tipo de Destino:</label>
                <div class="conteudo">
                    <select id="TIPO_DESTINO_CADASTRAR_DOCUMENTOS" class="FUNDOCAIXA1">
                        <option value="IN">Unidades Organizacionais</option>
                        <option value="PR">Procuradorias Federais</option>
                        <option value="PF">Pessoa Fisica</option>
                        <option value="PJ">Pessoa Juridica</option>
                        <option value="OF">Outros Orgaos</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <label>Destino:</label>
                <div class="conteudo">
                    <input id="FILTRO_DESTINO_CADASTRAR_DOCUMENTOS" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>

        <div id="box-filtro-assunto-cadastrar-documentos" class="box-filtro">
            <div class="row">
                <label>Assunto:</label>
                <div class="conteudo">
                    <input id="FILTRO_ASSUNTO_CADASTRAR_DOCUMENTOS" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>

        <div id="box-filtro-recebido-por-cadastrar-documentos" class="box-filtro">
            <div class="row">
                <label>RECEBIDO POR:</label>
                <div class="conteudo">
                    <input id="FILTRO_RECEBIDO_POR_CADASTRAR_DOCUMENTOS" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>

    </body>
</html>