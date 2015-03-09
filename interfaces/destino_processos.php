
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
                $("#FILTRO_UNIDADE_TRAMITE_PROCESSO").autocompleteonline({
                    url: 'modelos/combos/lista_tramite.php',
                    idComboBox: 'UNIDADE_TRAMITE_PROCESSO'
                });
                /*Mascaras*/
                $('#TELEFONE_TRAMITE_PROCESSO').focusout(function() {
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
                $('#CEP_TRAMITE_PROCESSO').mask('99999-999');

                $("#div-form-tramite-processos").hide();

                $('#tabs-tramite').tabs();
                $('#tipo_tramite_processo').val('I');

                $('#div-form-tramite-processos').dialog({
                    title: 'Trâmite de Documentos',
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    width: 600,
                    autoHeight: true,
                    buttons: {
                        Tramitar: function() {
                            validar_tramite_processos();
                        }
                    }
                });

                $('#link-interno-processos').click(function() {
                    $('#tipo_tramite_processo').val('I');
                });

                $('#link-externo-processos').click(function() {
                    $('#tipo_tramite_processo').val('E');
                });

                $('#botao_tramitar_processos').click(function() {
                    if (processos_selecionados(total_processos).length > 0) {
                        jquery_tramitar_processo(processos_selecionados(total_processos));
                    } else {
                        alert('Nenhum processos esta selecionado!');
                    }
                });

            });

            /*Funcoes*/
            function jquery_tramitar_processo(processo) {
                /*Carregar Digitais*/
                $('#PROCESSOS_TRAMITE_PROCESSO').val(processo);
                $('#PROCESSOS_TRAMITE_PROCESSO_FAKE1').val(processo);
                $('#PROCESSOS_TRAMITE_PROCESSO_FAKE2').val(processo);
                /*Carregar Combo Unidades*/
                $('#UNIDADE_TRAMITE_PROCESSO').combobox('modelos/combos/lista_tramite.php');
                /*Limpar Campos*/
                $('#DESTINATARIO_TRAMITE_PROCESSO').val(''), //destinatario
                        $('#LOCAL_TRAMITE_PROCESSO').val(''), //local
                        $('#ENDERECO_TRAMITE_PROCESSO').val(''), //endereco
                        $('#CEP_TRAMITE_PROCESSO').val(''), //cep
                        $('#TELEFONE_TRAMITE_PROCESSO').val(''), //telefone
                        $('#div-form-tramite-processos').dialog("open");
            }

            function validar_tramite_processos() {
                switch ( $('#tipo_tramite_processo').val() ) {
                    case 'I':
                        if ( 
                            $('#UNIDADE_TRAMITE_PROCESSO').val() && 
                            $('#UNIDADE_TRAMITE_PROCESSO').val() != null && 
                            $('#PROCESSOS_TRAMITE_PROCESSO').val() 
                            ) {
                            tramitar_processos();
                        } else {
                            alert('Foi encontrado um problema ao tentar validar as informações do trâmite.\nFeche o sistema de tente novamente!');
                        }
                        break;
                    case 'E':
                        if (    $('#DESTINATARIO_TRAMITE_PROCESSO').val() && 
                                $('#LOCAL_TRAMITE_PROCESSO').val() && 
                                $('#PROCESSOS_TRAMITE_PROCESSO').val() && 
                                $('#ENDERECO_TRAMITE_PROCESSO').val() ) {
                            tramitar_processos();
                        } else {
                            alert('Campo(s) obrigatório(s) em branco!');
                        }
                        break;
                    default:
                        alert('Foi encontrado um problema ao tentar validar as informações do trâmite.\nFeche o sistema de tente novamente!');
                        break;
                }
            }

            function tramitar_processos() {

                var c = confirm('Você tem certeza que deseja tramitar este(s) processo(s)?\n[' + $('#PROCESSOS_TRAMITE_PROCESSO').val() + ']\nObs:Todos Documentos Anexados também serão tramitados!');
                if (c) {
                    $("#progressbar").show();
                    $.post("modelos/processos/tramite.php",
                            {
                                acao: 'tramitar',
                                processos: $('#PROCESSOS_TRAMITE_PROCESSO').val(), //processos
                                tipo: $('#tipo_tramite_processo').val(), //tipo_tramite_processo
                                unidade: $('#UNIDADE_TRAMITE_PROCESSO').val(), //sigla_unidade
                                destinatario: $('#DESTINATARIO_TRAMITE_PROCESSO').val(), //destinatario
                                local: $('#LOCAL_TRAMITE_PROCESSO').val(), //local
                                endereco: $('#ENDERECO_TRAMITE_PROCESSO').val(), //endereco
                                cep: $('#CEP_TRAMITE_PROCESSO').val(), //cep
                                prioridade: $('#PRIORIDADE_TRAMITE_PROCESSO').val(), //prioridade
                                telefone: $('#TELEFONE_TRAMITE_PROCESSO').val()//telefone
                            },
                    function(data) {
                        $("#progressbar").hide();
                        if (data.success == 'true') {
                            $("#progressbar").hide();
                            oTableProcessos.fnDraw(false);
                            if (data.ticket == 'true') {
                                window.open('guia_processos.php');
                            } else {
                                alert(data.message);
                            }
                            $('#div-form-tramite-processos').dialog("close");
                        } else {
                            $("#progressbar").hide();
                            alert(data.error);
                        }
                    }, "json");
                }
            }


        </script>
    </head>
    <body>
        <!--Formulario-->
        <div class="div-form-dialog" id="div-form-tramite-processos">

            <input id="tipo_tramite_processo" readonly disabled type="hidden">
            <input id="PROCESSOS_TRAMITE_PROCESSO" readonly disabled type="hidden">

            <div id="tabs-tramite">
                <ul>
                    <li><a id="link-interno-processos" href="#aba-tramite-interno-processos">Trâmite Interno</a></li>
                    <li><a id="link-externo-processos" href="#aba-tramite-externo-processos">Trâmite Externo</a></li>
                </ul>
                <div id="aba-tramite-interno-processos">
                    <div class="row">
                        <label class="label">*PROCESSO(S):</label>
                        <span class="conteudo">
                            <input type="text" readonly id="PROCESSOS_TRAMITE_PROCESSO_FAKE2">
                        </span>
                    </div>

                    <div class="row">
                        <label class="label">*UNIDADE:</label>
                        <span class="conteudo">
                            <select class='FUNDOCAIXA1' id='UNIDADE_TRAMITE_PROCESSO'></select>
                        </span>
                    </div>

                    <div class="row">
                        <label class="label">FILTRO:</label>
                        <span class="conteudo">
                            <input type="text" class='FUNDOCAIXA1' id='FILTRO_UNIDADE_TRAMITE_PROCESSO'>
                        </span>
                    </div>
                </div>
                <div id="aba-tramite-externo-processos">

                    <div class="row">
                        <label class="label">*DOCUMENTO(S):</label>
                        <span class="conteudo">
                            <input type="text" readonly id="PROCESSOS_TRAMITE_PROCESSO_FAKE1">
                        </span>
                    </div>

                    <div class="row">
                        <label class="label">*DESTINATÁRIO:</label>
                        <span class="conteudo">
                            <input type="text" id="DESTINATARIO_TRAMITE_PROCESSO" maxlength="150" onKeyUp="DigitaLetraSeguro(this);">
                        </span>
                    </div>

                    <div class="row">
                        <label>*LOCAL:</label>
                        <span class="conteudo">
                            <input type="text" id="LOCAL_TRAMITE_PROCESSO" onKeyUp="DigitaLetraSeguro(this);">
                        </span>
                    </div>

                    <div>
                        <label>*ENDEREÇO:</label>
                        <input type="text" id="ENDERECO_TRAMITE_PROCESSO" maxlength="150" onKeyUp="DigitaLetraSeguro(this);">
                    </div>

                    <div>
                        <label>TELEFONE:</label>
                        <input type="text" id="TELEFONE_TRAMITE_PROCESSO" maxlength="14">
                    </div>

                    <div>
                        <label>CEP:</label>
                        <input type="text" id="CEP_TRAMITE_PROCESSO" maxlength="9">
                    </div>

                    <div>
                        <label>PRIORIDADE:</label>
                        <select id="PRIORIDADE_TRAMITE_PROCESSO">
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