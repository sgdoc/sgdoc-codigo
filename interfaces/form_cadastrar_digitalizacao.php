
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
        <script type="text/javascript">

            $(document).ready(function() {

                $("#vinculacao_digitalizacao").hide();

                $('#combo_tipo_vinculacao').change(function() {
                    switch ($(this).val()) {
                        case '':
                            // Comum
                            $("#vinculacao_digitalizacao").hide();
                            break;
                        case 'VOL.':
                            $("#vinculacao_digitalizacao").show();
                            $("#label_vinculacao_digitalizacao").text('*NUMERO DO VOLUME:');
                            break;

                        case 'ANEXO':
                            $("#vinculacao_digitalizacao").show();
                            $("#label_vinculacao_digitalizacao").text('*NUMERO DO ANEXO:');
                            break;

                        default:
                            break;
                    }
                    if ($(this).val() != '0') {
                        $("#NUMERO_PECA_CADASTRAR_DIGITALIZACAO").focus();
                    } else {
                        $("#DIGITAL_CADASTRAR_DIGITALIZACAO").focus();
                    }
                });

                /*Listeners*/
                $('#NUMERO_CADASTRAR_DIGITALIZACAO').keyup(function() {
                    formatar_numero_processo(document.getElementById('NUMERO_CADASTRAR_DIGITALIZACAO'));
                });

                $('#DIGITAL_CADASTRAR_DIGITALIZACAO').keyup(function() {
                    if ($(this).val().length == 7) {
                        try {
                            if (jquery_validar_digital($(this).val())) {
                                if ($('#TIPO_CADASTRAR_DIGITALIZACAO').find('option').length < 1) {
                                    /*Carregar o combo de tipologias de documentos*/
                                    $('#TIPO_CADASTRAR_DIGITALIZACAO').combobox('modelos/combos/tipologias_documentos.php');
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

                /*Evento Blur no campo numero do processo*/
                $('#NUMERO_CADASTRAR_DIGITALIZACAO').blur(function() {

                    try {
                        /*Esta variavel sera true quando a validacao local do numero do processo for valida*/
                        var valido = false;

                        switch ($(this).val().length) {
                            case 20:
                                if (isInteger(($(this).val().substr(13, 4)))) {
                                    /*Processos de 1999 ou anteriores*/
                                    if ($(this).val().substr(13, 4) <= 1999) {
                                        alert('Os processos gerados em 1999 ou anteriomente devem possuir 2 digitos para representar o ano da autuação.');
                                        $(this).focus();
                                        return false;
                                    } else {
                                        valido = true;
                                    }

                                    /*Processos de 2003 ou posteriores*/
                                    if ($(this).val().substr(13, 4) >= 2003) {
                                        if (!validar_digito_verificador_processo(document.getElementById('NUMERO_CADASTRAR_DIGITALIZACAO'))) {
                                            alert('Numero do processo invalido!\nObs: O digito correto será: [' + verificador_valido(document.getElementById('NUMERO_CADASTRAR_DIGITALIZACAO')) + ']');
                                            $(this).focus();
                                            return false;
                                        } else {
                                            valido = true;
                                        }

                                    }

                                    /*Processo entre 2000 e 2002*/
                                    if ($(this).val().substr(13, 4) >= 2000 && $(this).val().substr(13, 4) <= 2002) {
                                        if (!validar_digito_verificador_processo(document.getElementById('NUMERO_CADASTRAR_DIGITALIZACAO'))) {
                                            alert('Numero do processo invalido!\nObs: O digito correto será: [' + verificador_valido(document.getElementById('NUMERO_CADASTRAR_DIGITALIZACAO')) + ']');
                                            $(this).focus();
                                            return false;
                                        } else {
                                            valido = true;
                                        }
                                    }

                                } else {
                                    alert('Número de processo inválido!');
                                }
                                break;

                            case 18:
                                if (isInteger($(this).val().substr(13, 2))) {
                                    /*Processo entre 1940 e 1999*/
                                    if ($(this).val().substr(13, 2) >= 40 && $(this).val().substr(13, 2) <= 99) {
                                        if (!validar_digito_verificador_processo(document.getElementById('NUMERO_CADASTRAR_DIGITALIZACAO'))) {
                                            alert('Numero do processo invalido!\nObs: O digito correto será: [' + verificador_valido(document.getElementById('NUMERO_CADASTRAR_DIGITALIZACAO')) + ']');
                                            $(this).focus();
                                            return false;
                                        } else {
                                            valido = true;
                                        }
                                    }
                                    /*Processos entre 2003 e 2039*/
                                    if ($(this).val().substr(13, 2) >= 03 && $(this).val().substr(13, 2) <= 39) {
                                        alert('Os processos gerados em 2003 ou posteriomente devem possuir 4 digitos para representar o ano da autuação.');
                                        $(this).focus();
                                        return false;
                                    } else {
                                        valido = true;
                                    }
                                    /*Processos entre 2000 e 2002*/
                                    if ($(this).val().substr(13, 2) >= 00 && $(this).val().substr(13, 2) <= 02) {
                                        if (!validar_digito_verificador_processo(document.getElementById('NUMERO_CADASTRAR_DIGITALIZACAO'))) {
                                            alert('Numero do processo invalido!\nObs: O digito correto será: [' + verificador_valido(document.getElementById('NUMERO_CADASTRAR_DIGITALIZACAO')) + ']');
                                            $(this).focus();
                                            return false;
                                        } else {
                                            valido = true;
                                        }
                                    }
                                } else {
                                    alert('Número de processo inválido!');
                                }
                                break;
                            default:
                                alert('Número de processo inválido!');
                                $(this).val('');
                                break;
                        }

                        /*Se a validacao local for valida entao Verificar no banco se o processo ja foi cadastrado*/
                        if (valido == true) {
                            jquery_pegar_processo($(this).val());
                        }

                    } catch (e) {
                        alert(e);
                    }

                });

                $('#PROCEDENCIA_CADASTRAR_DIGITALIZACAO').change(function() {
                    switch ($(this).val()) {
                        case 'I':
                            $('#RECIBO_CADASTRAR_DIGITALIZACAO').attr('disabled', 'disabled');
                            $('#DATA_ENTRADA_CADASTRAR_DIGITALIZACAO').attr('disabled', 'disabled');
                            $('#RECIBO_CADASTRAR_DIGITALIZACAO').val('');
                            $('#DATA_ENTRADA_CADASTRAR_DIGITALIZACAO').val('');
                            break;

                        case 'E':
                            $('#RECIBO_CADASTRAR_DIGITALIZACAO').removeAttr('disabled');
                            $('#DATA_ENTRADA_CADASTRAR_DIGITALIZACAO').removeAttr('disabled');
                            break;

                        default:
                            break;
                    }
                });
            });

            /*Verificar se o numero do processo ja foi cadastrado*/
            function jquery_pegar_processo(numero_processo) {
                $.ajax({
                    type: 'POST',
                    url: 'modelos/processos/processos.php',
                    data: 'acao=pegar-processo&numero_processo=' + numero_processo,
                    async: false,
                    dataType: 'json',
                    success: function(data) {
                        if (data.success == 'true') {
                            if (data.existe == 'true') {
                                // Processo existe, carregar
                                var processo = data.processo;
                                if (processo.procedencia == 'I') {
                                    $('#ORIGEM_CADASTRAR_DIGITALIZACAO').val(jquery_get_unidades(processo.origem, 'nome'));
                                } else {
                                    $('#ORIGEM_CADASTRAR_DIGITALIZACAO').val(jquery_get_origem_externa(processo.origem, 'origem'));
                                }
                                $('#DATA_DOCUMENTO_CADASTRAR_DIGITALIZACAO').val(processo.dt_autuacao);
                                $('#ASSUNTO_CADASTRAR_DIGITALIZACAO').val(jquery_get_assunto_processo(processo.assunto, 'assunto'));
                                $('#ASSUNTO_COMPLEMENTAR_CADASTRAR_DIGITALIZACAO').val(processo.assunto_complementar);
                                $('#INTERESSADO_CADASTRAR_DIGITALIZACAO').val(jquery_get_interessado_processo(processo.interessado, 'interessado'));
                                $('#NUMERO_CADASTRAR_DIGITALIZACAO').attr('disabled', true);
                            } else {
                                // Processo não existe, retornar erro
                                alert("Não existe processo com o número digitado")
                            }
                        } else {
                            alert("Ocorreu um erro ao tentar carregar o processo")
                        }
                    },
                    failure: function(error) {
                    }
                });
            }

            /*Validar Campos Cadastro Documento*/
            function jquery_validar_campos_cadastrar_digitalizacao() {
                /*Validar campos*/
                if ($('#DIGITAL_CADASTRAR_DIGITALIZACAO').val().length == 7 &&
                        $('#NUMERO_CADASTRAR_DIGITALIZACAO').val() &&
                        $('#ID_CLASSIFICACAO_CADASTRAR_DIGITALIZACAO').val() != '0' &&
                        ($('#combo_tipo_vinculacao').val() == '') || $('#NUMERO_PECA_CADASTRAR_DIGITALIZACAO').val()) {
                    /*Validar o digital*/
                    if (jquery_validar_digital($('#DIGITAL_CADASTRAR_DIGITALIZACAO').val())) {
                        return jquery_cadastrar_digitalizacao();
                    } else {
                        alert('Digital inexistente ou já utilizado!');
                        return false;
                    }

                } else {
                    alert('Campo(s) obrigatório(s) em branco ou preenchidos de forma inválida!');
                }
            }

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
            function jquery_cadastrar_digitalizacao() {
                if (confirm('Você tem certeza que deseja cadastrar esta digitalização de processo?')) {
                    $.post("modelos/documentos/cadastrar_vincular.php", {
                        digital: $('#DIGITAL_CADASTRAR_DIGITALIZACAO').val(),
                        numero: $('#NUMERO_CADASTRAR_DIGITALIZACAO').val(),
                        tipo_vinculacao: $('#combo_tipo_vinculacao').val(),
                        classificacao: $('#ID_CLASSIFICACAO_CADASTRAR_DIGITALIZACAO').val(),
                        numero_peca: $('#NUMERO_PECA_CADASTRAR_DIGITALIZACAO').val()
                    },
                    function(data) {
                        try {
                            if (data.success == 'true') {
                                /*Alert*/
                                $('#div-form-cadastrar-digitalizacao').dialog('close');
                                oTableDocumentos.fnDraw(false);
                                alert('Documento cadastrado com sucesso!');
                            } else {
                                alert(data.error);
                            }
                        } catch (e) {
                            alert('Ocorreu um erro ao tentar validar o digital!\n[' + e + ']');
                        }
                    }, "json");
                }
            }

            //            /*Gerar numero documento*/
            //            function jquery_gerar_numero_documento(tipo){
            //                if(confirm('Você tem certeza que deseja gerar um novo numero para a tipologia "'+tipo+'"?\nAtenção! Este procedimento nao pode ser desfeito!')){
            //                    $.post("modelos/documentos/gerar_numeracao.php", {
            //                        tipologia: tipo
            //                    },
            //                    function(data){
            //                        try{
            //                            if(data.success == 'true'){
            //                                if(data.numero){
            //                                    $('#NUMERO_CADASTRAR_DIGITALIZACAO').val(data.numero);
            //                                    $('#NUMERO_CADASTRAR_DIGITALIZACAO').attr('disabled','disabled');
            //                                    $('#TIPO_CADASTRAR_DIGITALIZACAO').attr('disabled','disabled');
            //                                    // $('#botao-gerar-numero-cadastrar-documentos').attr('disabled','disabled');
            //                                }
            //                            }else{
            //                                alert(data.error);
            //                            }
            //                        }catch(e){
            //                            alert('Ocorreu um erro ao tentar inclementar o numero da tipologia escolhidal!\n['+e+']');
            //                        }
            //                    }, "json");
            //                }
            //            }
            //
            $(document).ready(function() {
                /*Calendarios*/
                $('#div-form-cadastrar-digitalizacao').dialog({
                    title: 'Novo Documento Digitalizado',
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    width: 650,
                    height: 435,
                    close: function() {
                        /*Liberar campos tipo e numero possivelmente bloqueados*/
                        $('#NUMERO_CADASTRAR_DIGITALIZACAO').removeAttr('disabled').val('');
                        /*Limpar os campos*/
                        $('#DIGITAL_CADASTRAR_DIGITALIZACAO').val('');
                        $('#combo_tipo_vinculacao').val('');
                        $("#vinculacao_digitalizacao").hide();
                        $('#NUMERO_PECA_CADASTRAR_DIGITALIZACAO').val('');
                        $('#ID_CLASSIFICACAO_CADASTRAR_DIGITALIZACAO').val('');
                        $('#ORIGEM_CADASTRAR_DIGITALIZACAO').val('');
                        $('#DATA_DOCUMENTO_CADASTRAR_DIGITALIZACAO').val('');
                        $('#ASSUNTO_CADASTRAR_DIGITALIZACAO').val('');
                        $('#ASSUNTO_COMPLEMENTAR_CADASTRAR_DIGITALIZACAO').val('');
                        $('#INTERESSADO_CADASTRAR_DIGITALIZACAO').val('');
                    },
                    buttons: {
                        Salvar: function() {
                            jquery_validar_campos_cadastrar_digitalizacao();
                        },
                        Cancelar: function() {
                            $(this).dialog('close');
                        }
                    }
                });
                /*Abrir form novo documento*/
                $('#botao-digitalizado-novo').click(function() {
                    $('#div-form-cadastrar-digitalizacao').dialog('open');
                });

                $('#ID_CLASSIFICACAO_CADASTRAR_DIGITALIZACAO').combobox('modelos/combos/classificacoes.php', {'tipo': 'filhos'});
            });

        </script>

    </head>
    <body>
        <!--Formulario-->
        <div class="div-form-dialog" id="div-form-cadastrar-digitalizacao">

            <div class="row">
                <label class="label">*DIGITAL:</label>
                <span class="conteudo">
                    <input type="text" id="DIGITAL_CADASTRAR_DIGITALIZACAO" maxlength="7" onKeyPress="DigitaNumero(this);">
                </span>
            </div>

            <div class="row">
                <label class="label">*NUMERO DO PROCESSO:</label>
                <span class="conteudo">
                    <input type="text" id="NUMERO_CADASTRAR_DIGITALIZACAO" maxlength="50" onKeyUp="DigitaLetraSeguro(this)">
                </span>
            </div>

            <div class="row">
                <label class="label">CLASSIFICACAO:</label>
                <span class="conteudo">
                    <select class="FUNDOCAIXA1" id="ID_CLASSIFICACAO_CADASTRAR_DIGITALIZACAO"></select>
                </span>
            </div>

            <div class="row">
                <label>TIPO VINCULAÇÃO:</label>
                <span class="conteudo">
                    <select class="FUNDOCAIXA1" id="combo_tipo_vinculacao">
                        <option selected="selected" value="">COMUM</option>
                        <option value="VOL.">VOLUME</option>
                        <option value="ANEXO">ANEXO</option>
                    </select>
                </span>
            </div>

            <div class="row" id="vinculacao_digitalizacao">
                <label id="label_vinculacao_digitalizacao">*NUMERO DO VOLUME:</label>
                <span class="conteudo">
                    <input type="text" maxlength="8" id="NUMERO_PECA_CADASTRAR_DIGITALIZACAO">
                </span>
            </div>

            <div class="row">
                <label class="label">TIPO DOCUMENTO:</label>
                <span class="conteudo">
                    <input type="text" id="TIPO_CADASTRAR_DIGITALIZACAO" disabled="disabled" value="DIGITALIZACAO DE PROCESSO" />
                </span>
            </div>

            <div class="row">
                <label>ORIGEM:</label>
                <span class="conteudo">
                    <input type="text" id="ORIGEM_CADASTRAR_DIGITALIZACAO" disabled="disabled" />
                </span>
            </div>

            <div class="row">
                <label>DATA DO DOCUMENTO:</label>
                <span class="conteudo">
                    <input type="text" id="DATA_DOCUMENTO_CADASTRAR_DIGITALIZACAO" maxlength="10" disabled="disabled" />
                </span>
            </div>

            <div class="row">
                <label>ASSUNTO:</label>
                <span class="conteudo">
                    <input type="text" id="ASSUNTO_CADASTRAR_DIGITALIZACAO" disabled="disabled" />
                </span>
            </div>

            <div class="row">
                <label>ASSUNTO COMPLEMENTAR:</label>
                <span class="conteudo">
                    <input type="text" id="ASSUNTO_COMPLEMENTAR_CADASTRAR_DIGITALIZACAO" disabled="disabled" />
                </span>
            </div>

            <div class="row">
                <label>INTERESSADO:</label>
                <span class="conteudo">
                    <input type="text" id="INTERESSADO_CADASTRAR_DIGITALIZACAO" disabled="disabled" />
                </span>
            </div>
        </div>

    </body>
</html>