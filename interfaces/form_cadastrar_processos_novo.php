
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
        <style type="text/css">
            .aux-combo-cpf{
                position: absolute;
                right: 18px;
                bottom: 8px;
            }
        </style>
        <script type="text/javascript">

            var boxInteressadoOpen = false;

            /*Inicio JQuery*/
            $(document).ready(function() {

                /*Validar numero processo*/
                function jquery_validar_numero_processo(field) {
                    try {
                        /*Esta variavel sera true quando a validacao local do numero do processo for valida*/
                        var valido = false;

                        switch ($(field).val().length) {
                            case 20:
                                if (isInteger(($(field).val().substr(13, 4)))) {
                                    /*Processos de 1999 ou anteriores*/
                                    if ($(field).val().substr(13, 4) <= 1999) {
                                        alert('Os processos gerados em 1999 ou anteriomente devem possuir 2 digitos para representar o ano da autuação.');
                                        $(field).focus();
                                        $(field).val('');
                                        return false;
                                    } else {
                                        valido = true;
                                    }

                                    /*Processos de 2003 ou posteriores*/
                                    if ($(field).val().substr(13, 4) >= 2003) {
                                        if (!validar_digito_verificador_processo(document.getElementById('NUMERO_PROCESSO_CADASTRO'))) {
                                            alert('Numero do processo invalido!\nObs: O digito correto será : [' + verificador_valido(document.getElementById('NUMERO_PROCESSO_CADASTRO')) + ']');
                                            $(field).focus();
                                            $(field).val('');
                                            return false;
                                        } else {
                                            valido = true;
                                        }

                                    }

                                    /*Processo entre 2000 e 2002*/
                                    if ($(field).val().substr(13, 4) >= 2000 && $(field).val().substr(13, 4) <= 2002) {
                                        if (!validar_digito_verificador_processo(document.getElementById('NUMERO_PROCESSO_CADASTRO'))) {
                                            alert('Numero do processo invalido!\nObs: O digito correto será : [' + verificador_valido(document.getElementById('NUMERO_PROCESSO_CADASTRO')) + ']');
                                            $(field).focus();
                                            $(field).val('');
                                            return false;
                                        } else {
                                            valido = true;
                                        }
                                    }

                                } else {
                                    alert('Número de processo inválido!');
                                    $(field).focus();
                                    $(field).val('');
                                    return false;
                                }
                                break;

                            case 18:
                                if (isInteger($(field).val().substr(13, 2))) {
                                    /*Processo entre 1940 e 1999*/
                                    if ($(field).val().substr(13, 2) >= 40 && $(field).val().substr(13, 2) <= 99) {
                                        if (!validar_digito_verificador_processo(document.getElementById('NUMERO_PROCESSO_CADASTRO'))) {
                                            alert('Numero do processo invalido!\nObs: O digito correto será : [' + verificador_valido(document.getElementById('NUMERO_PROCESSO_CADASTRO')) + ']');
                                            $(field).focus();
                                            $(field).val('');
                                            return false;
                                        } else {
                                            valido = true;
                                        }
                                    }
                                    /*Processos entre 2003 e 2039*/
                                    if ($(field).val().substr(13, 2) >= 03 && $(field).val().substr(13, 2) <= 39) {
                                        alert('Os processos gerados em 2003 ou posteriomente devem possuir 4 digitos para representar o ano da autuação.');
                                        $(field).focus();
                                        $(field).val('');
                                        return false;
                                    } else {
                                        valido = true;
                                    }
                                    /*Processos entre 2000 e 2002*/
                                    if ($(field).val().substr(13, 2) >= 00 && $(field).val().substr(13, 2) <= 02) {
                                        if (!validar_digito_verificador_processo(document.getElementById('NUMERO_PROCESSO_CADASTRO'))) {
                                            alert('Numero do processo invalido!\nObs: O digito correto será : [' + verificador_valido(document.getElementById('NUMERO_PROCESSO_CADASTRO')) + ']');
                                            $(field).focus();
                                            $(field).val('');
                                            return false;
                                        } else {
                                            valido = true;
                                        }
                                    }
                                } else {
                                    alert('Número de processo inválido!');
                                    $(field).focus();
                                    $(field).val('');
                                    return false;
                                }
                                break;
                            default:
                                alert('Número de processo inválido!');
                                $(field).focus();
                                $(field).val('');
                                return false;
                                break;
                        }

                        /*Se a validacao local for valida entao Verificar no banco se o processo ja foi cadastrado*/
                        if (valido == true) {
                            if (jquery_verificar_processo($(field).val()) == 'true') {
                                alert('Este processo ja esta cadastrado!');
                                $(field).focus();
                                $(field).val('');
                                return false;
                            } else {
                                return true;
                            }
                        }

                    } catch (e) {
                        alert(e);
                    }

                }

                /*Listeners Campos*/
                $('#NUMERO_PROCESSO_CADASTRO').keyup(function() {
                    formatar_numero_processo(document.getElementById('NUMERO_PROCESSO_CADASTRO'));
                });

                /*Evento Blur no campo numero do processo*/
                $('#NUMERO_PROCESSO_CADASTRO').blur(function() {
                    if ($('#NUMERO_PROCESSO_CADASTRO').val()) {
                        jquery_validar_numero_processo('#NUMERO_PROCESSO_CADASTRO');
                    }
                });

                /*Listeners carregar cpf/cnpj interessado*/
                $('#INTERESSADO_PROCESSO_CADASTRO').click(function() {
                    if ($(this).val()) {
                        jquery_carregar_cpfcnpj_interessado_processo_cadastro();
                    }
                });

                $('#INTERESSADO_PROCESSO_CADASTRO').change(function() {
                    if ($(this).val()) {
                        jquery_carregar_cpfcnpj_interessado_processo_cadastro();
                    }
                });

                /*Formatar campo cpf/cnpj interessado*/
                $("#FILTRO_CNPJ_INTERESSADO_PROCESSO_CADASTRO").mask('99.999.999/9999-99').hide();
                $("#FILTRO_CPF_INTERESSADO_PROCESSO_CADASTRO").mask('999.999.999-99').show();

                $('#combo_cpf_interessado_processo_cadastro').change(function() {
                    switch ($(this).val()) {
                        case 'cnpj':
                            $("#FILTRO_CPF_INTERESSADO_PROCESSO_CADASTRO").hide();
                            $("#FILTRO_CNPJ_INTERESSADO_PROCESSO_CADASTRO").show();
                            $("#cpf_ou_cnpj_label_cadastro").text('CNPJ');
                            break;

                        case 'cpf':
                            $("#FILTRO_CNPJ_INTERESSADO_PROCESSO_CADASTRO").hide();
                            $("#FILTRO_CPF_INTERESSADO_PROCESSO_CADASTRO").show();
                            $("#cpf_ou_cnpj_label_cadastro").text('CPF');
                            break;

                        default:
                            break;
                    }
                });

                /*AutoComplete*/
                /*Origem*/
                $("#FILTRO_ORIGEM_PROCESSO_CADASTRO").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idTypeField: 'TIPO_ORIGEM_PROCESSO_CADASTRO',
                    idComboBox: 'ORIGEM_PROCESSO_CADASTRO',
                    paramTypeName: 'type',
                    extraParams: {
                        action: 'processos-origens'
                    }
                });
                /*Assunto*/
                $("#FILTRO_ASSUNTO_PROCESSO_CADASTRO").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox: 'ASSUNTO_PROCESSO_CADASTRO',
                    extraParams: {
                        action: 'processos-assuntos'
                    }
                });
                /*Interessado*/
                $("#FILTRO_INTERESSADO_PROCESSO_CADASTRO").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox: 'INTERESSADO_PROCESSO_CADASTRO',
                    extraParams: {
                        action: 'processos-interessados'
                    }
                });
                /*CPF*/
                $("#FILTRO_CPF_INTERESSADO_PROCESSO_CADASTRO").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox: 'INTERESSADO_PROCESSO_CADASTRO',
                    setElemOnclick: false,
                    extraParams: {
                        action: 'processos-interessados'
                    }
                });
                /*CNPJ*/
                $("#FILTRO_CNPJ_INTERESSADO_PROCESSO_CADASTRO").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox: 'INTERESSADO_PROCESSO_CADASTRO',
                    setElemOnclick: false,
                    extraParams: {
                        action: 'processos-interessados'
                    }
                });

                /*Listeners*/
                /*Bind Dialog Close*/
                /*Box Interessado*/
                $("#box-filtro-interessado-cadastro-processo").bind("dialogclose", function(event, ui) {

                    /*Bug Fix :: Evitar que a mensagem de inclusao de novo interessado apareca novamente*/
                    if (!boxInteressadoOpen) {
                        return false;
                    }

                    var exists = false;
                    var c = false;
                    var cpf_cnpj;


                    /*Verificar se o filtro possui valor*/
                    if ($('#FILTRO_INTERESSADO_PROCESSO_CADASTRO').val()) {

                        /*Prevenir a ausencia de assunto na validacao*/
                        if (!$('#ASSUNTO_PROCESSO_CADASTRO').val()) {
                            alert('Antes de continuar selecione um assunto!');
                            $('#box-filtro-assunto-cadastro-processo').dialog('open');
                            $(this).dialog('open');
                            return false;
                        }

                        /*Validacao para o tipo de numero do novo interessado*/
                        if ($('#combo_cpf_interessado_processo_cadastro').find(':selected').val() == 'cpf') {
                            cpf_cnpj = $('#FILTRO_CPF_INTERESSADO_PROCESSO_CADASTRO').val();
                        } else {
                            cpf_cnpj = $('#FILTRO_CNPJ_INTERESSADO_PROCESSO_CADASTRO').val();
                        }

                        /*Validar o cpf antes de submeter*/
                        if (cpf_cnpj != '___.___.___-__' && cpf_cnpj != '__.___.___/____-__' && cpf_cnpj.length > 0) {
                            if (!jquery_validar_cpf_cnpj(cpf_cnpj)) {
                                alert('Cpf ou Cnpj invalido!');
                                $('#box-filtro-interessado-cadastro-processo').dialog('open');
                                return false;
                            }
                        }

                        /*Core da Validacao !@#$@#$%* - Inicio*/
                        $.post("modelos/processos/assunto.php", {
                            acao: 'interessado-obrigatorio',
                            assunto: $('#ASSUNTO_PROCESSO_CADASTRO').val(),
                            interessado: $('#INTERESSADO_PROCESSO_CADASTRO').val()
                        },
                        function(data) {
                            try {
                                if (data.success == 'true') {
                                    if (data.obrigatorio == 'true') {
                                        if (!jquery_validar_cpf_cnpj(cpf_cnpj)) {
                                            alert('Ao escolher o assunto ' + $('#ASSUNTO_PROCESSO_CADASTRO option:selected').text() + ' o cnpj/cpf do interessado torna-se obrigatorio!');//para o assunto escolhido eh necessario informar o cnpj/cpf valido!
                                            $('#box-filtro-interessado-cadastro-processo').dialog('open');
                                            return false;
                                        }
                                    }

                                    /*Verificar se o valor do filtro consta na relacao*/
                                    $("#INTERESSADO_PROCESSO_CADASTRO option").each(function() {
                                        if ($('#FILTRO_INTERESSADO_PROCESSO_CADASTRO').val() == $(this).text()) {
                                            exists = true;
                                        }
                                    });

                                    /*Core da Validacao !@#$@#$%* - Final*/

                                    /*Se nao existir na relacao*/
                                    if (!exists) {

                                        /*Se existir itens semelhantes "Informa" senao "Solicita a insercao"*/
                                        if ($('#INTERESSADO_PROCESSO_CADASTRO option').length > 0) {
                                            c = confirm('Atenção: ' + $('#INTERESSADO_PROCESSO_CADASTRO option').length + ' Interessado(s) localizado(s). Mas não são idênticos ao interessado informado (' + $('#FILTRO_INTERESSADO_PROCESSO_CADASTRO').val() + ') !\nDeseja adicioná-lo na base de dados de interessados?');
                                        } else {
                                            c = confirm('Esse interessado nao foi encontrado!\nDeseja adiciona-lo na base de dados de interessados?');
                                        }

                                        /*Se confirmacao insercao do interessado positiva*/
                                        if (c) {
                                            $.post("modelos/processos/interessado.php", {
                                                acao: 'adicionar',
                                                interessado: $('#FILTRO_INTERESSADO_PROCESSO_CADASTRO').val(),
                                                cpf: cpf_cnpj
                                            }, function(data) {
                                                try {
                                                    if (data.success == 'true') {
                                                        var options = $('#INTERESSADO_PROCESSO_CADASTRO').attr('options');
                                                        $('option', '#INTERESSADO_PROCESSO_CADASTRO').remove();
                                                        options[0] = new Option(data.interessado, data.id);
                                                    } else {
                                                        alert(data.error);
                                                        $('#box-filtro-interessado-cadastro-processo').dialog('open');
                                                    }
                                                } catch (e) {
                                                    alert('Ocorreu um erro ao tentar adicionar este interessado!\n[' + e + ']');
                                                }
                                            }, "json");
                                        } else {
                                            $(this).dialog('open');
                                        }
                                    }
                                } else {
                                    alert(data.error);
                                    return false;
                                }
                            } catch (e) {
                                alert('Ocorreu um erro ao validar a obrigatoriedade do cpf/cnpj do interessado para o assunto escolhido!\n[' + e + ']');
                            }
                        }, "json");
                    }
                });

                $("#box-filtro-origem-cadastro-processo").bind("dialogclose", function(event, ui) {
                    if ($('#TIPO_ORIGEM_PROCESSO_CADASTRO').val() == 'EX') {
                        var exists = false;
                        var c = false;
                        if ($('#FILTRO_ORIGEM_PROCESSO_CADASTRO').val()) {
                            $("#ORIGEM_PROCESSO_CADASTRO option").each(function() {
                                if ($('#FILTRO_ORIGEM_PROCESSO_CADASTRO').val() == $(this).text()) {
                                    exists = true;
                                }
                            });
                            if (!exists) {

                                if ($('#ORIGEM_PROCESSO_CADASTRO option').length > 0) {
                                    c = confirm('Atenção: ' + $('#ORIGEM_PROCESSO_CADASTRO option').length + ' Origem(s) localizada(s). Mas nao sao identicas a origem informada (' + $('#FILTRO_ORIGEM_PROCESSO_CADASTRO').val() + ') !\nDeseja adiciona-la na base de dados de origens de processos?');
                                } else {
                                    c = confirm('Origem nao encontrada!\nDeseja adiciona-la na base de dados de origens de processos?');
                                }

                                if (c) {
                                    $.post("modelos/processos/origem.php", {
                                        acao: 'adicionar',
                                        origem: $('#FILTRO_ORIGEM_PROCESSO_CADASTRO').val()
                                    },
                                    function(data) {
                                        try {
                                            if (data.success == 'true') {
                                                var options = $('#ORIGEM_PROCESSO_CADASTRO').attr('options');
                                                $('option', '#ORIGEM_PROCESSO_CADASTRO').remove();
                                                options[0] = new Option(data.origem, data.id);
                                            } else {
                                                alert(data.error);
                                            }
                                        } catch (e) {
                                            alert('Ocorreu um erro ao tentar cadastrar o processo!\n[' + e + ']');
                                        }
                                    }, "json");
                                }
                            }
                        }
                    }
                });

                /*Caixa de Dialogo*/
                $('#box-filtro-interessado-cadastro-processo').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    open: function(event, ui) {
                        /*Prevenir a ausencia de assunto na validacao*/
                        if (!$('#ASSUNTO_PROCESSO_CADASTRO').val()) {
                            $(this).dialog('close');
                            alert('Antes de continuar selecione um assunto!');
                            $('#box-filtro-assunto-cadastro-processo').dialog('open');
                            return false;
                        }
                        $('#box-filtro-interessado-cadastro-processo').parent('div').position({
                            of: $("#INTERESSADO_PROCESSO_CADASTRO"),
                            my: "left top",
                            at: "left bottom",
                            offset: 0
                        });
                        boxInteressadoOpen = true;
                    },
                    close: function() {
                        boxInteressadoOpen = false;
                    },
                    height: 180
                });

                $('#box-filtro-origem-cadastro-processo').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 120
                });

                $('#box-filtro-assunto-cadastro-processo').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 90
                });

                $('#div-form-cadastrar-processos').dialog({
                    title: 'Cadastro de Processo',
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    width: 650,
                    height: 310,
                    /*Bug Fix :: Evitar que a mensagem de inclusao de novo interessado apareca novamente*/
                    beforeClose: function() {
                        boxInteressadoOpen = false
                    },
                    close: function() {
                        $('#box-filtro-interessado-cadastro-processo').dialog('close');
                        $('#box-filtro-origem-cadastro-processo').dialog('close');
                        $('#box-filtro-assunto-cadastro-processo').dialog('close');
                        jquery_limpar_campos_processo_cadastro();
                    },
                    buttons: {
                        Salvar: function() {
                            jquery_validar_campos_processo_cadastro();
                        },
                        Cancelar: function() {
                            $(this).dialog('close');
                        }
                    }
                });

                /*Listeners Botoes*/
                $('#botao-filtro-interessado-cadastro-processo').click(function() {
                    $('#box-filtro-interessado-cadastro-processo').dialog('open');
                });

                $('#botao-filtro-origem-cadastro-processo').click(function() {
                    $('#box-filtro-origem-cadastro-processo').dialog('open');
                });

                $('#botao-filtro-assunto-cadastro-processo').click(function() {
                    $('#box-filtro-assunto-cadastro-processo').dialog('open');
                });

                $('#botao-processo-novo').click(function() {
                    $('#div-form-cadastrar-processos').dialog('open');
                });

                /*Calendarios*/
                $('#DATA_PRAZO_PROCESSO_CADASTRO').datepicker({
                    changeMonth: true,
                    changeYear: true
                });
                $('#DATA_AUTUACAO_PROCESSO_CADASTRO').datepicker({
                    changeMonth: true,
                    changeYear: true
                });

                /*Funcoes*/
                /*Verificar se o numero do processo ja foi cadastrado*/
                function jquery_verificar_processo(numero_processo) {
                    var r = $.ajax({
                        type: 'POST',
                        url: 'modelos/processos/processos.php',
                        data: 'acao=verificar-processo&numero_processo=' + numero_processo,
                        async: false,
                        success: function(msg) {
                        },
                        failure: function(error) {
                        }
                    }).responseText;

                    r = eval('(' + r + ')');

                    if (r.success == 'true') {
                        return r.existe;
                    } else {
                        throw 'Ocorreu um erro ao tentar verificar se o numero do processo ja existe!\n[' + r.error + ']';
                        return false;
                    }
                }

                /*Carregar o cpf/cnpj do interessado selecionado*/
                function jquery_carregar_cpfcnpj_interessado_processo_cadastro() {
                    $.post("modelos/processos/interessado.php", {
                        acao: 'get',
                        campo: 'cnpj_cpf',
                        valor: $('#INTERESSADO_PROCESSO_CADASTRO').val()
                    },
                    function(data) {
                        try {

                            switch (data.length) {
                                case 14:
                                    $('#combo_cpf_interessado_processo_cadastro').val('cpf');
                                    $('#FILTRO_CPF_INTERESSADO_PROCESSO_CADASTRO').val(data);
                                    $('#FILTRO_CNPJ_INTERESSADO_PROCESSO_CADASTRO').val('');
                                    $("#FILTRO_CPF_INTERESSADO_PROCESSO_CADASTRO").show();
                                    $("#FILTRO_CNPJ_INTERESSADO_PROCESSO_CADASTRO").hide();
                                    break;
                                case 18:
                                    $('#combo_cpf_interessado_processo_cadastro').val('cnpj');
                                    $('#FILTRO_CNPJ_INTERESSADO_PROCESSO_CADASTRO').val(data);
                                    $('#FILTRO_CPF_INTERESSADO_PROCESSO_CADASTRO').val('');
                                    $("#FILTRO_CPF_INTERESSADO_PROCESSO_CADASTRO").hide();
                                    $("#FILTRO_CNPJ_INTERESSADO_PROCESSO_CADASTRO").show();
                                    break;
                                default:
                                    $("#FILTRO_CPF_INTERESSADO_PROCESSO_CADASTRO").val('');
                                    $("#FILTRO_CNPJ_INTERESSADO_PROCESSO_CADASTRO").val('');
                                    break;
                            }

                        } catch (e) {
                            alert('Ocorreu um erro ao tentar recuperar o cpf/cnpj do interessado!\n[' + e + ']');
                        }
                    }, "json");
                }

                /*Limpar os campos do formulario*/
                function jquery_limpar_campos_processo_cadastro() {
                    $('#NUMERO_PROCESSO_CADASTRO').val('');
                    $('#ASSUNTO_PROCESSO_CADASTRO').empty();
                    $('#ASSUNTO_COMPLEMENTAR_PROCESSO_CADASTRO').val('');
                    $('#INTERESSADO_PROCESSO_CADASTRO').empty();
                    $('#ORIGEM_PROCESSO_CADASTRO').empty();
                    $('#DATA_AUTUACAO_PROCESSO_CADASTRO').val('');
                    $('#DATA_PRAZO_PROCESSO_CADASTRO').val('');
                    $('#FILTRO_ASSUNTO_PROCESSO_CADASTRO').val('');
                    $('#FILTRO_ORIGEM_PROCESSO_CADASTRO').val('');
                    $('#FILTRO_INTERESSADO_PROCESSO_CADASTRO').val('');
                }


                /*Validar todo o formulario antes de salvar os dados*/
                function jquery_validar_campos_processo_cadastro() {
                    /*Numero processo*/
                    if (!$('#NUMERO_PROCESSO_CADASTRO').val()) {
                        alert('O campo Numero do Processo e obrigatorio!');
                        $('#NUMERO_PROCESSO_CADASTRO').focus();
                        return false;
                    }

                    /*Numero Correto*/
                    if (!jquery_validar_numero_processo('#NUMERO_PROCESSO_CADASTRO')) {
                        return false;
                    }

                    /*Assunto*/
                    if (!$('#ASSUNTO_PROCESSO_CADASTRO').val()) {
                        alert('O campo Assunto e obrigatório!');
                        $('#ASSUNTO_PROCESSO_CADASTRO').focus();
                        return false;
                    }

                    /*Interessado*/
                    if (!$('#INTERESSADO_PROCESSO_CADASTRO').val()) {
                        alert('O campo Interessdo e obrigatorio!');
                        $('#INTERESSADO_PROCESSO_CADASTRO').focus();
                        return false;
                    }

                    /*Origem*/
                    if (!$('#ORIGEM_PROCESSO_CADASTRO').val()) {
                        alert('O campo Origem e obrigatorio!');
                        $('#ORIGEM_PROCESSO_CADASTRO').focus();
                        return false;
                    }

                    /*Data Autuacao*/
                    if (!$('#DATA_AUTUACAO_PROCESSO_CADASTRO').val()) {
                        alert('O campo Data da Autuacao e obrigatorio!');
                        $('#DATA_AUTUACAO_PROCESSO_CADASTRO').focus();
                        return false;
                    }


                    try {
                        if (jquery_validar_assunto_interessado_obrigatorio($('#ASSUNTO_PROCESSO_CADASTRO').val(), $('#INTERESSADO_PROCESSO_CADASTRO').val())) {
                            jquery_cadastrar_processo();
                        } else {
                            alert('O assunto escolhido necessita que o interessado possua um cnpj/cpf valido!');
                        }
                    } catch (e) {
                        alert(e);
                    }
                    return false;
                }

                /*Salvar os dados do processo*/
                function jquery_cadastrar_processo() {

                    var c = confirm('Você tem certeza que deseja cadastrar este processo?');

                    if (c) {
                        /*Ativar Preloader*/
                        $('#progressbar').show();

                        $.post("modelos/processos/processos.php", {
                            acao: 'cadastrar',
                            numero_processo: $('#NUMERO_PROCESSO_CADASTRO').val(),
                            interessado: $('#INTERESSADO_PROCESSO_CADASTRO').val(),
                            assunto: $('#ASSUNTO_PROCESSO_CADASTRO').val(),
                            assunto_complementar: $('#ASSUNTO_COMPLEMENTAR_PROCESSO_CADASTRO').val(),
                            tipo_origem: $('#TIPO_ORIGEM_PROCESSO_CADASTRO').val() == 'IN' ? '1' : '0', //previne o valor de 0 inteiro | INTERNO = 1 , EXTERNO = 0
                            origem: $('#ORIGEM_PROCESSO_CADASTRO').val(),
                            data_autuacao: $('#DATA_AUTUACAO_PROCESSO_CADASTRO').val(),
                            data_prazo: $('#DATA_PRAZO_PROCESSO_CADASTRO').val()
                        },
                        function(data) {
                            try {
                                if (data.success == 'true') {
                                    alert(data.mensagem);
                                    $('#div-form-cadastrar-processos').dialog("close");
                                    oTableProcessos.fnDraw();
                                    /*Desativar Preloader*/
                                    $('#progressbar').hide();
                                } else {
                                    alert(data.error);
                                    /*Desativar Preloader*/
                                    $('#progressbar').hide();
                                }
                            } catch (e) {
                                alert('Ocorreu um erro ao tentar cadastrar o processo!\n[' + e + ']');
                                /*Desativar Preloader*/
                                $('#progressbar').hide();
                            }
                        }, "json");
                    }
                }

            });



        </script>

    </head>
    <body>
        <!--Formulario-->
        <div class="div-form-dialog" id="div-form-cadastrar-processos">

            <div class="row">
                <label class="label">*NUMERO PROCESSO:</label>
                <span class="conteudo">
                    <input type="text" id="NUMERO_PROCESSO_CADASTRO" maxlength="20">
                </span>
            </div>

            <div class="row">
                <label class="label">*ASSUNTO:</label>
                <span class="conteudo">
                    <select class='FUNDOCAIXA1' id='ASSUNTO_PROCESSO_CADASTRO'></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-assunto-cadastro-processo" src="imagens/fam/application_edit.png">
            </div>

            <div class="row">
                <label class="label">ASSUNTO COMPLEMENTAR:</label>
                <span class="conteudo">
                    <input type="text" id="ASSUNTO_COMPLEMENTAR_PROCESSO_CADASTRO" maxlength="250" onKeyUp="DigitaLetraSeguro(this)">
                </span>
            </div>

            <div>
                <label>*INTERESSADO:</label>
                <span class="conteudo">
                    <select id="INTERESSADO_PROCESSO_CADASTRO"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-interessado-cadastro-processo" src="imagens/fam/application_edit.png">
            </div>

            <div>
                <label>*ORIGEM:</label>
                <span class="conteudo">
                    <select id="ORIGEM_PROCESSO_CADASTRO"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-origem-cadastro-processo" src="imagens/fam/application_edit.png">
            </div>

            <div>
                <label>*DATA DA AUTUACAO:</label>
                <input type="text" id="DATA_AUTUACAO_PROCESSO_CADASTRO" maxlength="10" readonly="true">
            </div>

            <div>
                <label>DATA DO PRAZO:</label>
                <input type="text" id="DATA_PRAZO_PROCESSO_CADASTRO" maxlength="10" readonly="true">
                <img title="Limpar" class="botao-auxiliar" src="imagens/fam/delete.png" onClick="limparCampoData('DATA_PRAZO_PROCESSO_CADASTRO');">
            </div>

        </div>

        <!-- Filtros -->
        <div id="box-filtro-origem-cadastro-processo" class="box-filtro">
            <div class="row">
                <label>*Tipo de Origem:</label>
                <div class="conteudo">
                    <select id="TIPO_ORIGEM_PROCESSO_CADASTRO" class="FUNDOCAIXA1">
                        <option value="IN">Processo Interno</option>
                        <option value="EX">Processo Externo</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <label>*Origem:</label>
                <div class="conteudo">
                    <input type="text" id="FILTRO_ORIGEM_PROCESSO_CADASTRO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>

        <div id="box-filtro-assunto-cadastro-processo" class="box-filtro">
            <div class="row">
                <label>*Assunto:</label>
                <div class="conteudo">
                    <input type="text" id="FILTRO_ASSUNTO_PROCESSO_CADASTRO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>

        <div id="box-filtro-interessado-cadastro-processo" class="box-filtro">
            <div class="row">
                <label>*Interessado:</label>
                <div class="conteudo">
                    <input type="text" id="FILTRO_INTERESSADO_PROCESSO_CADASTRO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1" maxlength="100" />
                </div>
            </div>
            <div class="row">
                <label>*CPF ou CNPJ:</label>
                <div class="conteudo">
                    <select class="FUNDOCAIXA1" id="combo_cpf_interessado_processo_cadastro">
                        <option selected value="cpf">CPF</option>
                        <option value="cnpj">CNPJ</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <label id="cpf_ou_cnpj_label_cadastro">CPF:</label>
                <div class="conteudo">
                    <input type="text" id="FILTRO_CNPJ_INTERESSADO_PROCESSO_CADASTRO" class="FUNDOCAIXA1" maxlength="18">
                    <input type="text" id="FILTRO_CPF_INTERESSADO_PROCESSO_CADASTRO" class="FUNDOCAIXA1" maxlength="14">
                </div>
            </div>
        </div>
    </body>
</html>