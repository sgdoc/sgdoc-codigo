;/*
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
var boxInteressadoOpen = false;

/*Inicio JQuery*/
$(document).ready(function() {

    /*Salvar Alteracoes processo*/
    $('#botao-salvar-alteracoes-processo').click(function() {
        jquery_validar_campos_detalhar_processo();
    });

    /*Listeners Campos*/
    $('#NUMERO_DETALHAR_PROCESSO').keyup(function() {
        formatar_numero_processo(document.getElementById('NUMERO_DETALHAR_PROCESSO'));
    });

    /*Botao historico tramite*/
    $('#botao-historico-tramite-processo').click(function() {
        popup('historico_tramite_processos.php?numero_processo=' + $('#NUMERO_DETALHAR_PROCESSO').val(), function() {
            oTableProcessos.fnDraw(false);
        });
    });

    /*Botao folha de despachos*/
    $('#botao-folha-despacho-processo').click(function() {
        popup('folha_despacho_processo.php?numero_processo=' + $('#NUMERO_DETALHAR_PROCESSO').val(), function() {
            oTableProcessos.fnDraw(false);
        });
    });

    /*Botao historico comentarios*/
    $('#botao-historico-comentarios-processo').click(function() {
        popup('historico_comentarios_processo.php?numero_processo=' + $('#NUMERO_DETALHAR_PROCESSO').val(), function() {
            oTableProcessos.fnDraw(false);
        });
    });

    /*Botao historico despachos*/
    $('#botao-historico-despachos-processo').click(function() {
        popup('historico_despachos_processo.php?numero_processo=' + $('#NUMERO_DETALHAR_PROCESSO').val(), function() {
            oTableProcessos.fnDraw(false);
        });
    });

    /*Botao historico despachos*/
    $('#botao-lista-modelo-termos').click(function() {
        popup('detalhar_vinculacoes_processos.php?numero_processo=' + $('#NUMERO_DETALHAR_PROCESSO').val(), function() {
            oTableProcessos.fnDraw(false);
        });
    });

    /*Botao vinculacao processos*/
    $('#botao-vinculacao-processo').click(function() {
        jquery_listar_vinculacao_processo($('#NUMERO_DETALHAR_PROCESSO').val(), true);
    });

    /*Listeners carregar cpf/cnpj interessado*/
    $('#INTERESSADO_DETALHAR_PROCESSO').click(function() {
        if ($(this).val()) {
            jquery_carregar_cpfcnpj_interessado_detalhar_processo();
        }
    });

    $('#INTERESSADO_DETALHAR_PROCESSO').change(function() {
        if ($(this).val()) {
            jquery_carregar_cpfcnpj_interessado_detalhar_processo();
        }
    });

    /*Formatar campo cpf/cnpj interessado*/
    $("#FILTRO_CNPJ_INTERESSADO_DETALHAR_PROCESSO").mask('99.999.999/9999-99').hide();
    $("#FILTRO_CPF_INTERESSADO_DETALHAR_PROCESSO").mask('999.999.999-99').show();

    $('#combo_cpf_interessado_detalhar_processo').change(function() {
        switch ($(this).val()) {
            case 'cnpj':
                $("#FILTRO_CPF_INTERESSADO_DETALHAR_PROCESSO").hide();
                $("#FILTRO_CNPJ_INTERESSADO_DETALHAR_PROCESSO").show();
                $("#cpf_ou_cnpj_label_detalhar").text('CNPJ');
                break;

            case 'cpf':
                $("#FILTRO_CNPJ_INTERESSADO_DETALHAR_PROCESSO").hide();
                $("#FILTRO_CPF_INTERESSADO_DETALHAR_PROCESSO").show();
                $("#cpf_ou_cnpj_label_detalhar").text('CPF');
                break;

            default:
                break;
        }
    });

    /*AutoComplete*/
    /*Origem*/
    $("#FILTRO_ORIGEM_DETALHAR_PROCESSO").autocompleteonline({
        url: 'modelos/combos/autocomplete.php',
        idTypeField: 'TIPO_ORIGEM_DETALHAR_PROCESSO',
        idComboBox: 'ORIGEM_DETALHAR_PROCESSO',
        paramTypeName: 'type',
        extraParams: {
            action: 'processos-origens'
        }
    });
    /*Assunto*/
    $("#FILTRO_ASSUNTO_DETALHAR_PROCESSO").autocompleteonline({
        url: 'modelos/combos/autocomplete.php',
        idComboBox: 'ASSUNTO_DETALHAR_PROCESSO',
        extraParams: {
            action: 'processos-assuntos'
        }
    });
    /*Interessado*/
    $("#FILTRO_INTERESSADO_DETALHAR_PROCESSO").autocompleteonline({
        url: 'modelos/combos/autocomplete.php',
        idComboBox: 'INTERESSADO_DETALHAR_PROCESSO',
        extraParams: {
            action: 'processos-interessados'
        }
    });
    /*CPF*/
    $("#FILTRO_CPF_INTERESSADO_DETALHAR_PROCESSO").autocompleteonline({
        url: 'modelos/combos/autocomplete.php',
        idComboBox: 'INTERESSADO_DETALHAR_PROCESSO',
        setElemOnclick: false,
        extraParams: {
            action: 'processos-interessados'
        }
    });
    /*CNPJ*/
    $("#FILTRO_CNPJ_INTERESSADO_DETALHAR_PROCESSO").autocompleteonline({
        url: 'modelos/combos/autocomplete.php',
        idComboBox: 'INTERESSADO_DETALHAR_PROCESSO',
        setElemOnclick: false,
        extraParams: {
            action: 'processos-interessados'
        }
    });

    /*Listeners*/
    /*Bind Dialog Close*/
    /*Box Interessado*/
    $("#box-filtro-interessado-detalhar-processo").bind("dialogclose", function(event, ui) {

        /*Bug Fix :: Evitar que a mensagem de inclusao de novo interessado apareca novamente*/
        if (!boxInteressadoOpen) {
            return false;
        }

        var exists = false;
        var c = false;
        var cpf_cnpj;


        /*Verificar se o filtro possui valor*/
        if ($('#FILTRO_INTERESSADO_DETALHAR_PROCESSO').val()) {

            /*Prevenir a ausencia de assunto na validacao*/
            if (!$('#ASSUNTO_DETALHAR_PROCESSO').val()) {
                alert('Antes de continuar selecione um assunto!');
                $('#box-filtro-assunto-detalhar-processo').dialog('open');
                $(this).dialog('open');
                return false;
            }

            /*Validacao para o tipo de numero do novo interessado*/
            if ($('#combo_cpf_interessado_detalhar_processo').find(':selected').val() == 'cpf') {
                cpf_cnpj = $('#FILTRO_CPF_INTERESSADO_DETALHAR_PROCESSO').val();
            } else {
                cpf_cnpj = $('#FILTRO_CNPJ_INTERESSADO_DETALHAR_PROCESSO').val();
            }

            /*Validar o cpf antes de submeter*/
            if (cpf_cnpj.length > 0) {
                if (!jquery_validar_cpf_cnpj(cpf_cnpj)) {
                    alert('Cpf ou Cnpj invalido!');
                    $('#box-filtro-interessado-detalhar-processo').dialog('open');
                    return false;
                }
            }

            /*Core da Validacao !@#$@#$%* - Inicio*/
            $.post("modelos/processos/assunto.php", {
                acao: 'interessado-obrigatorio',
                assunto: $('#ASSUNTO_DETALHAR_PROCESSO').val(),
                interessado: $('#INTERESSADO_DETALHAR_PROCESSO').val()
            },
            function(data) {
                try {
                    if (data.success == 'true') {
                        if (data.obrigatorio == 'true') {
                            if (!jquery_validar_cpf_cnpj(cpf_cnpj)) {
                                alert('Ao escolher o assunto ' + $('#ASSUNTO_DETALHAR_PROCESSO option:selected').text() + ' o cnpj/cpf do interessado torna-se obrigatorio!');//para o assunto escolhido eh necessario informar o cnpj/cpf valido!
                                $('#box-filtro-interessado-detalhar-processo').dialog('open');
                                return false;
                            }
                        }

                        /*Verificar se o valor do filtro consta na relacao*/
                        $("#INTERESSADO_DETALHAR_PROCESSO option").each(function() {
                            if ($('#FILTRO_INTERESSADO_DETALHAR_PROCESSO').val() == $(this).text()) {
                                exists = true;
                            }
                        });

                        /*Core da Validacao !@#$@#$%* - Final*/

                        /*Se nao existir na relacao*/
                        if (!exists) {

                            /*Se existir itens semelhantes "Informa" senao "Solicita a insercao"*/
                            if ($('#INTERESSADO_DETALHAR_PROCESSO option').length > 0) {
                                c = confirm('Atenção: ' + $('#INTERESSADO_DETALHAR_PROCESSO option').length + ' Interessado(s) localizado(s). Mas não são idênticos ao interessado informado (' + $('#FILTRO_INTERESSADO_DETALHAR_PROCESSO').val() + ') !\nDeseja adicioná-lo na base de dados de interessados?');
                            } else {
                                c = confirm('Esse interessado nao foi encontrado!\nDeseja adiciona-lo na base de dados de interessados?');
                            }

                            /*Se confirmacao insercao do interessado positiva*/
                            if (c) {
                                $.post("modelos/processos/interessado.php", {
                                    acao: 'adicionar',
                                    interessado: $('#FILTRO_INTERESSADO_DETALHAR_PROCESSO').val(),
                                    cpf: cpf_cnpj
                                }, function(data) {
                                    try {
                                        if (data.success == 'true') {
                                            var options = $('#INTERESSADO_DETALHAR_PROCESSO').attr('options');
                                            $('option', '#INTERESSADO_DETALHAR_PROCESSO').remove();
                                            options[0] = new Option(data.interessado, data.id);
                                        } else {
                                            alert(data.error);
                                            $('#box-filtro-interessado-detalhar-processo').dialog('open');
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

    $("#box-filtro-origem-detalhar-processo").bind("dialogclose", function(event, ui) {
        if ($('#TIPO_ORIGEM_DETALHAR_PROCESSO').val() == 'EX') {
            var exists = false;
            var c = false;
            if ($('#FILTRO_ORIGEM_DETALHAR_PROCESSO').val()) {
                $("#ORIGEM_DETALHAR_PROCESSO option").each(function() {
                    if ($('#FILTRO_ORIGEM_DETALHAR_PROCESSO').val() == $(this).text()) {
                        exists = true;
                    }
                });
                if (!exists) {

                    if ($('#ORIGEM_DETALHAR_PROCESSO option').length > 0) {
                        c = confirm('Atenção: ' + $('#ORIGEM_DETALHAR_PROCESSO option').length + ' Origem(s) localizada(s). Mas nao sao identicas a origem informada (' + $('#FILTRO_ORIGEM_DETALHAR_PROCESSO').val() + ') !\nDeseja adiciona-la na base de dados de origens de processos?');
                    } else {
                        c = confirm('Origem nao encontrada!\nDeseja adiciona-la na base de dados de origens de processos?');
                    }

                    if (c) {
                        $.post("modelos/processos/origem.php", {
                            acao: 'adicionar',
                            origem: $('#FILTRO_ORIGEM_DETALHAR_PROCESSO').val()
                        },
                        function(data) {
                            try {
                                if (data.success == 'true') {
                                    var options = $('#ORIGEM_DETALHAR_PROCESSO').attr('options');
                                    $('option', '#ORIGEM_DETALHAR_PROCESSO').remove();
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
    $('#box-filtro-interessado-detalhar-processo').dialog({
        title: 'Filtro',
        autoOpen: false,
        resizable: false,
        modal: false,
        width: 380,
        open: function(event, ui) {
            /*Prevenir a ausencia de assunto na validacao*/
            if (!$('#ASSUNTO_DETALHAR_PROCESSO').val()) {
                $(this).dialog('close');
                alert('Antes de continuar selecione um assunto!');
                $('#box-filtro-assunto-detalhar-processo').dialog('open');
                return false;
            }
            boxInteressadoOpen = true;
        },
        close: function() {
            boxInteressadoOpen = false;
        },
        height: 180
    });

    $('#box-filtro-origem-detalhar-processo').dialog({
        title: 'Filtro',
        autoOpen: false,
        resizable: false,
        modal: false,
        width: 380,
        height: 120
    });

    $('#box-filtro-assunto-detalhar-processo').dialog({
        title: 'Filtro',
        autoOpen: false,
        resizable: false,
        modal: false,
        width: 380,
        height: 90
    });

    $('#div-form-detalhar-processos').dialog({
        title: 'Detalhes do Processo',
        autoOpen: false,
        resizable: false,
        modal: true,
        width: 600,
        height: 300,
        /*Bug Fix :: Evitar que a mensagem de inclusao de novo interessado apareca novamente*/
        beforeClose: function() {
            boxInteressadoOpen = false
        },
        close: function() {
            $('#box-filtro-interessado-detalhar-processo').dialog('close');
            $('#box-filtro-origem-detalhar-processo').dialog('close');
            $('#box-filtro-assunto-detalhar-processo').dialog('close');
            jquery_limpar_campos_detalhar_processo();
        },
        buttons: {}
    });

    /*Listeners Botoes*/
    /*Imprimir etiqueta do processo*/
    $('#botao-imprimir-etiqueta-processo').click(function() {
        jquery_etiqueta_processo($('#NUMERO_DETALHAR_PROCESSO').val());
    });

    $('#botao-filtro-interessado-detalhar-processo').click(function() {
        $('#box-filtro-interessado-detalhar-processo').dialog('open');
    });

    $('#botao-filtro-origem-detalhar-processo').click(function() {
        $('#box-filtro-origem-detalhar-processo').dialog('open');
    });

    $('#botao-filtro-assunto-detalhar-processo').click(function() {
        $('#box-filtro-assunto-detalhar-processo').dialog('open');
    });


    /*Calendarios*/
    $('#DATA_PRAZO_DETALHAR_PROCESSO').datepicker({
        changeMonth: true,
        changeYear: true
    });
    $('#DATA_AUTUACAO_DETALHAR_PROCESSO').datepicker({
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
    function jquery_carregar_cpfcnpj_interessado_detalhar_processo() {
        $.post("modelos/processos/interessado.php", {
            acao: 'get',
            campo: 'cnpj_cpf',
            valor: $('#INTERESSADO_DETALHAR_PROCESSO').val()
        },
        function(data) {
            try {

                switch (data.length) {
                    case 14:
                        $('#combo_cpf_interessado_detalhar_processo').val('cpf');
                        $('#FILTRO_CPF_INTERESSADO_DETALHAR_PROCESSO').val(data);
                        $('#FILTRO_CNPJ_INTERESSADO_DETALHAR_PROCESSO').val('');
                        $("#FILTRO_CPF_INTERESSADO_DETALHAR_PROCESSO").show();
                        $("#FILTRO_CNPJ_INTERESSADO_DETALHAR_PROCESSO").hide();
                        break;
                    case 18:
                        $('#combo_cpf_interessado_detalhar_processo').val('cnpj');
                        $('#FILTRO_CNPJ_INTERESSADO_DETALHAR_PROCESSO').val(data);
                        $('#FILTRO_CPF_INTERESSADO_DETALHAR_PROCESSO').val('');
                        $("#FILTRO_CPF_INTERESSADO_DETALHAR_PROCESSO").hide();
                        $("#FILTRO_CNPJ_INTERESSADO_DETALHAR_PROCESSO").show();
                        break;
                    default:
                        $("#FILTRO_CPF_INTERESSADO_DETALHAR_PROCESSO").val('');
                        $("#FILTRO_CNPJ_INTERESSADO_DETALHAR_PROCESSO").val('');
                        break;
                }

            } catch (e) {
                alert('Ocorreu um erro ao tentar recuperar o cpf/cnpj do interessado!\n[' + e + ']');
            }
        }, "json");
    }

    /*Limpar os campos do formulario*/
    function jquery_limpar_campos_detalhar_processo() {
        $('#NUMERO_DETALHAR_PROCESSO').val('');
        $('#ASSUNTO_DETALHAR_PROCESSO').empty();
        $('#ASSUNTO_COMPLEMENTAR_DETALHAR_PROCESSO').val('');
        $('#INTERESSADO_DETALHAR_PROCESSO').empty();
        $('#ORIGEM_DETALHAR_PROCESSO').empty();
        $('#DATA_AUTUACAO_DETALHAR_PROCESSO').val('');
        $('#DATA_PRAZO_DETALHAR_PROCESSO').val('');
        $('#FILTRO_ASSUNTO_DETALHAR_PROCESSO').val('');
        $('#FILTRO_ORIGEM_DETALHAR_PROCESSO').val('');
        $('#FILTRO_INTERESSADO_DETALHAR_PROCESSO').val('');
    }


    /*Validar todo o formulario antes de salvar os dados*/
    function jquery_validar_campos_detalhar_processo() {
        /*Numero processo*/
        if (!$('#NUMERO_DETALHAR_PROCESSO').val()) {
            alert('O campo Numero do Processo e obrigatorio!');
            $('#NUMERO_DETALHAR_PROCESSO').focus();
            return false;
        }

        /*Assunto*/
        if (!$('#ASSUNTO_DETALHAR_PROCESSO').val()) {
            alert('O campo Assunto e obrigatório!');
            $('#ASSUNTO_DETALHAR_PROCESSO').focus();
            return false;
        }

        /*Interessado*/
        if (!$('#INTERESSADO_DETALHAR_PROCESSO').val()) {
            alert('O campo Interessdo e obrigatório!');
            $('#INTERESSADO_DETALHAR_PROCESSO').focus();
            return false;
        }

        /*Origem*/
        if (!$('#ORIGEM_DETALHAR_PROCESSO').val()) {
            alert('O campo Origem e obrigatorio!');
            $('#ORIGEM_DETALHAR_PROCESSO').focus();
            return false;
        }

        /*Data Autuacao*/
        if (!$('#DATA_AUTUACAO_DETALHAR_PROCESSO').val()) {
            alert('O campo Data da Autuacao e obrigatorio!');
            $('#DATA_AUTUACAO_DETALHAR_PROCESSO').focus();
            return false;
        }


        try {
            if (jquery_validar_assunto_interessado_obrigatorio($('#ASSUNTO_DETALHAR_PROCESSO').val(), $('#INTERESSADO_DETALHAR_PROCESSO').val())) {
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

        var c = confirm('Você tem certeza que deseja salvar as alterações feitas neste processo?');

        if (c) {
            /*Ativar Preloader*/
            $('#progressbar').show();

            $.post("modelos/processos/processos.php", {
                acao: 'alterar',
                numero_processo: $('#NUMERO_DETALHAR_PROCESSO').val(),
                interessado: $('#INTERESSADO_DETALHAR_PROCESSO').val(),
                assunto: $('#ASSUNTO_DETALHAR_PROCESSO').val(),
                assunto_complementar: $('#ASSUNTO_COMPLEMENTAR_DETALHAR_PROCESSO').val(),
                procedencia: $('#TIPO_ORIGEM_DETALHAR_PROCESSO').val() == 'IN' ? '1' : '0', //previne o valor de 0 inteiro | INTERNO = 1 , EXTERNO = 0
                origem: $('#ORIGEM_DETALHAR_PROCESSO').val(),
                dt_autuacao: $('#DATA_AUTUACAO_DETALHAR_PROCESSO').val(),
                dt_prazo: $('#DATA_PRAZO_DETALHAR_PROCESSO').val(),
                fg_prazo: $("#STATUS_PRAZO_DETALHAR_PROCESSO").attr('checked')
            },
            function(data) {
                try {
                    if (data.success == 'true') {
                        alert('Processo alterado com sucesso!');
                        $('#div-form-detalhar-processos').dialog("close");
                        oTableProcessos.fnDraw();
                        /*Desativar Preloader*/
                        $('#progressbar').hide();
                    } else {
                        alert(data.error);
                        /*Desativar Preloader*/
                        $('#progressbar').hide();
                    }
                } catch (e) {
                    alert('Ocorreu um erro ao tentar alterars as informacoes do processo!\n[' + e + ']');
                    /*Desativar Preloader*/
                    $('#progressbar').hide();
                }
            }, "json");
        }
    }

});