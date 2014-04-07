
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
                /*Filtro tramite*/
                $("#FILTRO_UNIDADE_TRAMITE_DOCUMENTO").autocompleteonline({
                    url: 'modelos/combos/lista_tramite.php',
                    idComboBox: 'UNIDADE_TRAMITE_DOCUMENTO'
                });

                /*Mascaras*/
                $('#TELEFONE_TRAMITE_DOCUMENTO').focusout(function() {
                    var phone, element;
                    element = $(this);
                    element.unmask();
                    phone = element.val().replace(/\D/g, '');
                    if (phone.length > 10) {
                        element.mask("(99) 99999-999?9");
                    } else {
                        element.mask("(99) 9999-9999?9");
                    }
                }).trigger('focusout');
                $('#CEP_TRAMITE_DOCUMENTO').mask('99999-999');

                $("#div-form-tramite-documentos").hide();

                $('#tabs-tramite').tabs();
                $('#tipo_tramite_documento').val('I');

                $('#div-form-tramite-documentos').dialog({
                    title: 'Tramite de Documentos',
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    width: 600,
                    autoHeight: true,
                    buttons: {
                        Tramitar: function() {
                            validar_tramite_documentos();
                        }
                    }
                });

                $('#link-interno').click(function() {
                    $('#tipo_tramite_documento').val('I');
                });

                $('#link-externo').click(function() {
                    $('#tipo_tramite_documento').val('E');
                });

                $('#botao-tramitar-documentos').click(function() {
                    if (digital_selecionados(total_documentos).length > 0) {
                        jquery_tramitar_documento(digital_selecionados(total_documentos));
                    } else {
                        alert('Nenhum documentos esta selecionado!');
                    }
                });

            });

            /*Funcoes*/
            function jquery_tramitar_documento(digital) {
                /*Carregar Digitais*/
                $('#DIGITAIS_TRAMITE_DOCUMENTO').val(digital);
                $('#DIGITAIS_TRAMITE_DOCUMENTO_FAKE1').val(digital);
                $('#DIGITAIS_TRAMITE_DOCUMENTO_FAKE2').val(digital);
                /*Carregar Combo Unidades*/
                $('#UNIDADE_TRAMITE_DOCUMENTO').combobox('modelos/combos/lista_tramite.php');
                /*Limpar Campos*/
                $('#DESTINATARIO_TRAMITE_DOCUMENTO').val(''), //destinatario
                        $('#LOCAL_TRAMITE_DOCUMENTO').val(''), //local
                        $('#ENDERECO_TRAMITE_DOCUMENTO').val(''), //endereco
                        $('#CEP_TRAMITE_DOCUMENTO').val(''), //cep
                        $('#TELEFONE_TRAMITE_DOCUMENTO').val(''), //telefone
                        $('#div-form-tramite-documentos').dialog("open");
            }

            function validar_tramite_documentos() {

                switch ($('#tipo_tramite_documento').val()) {
                    case 'I':
                        if ($('#UNIDADE_TRAMITE_DOCUMENTO').val() && $('#DIGITAIS_TRAMITE_DOCUMENTO').val()) {
                            tramitar_documentos();
                        } else {
                            alert('Foi encontrado um problema ao tentar validar as informações do trâmite.\nFeche o sistema de tente novamente!');
                        }
                        break;
                    case 'E':
                        if ($('#DESTINATARIO_TRAMITE_DOCUMENTO').val() && $('#LOCAL_TRAMITE_DOCUMENTO').val() && $('#DIGITAIS_TRAMITE_DOCUMENTO').val() && $('#ENDERECO_TRAMITE_DOCUMENTO').val()) {
                            tramitar_documentos();
                        } else {
                            alert('Campo(s) obrigatório(s) em branco!');
                        }
                        break;
                    default:
                        alert('Foi encontrado um problema ao tentar validar as informações do trâmite.\nFeche o sistema de tente novamente!');
                        break;
                }
            }

            function  tramitar_documentos() {
                try {
                    var c = confirm('Você tem certeza que deseja tramitar este(s) documento(s)?\n[' + $('#DIGITAIS_TRAMITE_DOCUMENTO').val() + ']\nObs:Todos documentos vinculados tambem serao tramitados!');
                    if (c) {
                        $("#progressbar").show();
                        $.post("modelos/documentos/tramite.php",
                                {
                                    acao: 'tramitar',
                                    digitais: $('#DIGITAIS_TRAMITE_DOCUMENTO').val(), //digitais
                                    tipo: $('#tipo_tramite_documento').val(), //tipo_tramite_documento
                                    unidade: $('#UNIDADE_TRAMITE_DOCUMENTO').val(), //sigla_unidade
                                    destinatario: $('#DESTINATARIO_TRAMITE_DOCUMENTO').val(), //destinatario
                                    local: $('#LOCAL_TRAMITE_DOCUMENTO').val(), //local
                                    endereco: $('#ENDERECO_TRAMITE_DOCUMENTO').val(), //endereco
                                    cep: $('#CEP_TRAMITE_DOCUMENTO').val(), //cep
                                    prioridade: $('#PRIORIDADE_TRAMITE_DOCUMENTO').val(), //prioridade
                                    telefone: $('#TELEFONE_TRAMITE_DOCUMENTO').val()//telefone
                                },
                        function(data) {
                            $("#progressbar").hide();
                            if (data.success == 'true') {
                                oTableDocumentos.fnDraw(false);
                                if (data.ticket == 'true') {
                                    window.open('guia_documentos.php');
                                } else {
                                    alert(data.message);
                                }
                                $('#div-form-tramite-documentos').dialog("close");
                                $("#progressbar").hide();
                            } else {
                                alert(data.error);
                                $("#progressbar").hide();
                            }
                        }, "json");
                    }
                } catch (e) {
                    $("#progressbar").hide();
                    alert('Ocorreu um erro:\n[' + e + ']');
                }
            }


        </script>
    </head>
    <body>
        <!--Formulario-->
        <div class="div-form-dialog" id="div-form-tramite-documentos">

            <input id="tipo_tramite_documento" readonly disabled type="hidden">
            <input id="DIGITAIS_TRAMITE_DOCUMENTO" readonly disabled type="hidden">

            <div id="tabs-tramite">
                <ul>
                    <li><a id="link-interno" href="#aba-tramite-interno">Trâmite Interno</a></li>
                    <li><a id="link-externo" href="#aba-tramite-externo">Trâmite Externo</a></li>
                </ul>
                <div id="aba-tramite-interno">
                    <div class="row">
                        <label class="label">*DOCUMENTO(S):</label>
                        <span class="conteudo">
                            <input type="text" readonly id="DIGITAIS_TRAMITE_DOCUMENTO_FAKE2">
                        </span>
                    </div>

                    <div class="row">
                        <label class="label">*UNIDADE:</label>
                        <span class="conteudo">
                            <select class='FUNDOCAIXA1' id='UNIDADE_TRAMITE_DOCUMENTO'></select>
                        </span>
                    </div>

                    <div class="row">
                        <label class="label">FILTRO:</label>
                        <span class="conteudo">
                            <input type="text" class='FUNDOCAIXA1' id='FILTRO_UNIDADE_TRAMITE_DOCUMENTO'>
                        </span>
                    </div>
                </div>
                <div id="aba-tramite-externo">

                    <div class="row">
                        <label class="label">*DOCUMENTO(S):</label>
                        <span class="conteudo">
                            <input type="text" readonly id="DIGITAIS_TRAMITE_DOCUMENTO_FAKE1">
                        </span>
                    </div>

                    <div class="row">
                        <label class="label">*DESTINATÁRIO:</label>
                        <span class="conteudo">
                            <input type="text" id="DESTINATARIO_TRAMITE_DOCUMENTO" maxlength="150" onKeyUp="DigitaLetraSeguro(this);">
                        </span>
                    </div>

                    <div class="row">
                        <label>*LOCAL:</label>
                        <span class="conteudo">
                            <input type="text" id="LOCAL_TRAMITE_DOCUMENTO" onKeyUp="DigitaLetraSeguro(this);">
                        </span>
                    </div>

                    <div>
                        <label>*ENDEREÇO:</label>
                        <input type="text" id="ENDERECO_TRAMITE_DOCUMENTO" maxlength="150" onKeyUp="DigitaLetraSeguro(this);">
                    </div>

                    <div>
                        <label>TELEFONE:</label>
                        <input type="text" id="TELEFONE_TRAMITE_DOCUMENTO" maxlength="14">
                    </div>

                    <div>
                        <label>CEP:</label>
                        <input type="text" id="CEP_TRAMITE_DOCUMENTO" maxlength="9">
                    </div>

                    <div>
                        <label>PRIORIDADE:</label>
                        <select id="PRIORIDADE_TRAMITE_DOCUMENTO">
                            <option selected value="Normal">Normal</option>
                            <option value="Urgente">Urgente</option>
                            <option value="Urgentissimo">Urgentissimo</option>
                        </select>
                    </div>

                </div>
            </div>

        </div>

    </body>
</html>