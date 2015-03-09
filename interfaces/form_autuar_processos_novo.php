
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

            $(document).ready(function() {

                /*Listeners carregar cpf/cnpj interessado*/
                $('#INTERESSADO_PROCESSO_AUTUACAO').click(function() {
                    if ($(this).val()) {
                        jquery_carregar_cpfcnpj_interessado_processo_autuacao();
                    }
                });

                $('#INTERESSADO_PROCESSO_AUTUACAO').change(function() {
                    if ($(this).val()) {
                        jquery_carregar_cpfcnpj_interessado_processo_autuacao();
                    }
                });

                /*Formatar campo cpf/cnpj interessado*/
                $("#FILTRO_CNPJ_INTERESSADO_PROCESSO_AUTUACAO").mask('99.999.999/9999-99').hide();
                $("#FILTRO_CPF_INTERESSADO_PROCESSO_AUTUACAO").mask('999.999.999-99').show();

                $('#combo_cpf_interessado_processo_autuacao').change(function() {
                    switch ($(this).val()) {
                        case 'cnpj':
                            $("#FILTRO_CPF_INTERESSADO_PROCESSO_AUTUACAO").hide();
                            $("#FILTRO_CNPJ_INTERESSADO_PROCESSO_AUTUACAO").show();
                            $("#cpf_ou_cnpj_label_autuacao").text('CNPJ');
                            break;

                        case 'cpf':
                            $("#FILTRO_CNPJ_INTERESSADO_PROCESSO_AUTUACAO").hide();
                            $("#FILTRO_CPF_INTERESSADO_PROCESSO_AUTUACAO").show();
                            $("#cpf_ou_cnpj_label_autuacao").text('CPF');
                            break;

                        default:
                            break;
                    }
                });

                /*AutoComplete*/
                /*Origem*/
                $("#FILTRO_ORIGEM_PROCESSO_AUTUACAO").autocompleteonline({
                    idComboBox: 'ORIGEM_PROCESSO_AUTUACAO',
                    url: 'modelos/combos/autocomplete.php',
                    extraParams: {
                        action: 'processos-origens',
                        type: 'IN'
                    }

                });
                /*Assunto*/
                $("#FILTRO_ASSUNTO_PROCESSO_AUTUACAO").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox: 'ASSUNTO_PROCESSO_AUTUACAO',
                    extraParams: {
                        action: 'processos-assuntos'
                    }
                });
                /*Interessado*/
                $("#FILTRO_INTERESSADO_PROCESSO_AUTUACAO").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox: 'INTERESSADO_PROCESSO_AUTUACAO',
                    extraParams: {
                        action: 'processos-interessados'
                    }
                });
                /*CPF*/
                $("#FILTRO_CPF_INTERESSADO_PROCESSO_AUTUACAO").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox: 'INTERESSADO_PROCESSO_AUTUACAO',
                    setElemOnclick: false,
                    extraParams: {
                        action: 'processos-interessados'
                    }
                });
                /*CNPJ*/
                $("#FILTRO_CNPJ_INTERESSADO_PROCESSO_AUTUACAO").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox: 'INTERESSADO_PROCESSO_AUTUACAO',
                    setElemOnclick: false,
                    extraParams: {
                        action: 'processos-interessados'
                    }
                });
                /*Calendario*/
                $('#DATA_PRAZO_PROCESSO_AUTUACAO').datepicker();
                /*Filtros*/
                $('#box-filtro-interessado-autuacao-processo').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    open: function(event, ui) {
                        /*Prevenir a ausencia de assunto na validacao*/
                        if (!$('#ASSUNTO_PROCESSO_AUTUACAO').val()) {
                            $(this).dialog('close');
                            alert('Antes de continuar selecione um assunto!');
                            $('#box-filtro-assunto-autuacao-processo').dialog('open');
                            return false;
                        }
                        $('#box-filtro-interessado-autuacao-processo').parent('div').position({
                            of: $("#INTERESSADO_PROCESSO_AUTUACAO"),
                            my: "left top",
                            at: "left bottom",
                            offset: 0
                        });

                        boxInteressadoOpen = true;
                    },
                    close: function() {
                        boxInteressadoOpen = false;
                    },
                    width: 380,
                    height: 180
                });

                /*Listeners*/
                /*Bind Dialog Close*/
                /*Box Interessado*/
                $("#box-filtro-interessado-autuacao-processo").bind("dialogclose", function(event, ui) {

                    /*Bug Fix :: Evitar que a mensagem de inclusao de novo interessado apareca novamente*/
                    if (!boxInteressadoOpen) {
                        return false;
                    }

                    var exists = false;
                    var c = false;
                    var cpf_cnpj;

                    /*Verificar se o filtro possui valor*/
                    if ($('#FILTRO_INTERESSADO_PROCESSO_AUTUACAO').val()) {

                        /*Prevenir a ausencia de assunto na validacao*/
                        if (!$('#ASSUNTO_PROCESSO_AUTUACAO').val()) {
                            alert('Antes de continuar selecione um assunto!');
                            $('#box-filtro-assunto-autuacao-processo').dialog('open');
                            $(this).dialog('open');
                            return false;
                        }

                        /*Validacao para o tipo de numero do novo interessado*/
                        if ($('#combo_cpf_interessado_processo_autuacao').find(':selected').val() == 'cpf') {
                            cpf_cnpj = $('#FILTRO_CPF_INTERESSADO_PROCESSO_AUTUACAO').val();
                        } else {
                            cpf_cnpj = $('#FILTRO_CNPJ_INTERESSADO_PROCESSO_AUTUACAO').val();
                        }

                        /*Validar o cpf antes de submeter*/
                        if (cpf_cnpj.length > 0) {
                            if (!jquery_validar_cpf_cnpj(cpf_cnpj)) {
                                alert('Cpf ou Cnpj invalido!');
                                $(this).dialog('open');
                                return false;
                            }
                        }

                        /*Core da Validacao !@#$@#$%* - Inicio*/
                        $.post("modelos/processos/assunto.php", {
                            acao: 'interessado-obrigatorio',
                            assunto: $('#ASSUNTO_PROCESSO_AUTUACAO').val(),
                            interessado: $('#INTERESSADO_PROCESSO_AUTUACAO').val()
                        },
                        function(data) {
                            try {
                                if (data.success == 'true') {
                                    if (data.obrigatorio == 'true') {
                                        if (!jquery_validar_cpf_cnpj(cpf_cnpj)) {
                                            //para o assunto escolhido eh necessario informar o cnpj/cpf valido!
                                            alert('Ao escolher o assunto ' + $('#ASSUNTO_PROCESSO_AUTUACAO option:selected').text() + ' o cnpj/cpf do interessado torna-se obrigatório!');
                                            $('#box-filtro-interessado-autuacao-processo').dialog('open');
                                            return false;
                                        }
                                    }

                                    /*Verificar se o valor do filtro consta na relacao*/
                                    $("#INTERESSADO_PROCESSO_AUTUACAO option").each(function() {
                                        if ($('#FILTRO_INTERESSADO_PROCESSO_AUTUACAO').val() == $(this).text()) {
                                            exists = true;
                                        }
                                    });

                                    /*Core da Validacao !@#$@#$%* - Final*/

                                    /*Se nao existir na relacao*/
                                    if (!exists) {

                                        /*Se existir itens semelhantes "Informa" senao "Solicita a insercao"*/
                                        if ($('#INTERESSADO_PROCESSO_AUTUACAO option').length > 0) {
                                            c = confirm('Atenção: ' + $('#INTERESSADO_PROCESSO_AUTUACAO option').length + ' Interessado(s) localizado(s). Mas não são idênticos ao interessado informado (' + $('#FILTRO_INTERESSADO_PROCESSO_AUTUACAO').val() + ') !\nDeseja adicioná-lo na base de dados de interessados?');
                                        } else {
                                            c = confirm('Esse interessado não foi encontrado!\nDeseja adicioná-lo na base de dados de interessados?');
                                        }

                                        /*Se confirmacao insercao do interessado positiva*/
                                        if (c) {
                                            $.post("modelos/processos/interessado.php", {
                                                acao: 'adicionar',
                                                interessado: $('#FILTRO_INTERESSADO_PROCESSO_AUTUACAO').val(),
                                                cpf: cpf_cnpj
                                            }, function(data) {
                                                try {
                                                    if (data.success == 'true') {
                                                        var options = $('#INTERESSADO_PROCESSO_AUTUACAO').attr('options');
                                                        $('option', '#INTERESSADO_PROCESSO_AUTUACAO').remove();
                                                        options[0] = new Option(data.interessado, data.id);
                                                    } else {
                                                        alert(data.error);
                                                        $('#box-filtro-interessado-autuacao-processo').dialog('open');

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
                                return false;
                            }
                        }, "json");
                    }

                });

                $('#box-filtro-assunto-autuacao-processo').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 90,
                    open: function() {
                        $('#box-filtro-assunto-autuacao-processo').parent('div').position({
                            of: $("#ASSUNTO_PROCESSO_AUTUACAO"),
                            my: "left top",
                            at: "left bottom",
                            offset: 0
                        });
                    }
                });

                $('#box-filtro-origem-autuacao-processo').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 90,
                    open: function() {
                        $('#box-filtro-origem-autuacao-processo').parent('div').position({
                            of: $("#ORIGEM_PROCESSO_AUTUACAO"),
                            my: "left top",
                            at: "left bottom",
                            offset: 0
                        });
                    }
                });
                /*Formulario*/
                $('#div-form-autuar-processos').dialog({
                    title: 'Autuação de Processo',
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    width: 660,
                    /*Bug Fix :: Evitar que a mensagem de inclusao de novo interessado apareca novamente*/
                    beforeClose: function() {
                        boxInteressadoOpen = false
                    },
                    close: function() {
                        $('#box-filtro-origem-autuacao-processo').dialog('close');
                        $('#box-filtro-assunto-autuacao-processo').dialog('close');
                        $('#box-filtro-interessado-autuacao-processo').dialog('close');
                        jquery_limpar_campos_processo_autuacao();
                    },
                    height: 280,
                    buttons: {
                        Salvar: function() {
                            jquery_validar_campos_processo_autuacao();
                        },
                        Cancelar: function() {
                            $(this).dialog('close');
                        }
                    }
                });
                /*Botoes Auxiliar*/
                $('#botao-filtro-assunto-autuacao-processo').click(function() {
                    $('#box-filtro-assunto-autuacao-processo').dialog('open');
                });

                $('#botao-filtro-interessado-autuacao-processo').click(function() {
                    $('#box-filtro-interessado-autuacao-processo').dialog('open');
                });

                $('#botao-filtro-origem-autuacao-processo').click(function() {
                    $('#box-filtro-origem-autuacao-processo').dialog('open');
                });



            });
            /*Funcoes*/

            /*Limpar os campos do formulario*/
            function jquery_limpar_campos_processo_autuacao() {
                $('#DIGITAL_PROCESSO_AUTUACAO').val('');
                $('#ASSUNTO_PROCESSO_AUTUACAO').empty();
                $('#ASSUNTO_COMPLEMENTAR_PROCESSO_AUTUACAO').val('');
                $('#INTERESSADO_PROCESSO_AUTUACAO').empty();
                $('#ORIGEM_PROCESSO_AUTUACAO').empty();
                $('#DATA_AUTUACAO_PROCESSO_AUTUACAO').val('');
                $('#DATA_PRAZO_PROCESSO_AUTUACAO').val('');
                $('#FILTRO_ASSUNTO_PROCESSO_AUTUACAO').val('');
                $('#FILTRO_ORIGEM_PROCESSO_AUTUACAO').val('');
                $('#FILTRO_INTERESSADO_PROCESSO_AUTUACAO').val('');
            }

            /*Validar todo o formulario antes de salvar os dados*/
            function jquery_validar_campos_processo_autuacao() {
                /*Digital*/
                if ($('#DIGITAL_PROCESSO_AUTUACAO').val().length < 7) {
                    alert('O Digital esta invalido feche o sistema e tente novamente!');
                    return false;
                }

                /*Assunto*/
                if (!$('#ASSUNTO_PROCESSO_AUTUACAO').val()) {
                    alert('O campo Assunto e obrigatório!');
                    $('#ASSUNTO_PROCESSO_AUTUACAO').focus();
                    return false;
                }

                /*Interessado*/
                if (!$('#INTERESSADO_PROCESSO_AUTUACAO').val()) {
                    alert('O campo Interessdo e obrigatório!');
                    $('#INTERESSADO_PROCESSO_AUTUACAO').focus();
                    return false;
                }

                /*Origem*/
                if (!$('#ORIGEM_PROCESSO_AUTUACAO').val()) {
                    alert('O campo Origem e obrigatório!');
                    $('#ORIGEM_PROCESSO_AUTUACAO').focus();
                    return false;
                }

                try {
                    if (jquery_validar_assunto_interessado_obrigatorio($('#ASSUNTO_PROCESSO_AUTUACAO').val(), $('#INTERESSADO_PROCESSO_AUTUACAO').val())) {
                        jquery_salvar_autuacao();
                    } else {
                        alert('O assunto escolhido necessita que o interessado possua um cnpj/cpf valido!');
                    }
                } catch (e) {
                    alert(e);
                }
                return false;
            }

            /*Carregar o cpf/cnpj do interessado selecionado*/
            function jquery_carregar_cpfcnpj_interessado_processo_autuacao() {
                $.post("modelos/processos/interessado.php", {
                    acao: 'recuperar-cpfcnpj',
                    id: $('#INTERESSADO_PROCESSO_AUTUACAO').val()
                },
                function(data) {
                    try {
                        if (data.success == 'true') {
                            switch (data.cnpj_cpf.length) {
                                case 14:
                                    $('#combo_cpf_interessado_processo_autuacao').val('cpf');
                                    $('#FILTRO_CPF_INTERESSADO_PROCESSO_AUTUACAO').val(data.cnpj_cpf);
                                    $('#FILTRO_CNPJ_INTERESSADO_PROCESSO_AUTUACAO').val('');
                                    $("#FILTRO_CPF_INTERESSADO_PROCESSO_AUTUACAO").show();
                                    $("#FILTRO_CNPJ_INTERESSADO_PROCESSO_AUTUACAO").hide();
                                    break;
                                case 18:
                                    $('#combo_cpf_interessado_processo_autuacao').val('cnpj');
                                    $('#FILTRO_CNPJ_INTERESSADO_PROCESSO_AUTUACAO').val(data.cnpj_cpf);
                                    $('#FILTRO_CPF_INTERESSADO_PROCESSO_AUTUACAO').val('');
                                    $("#FILTRO_CPF_INTERESSADO_PROCESSO_AUTUACAO").hide();
                                    $("#FILTRO_CNPJ_INTERESSADO_PROCESSO_AUTUACAO").show();
                                    break;
                                default:
                                    $("#FILTRO_CPF_INTERESSADO_PROCESSO_AUTUACAO").val('');
                                    $("#FILTRO_CNPJ_INTERESSADO_PROCESSO_AUTUACAO").val('');
                                    break;
                            }
                        }
                    } catch (e) {
                        alert('Ocorreu um erro ao tentar recuperar o cpf/cnpj do interessado!\n[' + e + ']');
                    }
                }, "json");
            }

            function jquery_autuar(digital) {
                if (jquery_quantidade_imagens_documento(digital) < 1) {
                    alert('Este documento não possui imagens!\nObs: Antes de prosseguir com a autuação efetue o upload das imagens!');
                    return false;
                }

                $('#div-form-autuar-processos').dialog('open');
                $('#DIGITAL_PROCESSO_AUTUACAO').val(digital);
            }

            function jquery_salvar_autuacao() {
                var c = confirm('Você tem certeza que deseja autuar este processo?');

                if (c) {
                    /*Ativar Preloader*/
                    $('#progressbar').show();
                    $.post("modelos/processos/processos.php", {
                        acao: 'autuar',
                        digital: $('#DIGITAL_PROCESSO_AUTUACAO').val(),
                        interessado: $('#INTERESSADO_PROCESSO_AUTUACAO').val(),
                        assunto: $('#ASSUNTO_PROCESSO_AUTUACAO').val(),
                        assunto_complementar: $('#ASSUNTO_COMPLEMENTAR_PROCESSO_AUTUACAO').val(),
                        origem: $('#ORIGEM_PROCESSO_AUTUACAO').val(),
                        dt_prazo: $('#DATA_PRAZO_PROCESSO_AUTUACAO').val()
                    },
                    function(data) {
                        try {
                            if (data.success == 'true') {
                                $('#div-form-autuar-processos').dialog("close");
                                oTableDocumentos.fnDraw();
                                oTableProcessos.fnDraw();
                                $('#progressbar').hide();
                                if (confirm('Processo ' + data.numero_processo + ' autuado com sucesso!\nVocê deseja imprimir a etiqueta do processo agora?')) {
                                    jquery_etiqueta_processo(data.numero_processo);
                                }
                            } else {
                                alert(data.error);
                                $('#progressbar').hide();
                            }
                        } catch (e) {
                            alert('Ocorreu um erro ao tentar autuar o processo!\n[' + e + ']');
                            $('#progressbar').hide();
                        }
                    }, "json");
                }

            }

        </script>

    </head>
    <body>
        <!--Formulario-->
        <!--
        *Digital:
        *Interessado:
       	*Assunto:
        *Assunto Complementar:
        *Origem do Pedido de Autuação:
        *Data do Prazo
        -->
        <div class="div-form-dialog" id="div-form-autuar-processos">

            <div class="row">
                <label class="label">*DIGITAL:</label>
                <span class="conteudo">
                    <input type="text" disabled id="DIGITAL_PROCESSO_AUTUACAO" class="FUNDOCAIXA1">
                </span>
            </div>

            <div class="row">
                <label class="label">*ASSUNTO:</label>
                <span class="conteudo">
                    <select id="ASSUNTO_PROCESSO_AUTUACAO" class="FUNDOCAIXA1"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-assunto-autuacao-processo" src="imagens/fam/application_edit.png">
            </div>

            <div class="row">
                <label class="label">ASSUNTO COMPLEMENTAR:</label>
                <span class="conteudo">
                    <input maxlength="250" type="text" id="ASSUNTO_COMPLEMENTAR_PROCESSO_AUTUACAO" class="FUNDOCAIXA1" onKeyUp="DigitaLetraSeguro(this)" >
                </span>
            </div>

            <div class="row">
                <label class="label">*INTERESSADO:</label>
                <span class="conteudo">
                    <select id="INTERESSADO_PROCESSO_AUTUACAO" class="FUNDOCAIXA1"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-interessado-autuacao-processo" src="imagens/fam/application_edit.png">
            </div>

            <div class="row">
                <label class="label">*ORIGEM:</label>
                <span class="conteudo">
                    <select id="ORIGEM_PROCESSO_AUTUACAO" class="FUNDOCAIXA1"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-origem-autuacao-processo" src="imagens/fam/application_edit.png">
            </div>

            <div class="row">
                <label class="label">PRAZO:</label>
                <span class="conteudo">
                    <input type="text" readonly id="DATA_PRAZO_PROCESSO_AUTUACAO" class="FUNDOCAIXA1">
                </span>
                <img title="Limpar" class="botao-auxiliar" src="imagens/fam/delete.png" onClick="limparCampoData('DATA_PRAZO_PROCESSO_AUTUACAO')">
            </div>

        </div>
        <!--Filtros-->
        <div id="box-filtro-assunto-autuacao-processo" class="box-filtro">
            <div class="row">
                <label>Assunto:</label>
                <div class="conteudo">
                    <input type="text" id="FILTRO_ASSUNTO_PROCESSO_AUTUACAO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>

        <div id="box-filtro-interessado-autuacao-processo" class="box-filtro">
            <div class="row">
                <label>Interessado:</label>
                <div class="conteudo">
                    <input type="text" id="FILTRO_INTERESSADO_PROCESSO_AUTUACAO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
            <div class="row">
                <label>*CPF ou CNPJ:</label>
                <div class="conteudo">
                    <select class="FUNDOCAIXA1" id="combo_cpf_interessado_processo_autuacao">
                        <option selected value="cpf">CPF</option>
                        <option value="cnpj">CNPJ</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <label id="cpf_ou_cnpj_label_autuacao">CPF:</label>
                <div class="conteudo">
                    <input type="text" id="FILTRO_CNPJ_INTERESSADO_PROCESSO_AUTUACAO" class="FUNDOCAIXA1" maxlength="18">
                    <input type="text" id="FILTRO_CPF_INTERESSADO_PROCESSO_AUTUACAO" class="FUNDOCAIXA1" maxlength="14">
                </div>
            </div>
        </div>

        <div id="box-filtro-origem-autuacao-processo" class="box-filtro">
            <div class="row">
                <label>Origem do Pedido:</label>
                <div class="conteudo">
                    <input type="text" id="FILTRO_ORIGEM_PROCESSO_AUTUACAO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>
    </body>
</html>