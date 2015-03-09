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

$controller = Controlador::getInstance();
$auth = $controller->usuario;

$file = 'detalhar_documentos.php';
$name_recurso = str_replace('.', '_', $file);
if (!($controller->cache->test('recurso_' . $name_recurso))) {
    $recurso = DaoRecurso::getRecursoByUrl($file);
    if (isset($recurso->id)) {
        $controller->cache->save($recurso, 'recurso_' . $name_recurso, array('recurso_' . $recurso->id, 'paginas'));
    } else {
        $recurso = null;
    }
} else {
    $recurso = $controller->cache->load('recurso_' . $name_recurso);
}

$botoes = Util::getMenus($auth, $recurso, $controller->acl);

foreach ($recurso->dependencias as $arquivo) {
    include('interfaces/' . $arquivo);
}
?>

<html>
    <head>
        <script type="text/javascript">
            var boxOrigemOpen = false;
            var boxDestinoOpen = false;
            $(document).ready(function() {
                /*Botoes Menu*/
                /*Anexar/Apensar*/

                /*Calendarios*/
                $("#DATA_PRAZO_DETALHAR_DOCUMENTO").datepicker({
                    changeMonth: true,
                    changeYear: true
                });
                $("#DATA_ENTRADA_DETALHAR_DOCUMENTO").datepicker({
                    changeMonth: true,
                    changeYear: true
                });
                $("#DATA_DOCUMENTO_DETALHAR_DOCUMENTO").datepicker({
                    changeMonth: true,
                    changeYear: true
                });
                //Combo
                $('#PRIORIDADE_DETALHAR_DOCUMENTO').combobox('modelos/combos/lista_prioridades.php');
                /*Auto Completes*/
                /*Origem*/
                $("#FILTRO_ORIGEM_DETALHAR_DOCUMENTO").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idTypeField: 'TIPO_ORIGEM_DETALHAR_DOCUMENTO',
                    idComboBox: 'ORIGEM_DETALHAR_DOCUMENTO',
                    paramTypeName: 'type',
                    extraParams: {
                        action: 'documentos-origens'
                    }
                });
                /*Destino*/
                $("#FILTRO_DESTINO_DETALHAR_DOCUMENTO").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idTypeField: 'TIPO_DESTINO_DETALHAR_DOCUMENTO',
                    idComboBox: 'DESTINO_DETALHAR_DOCUMENTO',
                    paramTypeName: 'type',
                    extraParams: {
                        action: 'documentos-origens'
                    }
                });
                $("#FILTRO_ASSUNTO_DETALHAR_DOCUMENTO").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox: 'ASSUNTO_DETALHAR_DOCUMENTO',
                    extraParams: {
                        action: 'documentos-assuntos'
                    }

                });
                /*Dialog*/
                /*Detalhar documento*/
                $('#div-form-detalhar-documentos').dialog({
                    title: 'Detalhes do Documento',
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    width: 610,
                    height: 590,
                    beforeClose: function() {
                        boxDestinoOpen = false;
                        boxOrigemOpen = false;
                    },
                    close: function() {
                        $('#box-filtro-tipo-origem-detalhar-documentos').dialog('close');
                        $('#box-filtro-destino-detalhar-documentos').dialog('close');
                    }
                });
                /*Filtro Origem*/
                $('#box-filtro-tipo-origem-detalhar-documentos').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 120,
                    open: function() {
                        $("#FILTRO_ORIGEM_DETALHAR_DOCUMENTO").val('');
                        boxOrigemOpen = true;
                    },
                    close: function() {
                        boxOrigemOpen = false;
                    }
                });
                /*Filtro Destino*/
                $('#box-filtro-destino-detalhar-documentos').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 120,
                    open: function() {
                        $("#FILTRO_DESTINO_DETALHAR_DOCUMENTO").val('');
                        boxDestinoOpen = true;
                    },
                    close: function() {
                        boxDestinoOpen = false;
                    }
                });
                /*Filtro Assunto*/
                $('#box-filtro-assunto-detalhar-documento').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 90,
                    open: function() {
                        $("#FILTRO_ASSUNTO_DETALHAR_DOCUMENTO").val('');
                    }
                });
                /*Listeners*/
                /*Botao salvar alteracoes*/
                $('#botao-salvar-alteracoes-documentos').click(function() {
                    var validou = jquery_validar_campos_alterar_documentos();
                    if (validou == true) {
                        jquery_alterar_documento();
                    } else {
                        alert(validou);
                    }
                });

                /*Botao historico tramite*/
                $('#botao-historico-tramite-documento').click(function() {
                    popup('historico_tramite_documentos.php?digital=' + $('#DIGITAL_DETALHAR_DOCUMENTO').val(), function() {

                    });
                });

                /*Botao folha de despachos*/
                $('#botao-folha-despacho-documento').click(function() {
                    popup('folha_despacho_documento.php?digital=' + $('#DIGITAL_DETALHAR_DOCUMENTO').val(), function() {

                    });
                });

                /*Botao historico comentarios*/
                $('#botao-historico-comentarios-documento').click(function() {
                    popup('historico_comentarios_documento.php?digital=' + $('#DIGITAL_DETALHAR_DOCUMENTO').val(), function() {

                    });
                });

                /*Botao historico despachos*/
                $('#botao-historico-despachos-documento').click(function() {
                    popup('historico_despachos_documento.php?digital=' + $('#DIGITAL_DETALHAR_DOCUMENTO').val(), function() {

                    });
                });

                /*Botao vinculacao documentos*/
                $('#botao-vinculacao-documento').click(function() {
                    jquery_listar_vinculacao_documento($('#DIGITAL_DETALHAR_DOCUMENTO').val(), true);
                });

                /*Filtro Origem*/
                $('#botao-filtro-tipo-origem-detalhar-documentos').click(function() {
                    $('#box-filtro-tipo-origem-detalhar-documentos').dialog('open');
                });
                /*Filtro Destino*/
                $('#botao-filtro-destino-detalhar-documentos').click(function() {
                    $('#box-filtro-destino-detalhar-documentos').dialog('open');
                });
                /*Filtro Assunto*/
                $('#botao-filtro-assunto-detalhar-documento').click(function() {
                    $('#box-filtro-assunto-detalhar-documento').dialog('open');
                });
                /*Binds*/
                /*Origem*/
                $("#box-filtro-tipo-origem-detalhar-documentos").bind("dialogclose", function(event, ui) {
                    /*Bug Fix :: Evitar que a mensagem de inclusao de novo interessado apareca novamente*/
                    if (!boxOrigemOpen) {
                        return false;
                    }

                    var exists = false;
                    var c = false;
                    if ($('#TIPO_ORIGEM_DETALHAR_DOCUMENTO').val() != 'IN' && $('#TIPO_ORIGEM_DETALHAR_DOCUMENTO').val() != 'PR') {
                        $("#ORIGEM_DETALHAR_DOCUMENTO option").each(function() {
                            if ($('#FILTRO_ORIGEM_DETALHAR_DOCUMENTO').val() == $(this).text()) {
                                exists = true;
                            }
                        });
                        if (!exists) {

                            if ($('#ORIGEM_DETALHAR_DOCUMENTO option').length > 0) {
                                c = confirm('Atenção: ' + $('#ORIGEM_DETALHAR_DOCUMENTO option').length + ' Origem(s) localizada(s). Mas nao sao identicas a origem informada (' + $('#FILTRO_ORIGEM_DETALHAR_DOCUMENTO').val() + ') !\nDeseja adiciona-la na base de dados de origens de documentos?');
                            } else {
                                c = confirm('Esta origem nao foi encontrada!\nDeseja adiciona-la na base de dados de origens de documentos?');
                            }

                            if (c) {
                                $.post("modelos/documentos/pessoa.php", {
                                    acao: 'adicionar',
                                    pessoa: $('#FILTRO_ORIGEM_DETALHAR_DOCUMENTO').val(),
                                    tipo: $('#TIPO_ORIGEM_DETALHAR_DOCUMENTO').val()
                                },
                                function(data) {
                                    try {
                                        if (data.success == 'true') {
                                            var options = $('#ORIGEM_DETALHAR_DOCUMENTO').attr('options');
                                            $('option', '#ORIGEM_DETALHAR_DOCUMENTO').remove();
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
                /*Destino*/
                /*Binds*/
                $("#box-filtro-destino-detalhar-documentos").bind("dialogclose", function(event, ui) {

                    if (!boxDestinoOpen) {
                        return false;
                    }

                    var exists = false;
                    var c = false;
                    if ($('#TIPO_DESTINO_DETALHAR_DOCUMENTO').val() != 'IN' && $('#TIPO_DESTINO_DETALHAR_DOCUMENTO').val() != 'PR') {
                        $("#DESTINO_DETALHAR_DOCUMENTO option").each(function() {
                            if ($('#FILTRO_DESTINO_DETALHAR_DOCUMENTO').val() == $(this).text()) {
                                exists = true;
                            }
                        });
                        if (!exists) {

                            if ($('#DESTINO_DETALHAR_DOCUMENTO option').length > 0) {
                                c = confirm('Atenção: ' + $('#DESTINO_DETALHAR_DOCUMENTO option').length + ' Destino(s) localizado(s). Mas nao sao identicos ao destino informado (' + $('#FILTRO_DESTINO_DETALHAR_DOCUMENTO').val() + ') !\nDeseja adiciona-lo na base de dados de destinos de documentos?');
                            } else {
                                c = confirm('Este destino nao foi encontrado!\nDeseja adiciona-lo na base de dados de destinos de documentos?');
                            }

                            if (c) {
                                $.post("modelos/documentos/pessoa.php", {
                                    acao: 'adicionar',
                                    pessoa: $('#FILTRO_DESTINO_DETALHAR_DOCUMENTO').val(),
                                    tipo: $('#TIPO_DESTINO_DETALHAR_DOCUMENTO').val()
                                },
                                function(data) {
                                    try {
                                        if (data.success == 'true') {
                                            var options = $('#DESTINO_DETALHAR_DOCUMENTO').attr('options');
                                            $('option', '#DESTINO_DETALHAR_DOCUMENTO').remove();
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
            });
            /*Funcoes*/
            /*Validar Campos Cadastro Documento*/
            function jquery_validar_campos_alterar_documentos() {
                /*Validar tipo de origem procuradorias*/
                if ($('#TIPO_ORIGEM_DETALHAR_DOCUMENTO').val() == 'PR' && $('#DATA_PRAZO_DETALHAR_DOCUMENTO').val().length != 10) {
                    return 'Ao escolher o tipo de origem "Procuradorias Federais" o campo prazo torna-se obrigatorio!';
                }
                /*Validar campos*/
                if (
                        (
                                $('#DIGITAL_DETALHAR_DOCUMENTO').val().length == 7 &&
                                $('#TIPO_DETALHAR_DOCUMENTO').val() &&
                                $('#NUMERO_DETALHAR_DOCUMENTO').val() &&
                                $('#ORIGEM_DETALHAR_DOCUMENTO').val() &&
                                $('#DATA_DOCUMENTO_DETALHAR_DOCUMENTO').val() &&
                                $('#DESTINO_DETALHAR_DOCUMENTO').val() &&
                                $('#TECNICO_RESPONSAVEL_DETALHAR_DOCUMENTO').val() &&
                                $('#ASSUNTO_DETALHAR_DOCUMENTO').val() &&
                                $('#PROCEDENCIA_DETALHAR_DOCUMENTO').val() == 'I'
                                ) || (
                        $('#DIGITAL_DETALHAR_DOCUMENTO').val().length == 7 &&
                        $('#TIPO_DETALHAR_DOCUMENTO').val() &&
                        $('#NUMERO_DETALHAR_DOCUMENTO').val() &&
                        $('#ORIGEM_DETALHAR_DOCUMENTO').val() &&
                        $('#DATA_DOCUMENTO_DETALHAR_DOCUMENTO').val() &&
                        $('#DESTINO_DETALHAR_DOCUMENTO').val() &&
                        $('#TECNICO_RESPONSAVEL_DETALHAR_DOCUMENTO').val() &&
                        $('#ASSUNTO_DETALHAR_DOCUMENTO').val() &&
                        $('#PROCEDENCIA_DETALHAR_DOCUMENTO').val() == 'E' &&
                        $('#DATA_ENTRADA_DETALHAR_DOCUMENTO').val() &&
                        $('#RECIBO_DETALHAR_DOCUMENTO').val()
                        )
                        ) {
                    /*Validar duplicidade de documento*/
                    $.ajaxSetup({async: false});
                    var retorno = false;
                    $.post("modelos/documentos/documentos.php", {
                        acao: 'unique',
                        id: $('#ID_DETALHAR_DOCUMENTO').val(),
                        tipo: $('#TIPO_DETALHAR_DOCUMENTO').val(),
                        numero: $('#NUMERO_DETALHAR_DOCUMENTO').val(),
                        origem: $('#ORIGEM_DETALHAR_DOCUMENTO').val()
                    },
                    function(data) {
                        if (data.success == 'true') {
                            retorno = true;
                        } else {
                            retorno = data.error;
                        }
                    }, "json");
                    return retorno;
                } else {
                    return 'Campo(s) obrigatório(s) em branco ou preenchidos de forma inválida!';
                }
            }


            /*Inserir documento*/
            function jquery_alterar_documento() {
                $('#progressbar').show();
                if (confirm('Você tem certeza que deseja alterar as informações deste documento?')) {
                    $.post("modelos/documentos/documentos.php", {
                        acao: 'alterar',
                        digital: $('#DIGITAL_DETALHAR_DOCUMENTO').val(),
                        tipo: $('#TIPO_DETALHAR_DOCUMENTO').val(),
                        numero: $('#NUMERO_DETALHAR_DOCUMENTO').val(),
                        origem: $('#ORIGEM_DETALHAR_DOCUMENTO').val(),
                        dt_documento: $('#DATA_DOCUMENTO_DETALHAR_DOCUMENTO').val(),
                        destino: $('#DESTINO_DETALHAR_DOCUMENTO').val(),
                        tecnico_responsavel: $('#TECNICO_RESPONSAVEL_DETALHAR_DOCUMENTO').val(),
                        assunto: $('#ASSUNTO_DETALHAR_DOCUMENTO').val(),
                        assunto_complementar: $('#ASSUNTO_COMPLEMENTAR_DETALHAR_DOCUMENTO').val(),
                        prioridade: $('#PRIORIDADE_DETALHAR_DOCUMENTO').val(),
                        assinatura: $('#ASSINATURA_DETALHAR_DOCUMENTO').val(),
                        interessado: $('#INTERESSADO_DETALHAR_DOCUMENTO').val(),
                        procedencia: $('#PROCEDENCIA_DETALHAR_DOCUMENTO').val(),
                        dt_entrada: $('#DATA_ENTRADA_DETALHAR_DOCUMENTO').val(),
                        recibo: $('#RECIBO_DETALHAR_DOCUMENTO').val(),
                        cargo: $('#CARGO_DETALHAR_DOCUMENTO').val(),
                        dt_prazo: $('#DATA_PRAZO_DETALHAR_DOCUMENTO').val(),
                        fg_prazo: $("#STATUS_PRAZO_DETALHAR_DOCUMENTO").attr('checked')
                    },
                    function(data) {
                        try {
                            if (data.success == 'true') {
                                $('#div-form-detalhar-documentos').dialog('close');
                                oTableDocumentos.fnDraw(false);
                                alert('Documento alterado com sucesso!');
                            } else {
                                alert(data.error);
                            }
                        } catch (e) {
                            alert('Ocorreu um erro ao tentar validar o digital!\n[' + e + ']');
                        }
                        $('#progressbar').hide();
                    }, "json");
                }
                $('#progressbar').hide();
            }


        </script>

        <style type="text/css">
            #notificacao-prazo-detalhar-documento{
                vertical-align: middle;
                margin-left: -300px;
                position: absolute;
                margin-top: 7px;    
                width: 270px;
                height: 14px;
                font-size: 10px;
                text-align: right;
                border: 0px solid #000;
            }

            #DATA_PRAZO_DETALHAR_DOCUMENTO{
                padding-left: 25px;
            }

            #STATUS_PRAZO_DETALHAR_DOCUMENTO{
                vertical-align: middle;
                margin-left: -395px;
                position: absolute;
                margin-top: 5px;    
                width: 20px;
                height: 20px;
            }

            .menu-detalhar-documentos{
                position: absolute;
                bottom: 15px;
                left: 25%;
            }
        </style>

    </head>
    <body>

        <div id="div-form-detalhar-documentos" class="div-form-dialog">
            <input type="hidden" id="ID_DETALHAR_DOCUMENTO">
            <div class="row" >
                <label class="label">*DIGITAL:</label>
                <span class="conteudo">
                    <input disabled type="text" id="FAKE_DIGITAL_DETALHAR_DOCUMENTO">
                    <input disabled type="hidden" id="DIGITAL_DETALHAR_DOCUMENTO">
                </span>
            </div>

            <div class="row">
                <label class="label">*PROCEDENCIA:</label>
                <span class="conteudo">
                    <select id='PROCEDENCIA_DETALHAR_DOCUMENTO'>
                        <option value='I'>Interno</option>
                        <option value='E'>Externo</option>
                    </select>
                </span>
            </div>

            <div class="row">
                <label class="label">*NUMERO:</label>
                <span class="conteudo">
                    <input type="text" id="NUMERO_DETALHAR_DOCUMENTO" maxlength="60" onKeyUp="DigitaLetraSeguro(this)">
                </span>
            </div>

            <div class="row">
                <label class="label">*TIPO:</label>
                <span class="conteudo">
                    <select id='TIPO_DETALHAR_DOCUMENTO'></select>
                </span>
            </div>

            <div class="row">
                <label class="label">*ORIGEM:</label>
                <span class="conteudo">
                    <select id="ORIGEM_DETALHAR_DOCUMENTO"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-tipo-origem-detalhar-documentos" src="imagens/fam/application_edit.png">
            </div>

            <div class="row">
                <label class="label">*ASSUNTO:</label>
                <span class="conteudo">
                    <select onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1" id="ASSUNTO_DETALHAR_DOCUMENTO"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-assunto-detalhar-documento" src="imagens/fam/application_edit.png">
            </div>

            <div class="row">
                <label class="label">ASSUNTO COMPLEMENTAR:</label>
                <span class="conteudo">
                    <input type="text" onKeyUp="DigitaLetraSeguro(this)" id="ASSUNTO_COMPLEMENTAR_DETALHAR_DOCUMENTO">
                </span>
            </div>

            <div class="row">
                <label>*PRIORIDADE:</label>
                <span class="conteudo">
                    <select type="text" id="PRIORIDADE_DETALHAR_DOCUMENTO"></select>
                </span>
            </div>

            <div class="row">
                <label class="label">*DESTINO:</label>
                <span class="conteudo">
                    <select id="DESTINO_DETALHAR_DOCUMENTO"></select>
                </span>
                <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-destino-detalhar-documentos" src="imagens/fam/application_edit.png">
            </div>

            <div class="row">
                <label class="label">*ENCAMINHADO PARA:</label>
                <span class="conteudo">
                    <input type="text" onKeyUp="DigitaLetraSeguro(this)" id="TECNICO_RESPONSAVEL_DETALHAR_DOCUMENTO" maxlength="60">
                </span>
            </div>

            <div class="row">
                <label class="label">INTERESSADO:</label>
                <span class="conteudo">
                    <input type="text" onKeyUp="DigitaLetraSeguro(this)" id="INTERESSADO_DETALHAR_DOCUMENTO" maxlength="200">
                </span>
            </div>

            <div class="row">
                <label class="label">ASSINATURA:</label>
                <span class="conteudo">
                    <input type="text" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1" id="ASSINATURA_DETALHAR_DOCUMENTO" maxlength="60">
                </span>
            </div>

            <div class="row">
                <label class="label">CARGO:</label>
                <span class="conteudo">
                    <input type="text" onKeyUp="DigitaLetraSeguro(this)" id="CARGO_DETALHAR_DOCUMENTO" maxlength="60">
                </span>
            </div>

            <div class="row">
                <label class="label">DATA DO PRAZO:</label>
                <span class="conteudo">
                    <input type="text" id="DATA_PRAZO_DETALHAR_DOCUMENTO" maxlength="10" readonly="true">
                </span>
                <span id="notificacao-prazo-detalhar-documento" class="notificacao-prazo-vermelho"></span>
                <input type="checkbox" id="STATUS_PRAZO_DETALHAR_DOCUMENTO" title="Ativar ou Desativar prazo geral documento.">
                <img title="Limpar" src="imagens/fam/delete.png" onClick="limparCampoData('DATA_PRAZO_DETALHAR_DOCUMENTO');" class="botao-auxiliar">
            </div>

            <div class="row">
                <label class="label">*DATA DO DOCUMENTO:</label>
                <span class="conteudo">
                    <input type="text" id="DATA_DOCUMENTO_DETALHAR_DOCUMENTO" maxlength="10" readonly="true">
                </span>
            </div>

            <div class="row">
                <label class="label">DATA DA ENTRADA:</label>
                <span class="conteudo">
                    <input disabled type="text" id="DATA_ENTRADA_DETALHAR_DOCUMENTO" maxlength="10" readonly="true" >
                </span>
            </div>

            <div class="row">
                <label class="label">RECEBIDO POR:</label>
                <span class="conteudo">
                    <input type="text" onKeyUp="DigitaLetraSeguro(this)" disabled="disabled" id="RECIBO_DETALHAR_DOCUMENTO" maxlength="60">
                </span>
            </div>

            <div class="menu-detalhar-documentos">
                <?php Util::montaMenus($botoes, array('class' => 'botao32')); ?>
            </div>

            <div id="box-filtro-tipo-origem-detalhar-documentos" class="box-filtro">
                <div class="row">
                    <label class="label">TIPO DE ORIGEM:</label>
                    <span class="conteudo">
                        <select id="TIPO_ORIGEM_DETALHAR_DOCUMENTO" class="FUNDOCAIXA1">
                            <option value="IN">Unidades ICMBio</option>
                            <option value="PR">Procuradorias Federais</option>
                            <option value="PF">Pessoa Fisica</option>
                            <option value="PJ">Pessoa Juridica</option>
                            <option value="OF">Outros Orgaos</option>
                        </select>
                    </span>
                </div>
                <div class="row">
                    <label class="label">ORIGEM:</label>
                    <span class="conteudo">
                        <input type="text" id="FILTRO_ORIGEM_DETALHAR_DOCUMENTO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                    </span>
                </div>
            </div>

            <div id="box-filtro-destino-detalhar-documentos" class="box-filtro">
                <div class="row">
                    <label class="label">TIPO DE DESTINO:</label>
                    <span class="conteudo">
                        <select id="TIPO_DESTINO_DETALHAR_DOCUMENTO" class="FUNDOCAIXA1">
                            <option value="IN">Unidades ICMBio</option>
                            <option value="PR">Procuradorias Federais</option>
                            <option value="PF">Pessoa Fisica</option>
                            <option value="PJ">Pessoa Juridica</option>
                            <option value="OF">Outros Orgaos</option>
                        </select>
                    </span>
                </div>
                <div class="row">
                    <label class="label">DESTINO:</label>
                    <span class="conteudo">
                        <input type="text" id="FILTRO_DESTINO_DETALHAR_DOCUMENTO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                    </span>
                </div>
            </div>

            <div id="box-filtro-assunto-detalhar-documento" class="box-filtro">
                <div class="row">
                    <label>Assunto:</label>
                    <div class="conteudo">
                        <input type="text" id="FILTRO_ASSUNTO_DETALHAR_DOCUMENTO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>