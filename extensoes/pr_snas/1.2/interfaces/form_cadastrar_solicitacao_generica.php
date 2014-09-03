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
        print(Util::autoLoadJavascripts(array('javascripts/jquery.form.js')));
        ?>
        <script type="text/javascript" src="plugins/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
        <script type="text/javascript">
            //var oTableDocumentosDemandasUnidade = null;

            /*Validar Campos Cadastro Documento*/
            function jquery_validar_campos_cadastrar_solicitacoes_generica() {
                /*Validar tipo de origem procuradorias*/
                /*Validar campos*/
                if (
                        $('#DATA_DOCUMENTO_CADASTRAR_SOLICITACOES_GENERICA').val() &&
                        $('#ASSUNTO_CADASTRAR_SOLICITACOES_GENERICA').val() &&
//                        $('#PRIORIDADE_CADASTRAR_SOLICITACOES_GENERICA').val() &&
                        $('#PRAZO_CADASTRAR_SOLICITACOES_GENERICA').val() &&
                        $('#NOME_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA').val() &&
                        $('#TRAMITAR_PARA_CADASTRAR_SOLICITACOES_GENERICA').val() &&
                        ($('#TRAMITAR_PARA_CADASTRAR_SOLICITACOES_GENERICA').val() != '0') &&
                        $('#OCR_CADASTRAR_SOLICITACOES_GENERICA').val()
                        ) {
                    /*Validar o cpf/cnpj antes de submeter*/
                    var tipo = $('#combo_cpf_interessado_sic').val();
                    if (tipo != '0') {
                        var cpf = $('#CPF_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA').val();
                        if (cpf.length > 0) {
                            if (!jquery_validar_cpf_cnpj(cpf)) {
                                alert($("#LABEL_CPF_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA").text() + ' invalido!');
                                return false;
                            }
                        }
                    }
                    return jquery_cadastrar_solicitacao_generica();
                } else {
                    alert('Campo(s) obrigatório(s) em branco ou preenchidos de forma inválida!');
                }
            }


            /*Inserir documento*/
            function jquery_cadastrar_solicitacao_generica() {

                $('#progressbar').show();

                if (confirm('Você tem certeza que deseja cadastrar esta demanda?')) {

                    var extras = {PRIORIDADES: 0/*, PARTICIPANTES:1*/};
                    extras.PRIORIDADES = {id: 0, id_campo: 1};
//                                    extras.PARTICIPANTES = {id:0, id_campo:1};
                    extras.PRIORIDADES.id = new Array();
                    extras.PRIORIDADES.id_campo = new Array();
//                                    extras.PARTICIPANTES.id = new Array();
//                                    extras.PARTICIPANTES.id_campo = new Array();
                    $('#container-prioridades span select option').each(function() {
                        $(this).each(function() {
                            extras.PRIORIDADES.id.push($(this).attr('value'));
                            extras.PRIORIDADES.id_campo.push($(this).attr('id_campo'));
                        });
                    });
//                                    $('#container-participantes span select option').each(function(){
//                            $(this).each(function() {
//                            extras.PARTICIPANTES.id.push($(this).attr('value'));
//                                    extras.PARTICIPANTES.id_campo.push($(this).attr('id_campo'));
//                            });
//                            });

                    $.post('extensoes/pr_snas/1.2/modelos/generico/cf.php', {
                        ASSUNTO: $('#ASSUNTO_CADASTRAR_SOLICITACOES_GENERICA :selected').text(),
                        ID_ASSUNTO: $('#ASSUNTO_CADASTRAR_SOLICITACOES_GENERICA').val(),
                        ASSUNTO_COMPLEMENTAR: $('#ASSUNTO_COMPLEMENTAR_CADASTRAR_SOLICITACOES_GENERICA').val(),
                        DT_DOCUMENTO: $('#DATA_DOCUMENTO_CADASTRAR_SOLICITACOES_GENERICA').val(),
                        INTERESSADO: $('#NOME_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA').val() + ' - ' + $('#CPF_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA').val() + ' - ' + $('#EMAIL_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA').val(),
                        DT_PRAZO: $('#PRAZO_CADASTRAR_SOLICITACOES_GENERICA').val(),
                        ID_UNID_CAIXA_ENTRADA: $('#TRAMITAR_PARA_CADASTRAR_SOLICITACOES_GENERICA').val(),
                        DESTINO: $('#TRAMITAR_PARA_CADASTRAR_SOLICITACOES_GENERICA :selected').text(),
                        SOLICITACAO: $('#OCR_CADASTRAR_SOLICITACOES_GENERICA').val(),
//                        PRIORIDADE: $('#PRIORIDADE_CADASTRAR_SOLICITACOES_GENERICA').val(),
                        extras: extras,
                        DIGITAL_REFERENCIA: $('#DIGITAL_REFERENCIA_CADASTRAR_SOLICITACOES_GENERICA').val()
                    }, function(response) {
                        if (response.success === true) {
                            $('#ASSUNTO_CADASTRAR_SOLICITACOES_GENERICA').empty();
                            $('#ASSUNTO_COMPLEMENTAR_CADASTRAR_SOLICITACOES_GENERICA').val('');
                            $('#DATA_DOCUMENTO_CADASTRAR_SOLICITACOES_GENERICA').val('');
                            $('#NOME_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA').val('');
                            $('#CPF_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA').val('');
                            $('#EMAIL_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA').val('');
                            $('#PRAZO_CADASTRAR_SOLICITACOES_GENERICA').val('');
                            $('#TRAMITAR_PARA_CADASTRAR_SOLICITACOES_GENERICA').empty();
                            $('#OCR_CADASTRAR_SOLICITACOES_GENERICA').val('');
                            $('#PRIORIDADE_CADASTRAR_SOLICITACOES_GENERICA').attr('selectedIndex', 0);
                            ;
                            $('#div-form-cadastrar-solicitacoes-generica').dialog('close');
                        }
                        alert(response.message);
                        $('#progressbar').hide();
                    }, 'json');
                }
            }

            $(document).ready(function() {

                $('#PRIORIDADE_CADASTRAR_SOLICITACOES_GENERICA').combobox('modelos/combos/lista_prioridades.php');

                /*Filtro tramite*/
                $("#FILTRO_DESTINO_CADASTRAR_SOLICITACOES_GENERICA").autocompleteonline({
                    url: 'modelos/combos/lista_tramite.php',
                    idComboBox: 'TRAMITAR_PARA_CADASTRAR_SOLICITACOES_GENERICA'
                });

                $("#FILTRO_ASSUNTO_CADASTRAR_SOLICITACOES_GENERICA").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox: 'ASSUNTO_CADASTRAR_SOLICITACOES_GENERICA',
                    extraParams: {
                        action: 'documentos-assuntos'
                    }
                });

                $("#PRAZO_CADASTRAR_SOLICITACOES_GENERICA").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    numberOfMonths: 1,
                    onClose: function(selectedDate) {
                        $("#DATA_DOCUMENTO_CADASTRAR_SOLICITACOES_GENERICA").datepicker("option", "maxDate", selectedDate);
                    }
                });

                $("#DATA_DOCUMENTO_CADASTRAR_SOLICITACOES_GENERICA").datepicker({
                    defaultDate: new Date(),
                    changeMonth: true,
                    changeYear: true,
                    numberOfMonths: 1,
                    onClose: function(selectedDate) {
                        $("#PRAZO_CADASTRAR_SOLICITACOES_GENERICA").datepicker("option", "minDate", selectedDate);
                    }
                });

                $('#box-filtro-assunto-cadastrar-solicitacoes-generica').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 90,
                    close: function(event, ui) {
                        $("#FILTRO_ASSUNTO_CADASTRAR_SOLICITACOES_GENERICA").val('');
                    }
                });

                $('#div-form-cadastrar-solicitacoes-generica').dialog({
                    title: 'Nova Demanda',
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    width: 650,
                    autoHeight: true,
                    open: function(event, ui) {
                        // verifica se temos digital disponivel
                        $('#progressbar').show();
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
                            jquery_validar_campos_cadastrar_solicitacoes_generica();
                        },
                        Cancelar: function() {
                            $('#DIGITAL_REFERENCIA_CADASTRAR_SOLICITACOES_GENERICA').val('');
                            $('#ASSUNTO_CADASTRAR_SOLICITACOES_GENERICA').empty();
                            $('#ASSUNTO_COMPLEMENTAR_CADASTRAR_SOLICITACOES_GENERICA').val('');
                            $('#DATA_DOCUMENTO_CADASTRAR_SOLICITACOES_GENERICA').val('');
                            $('#NOME_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA').val('');
                            $('#CPF_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA').val('');
                            $('#EMAIL_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA').val('');
                            $('#PRAZO_CADASTRAR_SOLICITACOES_GENERICA').val('');
                            $('#TRAMITAR_PARA_CADASTRAR_SOLICITACOES_GENERICA').empty();
                            $('#OCR_CADASTRAR_SOLICITACOES_GENERICA').val('');
                            $('#PRIORIDADE_CADASTRAR_SOLICITACOES_GENERICA').attr('selectedIndex', 0);
                            $('#box-filtro-assunto-cadastrar-solicitacoes-generica').dialog('close');
                            $(this).dialog('close');
                        }
                    },
                    close: function(event, ui) {
                        $('#box-filtro-tramitar-para-cadastrar-solicitacoes-generica').dialog('close');
                        $('#box-filtro-assunto-cadastrar-solicitacoes-generica').dialog('close');
                        $('#DIGITAL_REFERENCIA_CADASTRAR_SOLICITACOES_GENERICA').val('');
                        $('#ASSUNTO_CADASTRAR_SOLICITACOES_GENERICA').empty();
                        $('#ASSUNTO_COMPLEMENTAR_CADASTRAR_SOLICITACOES_GENERICA').val('');
                        $('#DATA_DOCUMENTO_CADASTRAR_SOLICITACOES_GENERICA').val('');
                        $('#NOME_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA').val('');
                        $('#CPF_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA').val('');
                        $('#EMAIL_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA').val('');
                        $('#PRAZO_CADASTRAR_SOLICITACOES_GENERICA').val('');
                        $('#TRAMITAR_PARA_CADASTRAR_SOLICITACOES_GENERICA').empty();
                        $('#OCR_CADASTRAR_SOLICITACOES_GENERICA').val('');
                        $('#PRIORIDADE_CADASTRAR_SOLICITACOES_GENERICA').attr('selectedIndex', 0);
                        carregarListaPrazos();
                    }
                });

                $('#DIGITAL_REFERENCIA_CADASTRAR_SOLICITACOES_GENERICA').attr('disabled', 'disabled');
                /*Filtro Destino*/
                $('#box-filtro-tramitar-para-cadastrar-solicitacoes-generica').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 120,
                    open: function() {
                        $("#TRAMITAR_PARA_CADASTRAR_SOLICITACOES_GENERICA").empty();
                        boxDestinoOpen = true;
                    },
                    close: function() {
                        boxDestinoOpen = false;
                    }
                });
                /*Listeners*/
                $('#botao-filtro-assunto-cadastrar-solicitacoes-generica').click(function() {
                    $('#box-filtro-assunto-cadastrar-solicitacoes-generica').dialog('open');
                });
                $('#botao-filtro-tramitar-para-cadastrar-solicitacoes-generica').click(function() {
                    $('#box-filtro-tramitar-para-cadastrar-solicitacoes-generica').dialog('open');
                });
                $('#botao-documento-novo-cadastrar-solicitacoes-generica').click(function() {
                    $('#div-form-cadastrar-solicitacoes-generica').dialog('open');
                });

            });

        </script>

    </head>
    <body>
        <!--Formulario-->
        <div class="div-form-dialog" id="div-form-cadastrar-solicitacoes-generica">
            <form id="solicitacao_sic_generico" enctype="multipart/form-data" method="POST" action="modelos/generico/adicionar.php">
                <div class="row">
                    <label>DIGITAL REFERÊNCIA: </label>
                    <span class="conteudo">
                        <input type="text" id="DIGITAL_REFERENCIA_CADASTRAR_SOLICITACOES_GENERICA" />
                    </span>
                </div>
                <div class="row">
                    <label>*ASSUNTO:</label>
                    <span class="conteudo">
                        <select id="ASSUNTO_CADASTRAR_SOLICITACOES_GENERICA"></select>
                    </span>
                    <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-assunto-cadastrar-solicitacoes-generica" src="imagens/fam/application_edit.png">
                </div>



                <div class="row">
                    <label>*ASSUNTO COMPLEMENTAR:</label>
                    <span class="conteudo">
                        <input type="text" id="ASSUNTO_COMPLEMENTAR_CADASTRAR_SOLICITACOES_GENERICA" onKeyUp="DigitaLetraSeguro(this)">
                    </span>
                </div>

                <!--                <div class="row">
                                    <label>*PRIORIDADE:</label>
                                    <span class="conteudo">
                                        <select type="text" id="PRIORIDADE_CADASTRAR_SOLICITACOES_GENERICA"></select>
                                    </span>
                                </div>-->

                <div class="row-prioridades">
                    <div class="row">
                        <label class="label">*PRIORIDADE(S):</label>
                        <span class="conteudo">
                            <select class="FUNDOCAIXA1" id="LISTA_PRIORIDADE"></select>
                        </span>
                        <img title="Adicionar" class="botao-auxiliar-fix-combobox" id="botao-adicionar-prioridade" src="imagens/fam/add.png">
                        <img title="Filtrar" class="botao-auxiliar-recuo-fix-combobox" id="botao-filtro-prioridade" src="imagens/fam/application_edit.png">
                    </div>
                    <div class="row" id="container-prioridades"></div>
                </div>

                <div class="row">
                    <label>*DATA DO DOCUMENTO:</label>
                    <span class="conteudo">
                        <input type="text" id="DATA_DOCUMENTO_CADASTRAR_SOLICITACOES_GENERICA" maxlength="10" readonly="true">
                    </span>
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
                    <label id="LABEL_CPF_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA">CPF INTERESSADO:</label>
                    <span class="conteudo">
                        <input type="text" maxlength="100" id="CPF_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA">
                    </span>
                </div>

                <div class="row">
                    <label>*NOME INTERESSADO:</label>
                    <span class="conteudo">
                        <input type="text" onKeyUp="DigitaLetraSeguro(this)" maxlength="100" id="NOME_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA">
                    </span>
                </div>

                <div class="row">
                    <label>EMAIL INTERESSADO:</label>
                    <span class="conteudo">
                        <input type="text" maxlength="100" id="EMAIL_INTERESSADO_CADASTRAR_SOLICITACOES_GENERICA">
                    </span>
                </div>

                <div class="row">
                    <label>*DATA DO PRAZO:</label>
                    <span class="conteudo">
                        <input type="text" id="PRAZO_CADASTRAR_SOLICITACOES_GENERICA" maxlength="10">
                    </span>
                </div>

                <div class="row">
                    <label>*TRAMITAR PARA:</label>
                    <span class="conteudo">
                        <select id="TRAMITAR_PARA_CADASTRAR_SOLICITACOES_GENERICA" class="FUNDOCAIXA1"></select>
                    </span>
                    <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-tramitar-para-cadastrar-solicitacoes-generica" src="imagens/fam/application_edit.png">
                </div>

                <div class="row">
                    <label style="display: inline-block; vertical-align: top;">*CONTEÚDO DA SOLICITAÇÃO:</label>
                    <span class="conteudo">
                        <textarea cols="1" rows="1" id="OCR_CADASTRAR_SOLICITACOES_GENERICA" class="FUNDOCAIXA1" style="height: 140px; width: 64%"></textarea>
                    </span>
                </div>

            </form>
        </div>

        <div id="box-filtro-assunto-cadastrar-solicitacoes-generica" class="box-filtro">
            <div class="row">
                <label>ASSUNTO:</label>
                <div class="conteudo">
                    <input id="FILTRO_ASSUNTO_CADASTRAR_SOLICITACOES_GENERICA" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>

        <div id="box-filtro-tramitar-para-cadastrar-solicitacoes-generica" class="box-filtro">
            <div class="row">
                <label>DESTINO:</label>
                <div class="conteudo">
                    <input id="FILTRO_DESTINO_CADASTRAR_SOLICITACOES_GENERICA" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>

    </body>
</html>