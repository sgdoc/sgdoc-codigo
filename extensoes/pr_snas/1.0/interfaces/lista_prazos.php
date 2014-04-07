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

include("function/auto_load_statics.php");
include(__BASE_PATH__ . '/interfaces/verificador_caixas.php');
include(__BASE_PATH__ . '/extensoes/pr_snas/1.0/interfaces/detalhar_documentos.php');
include(__BASE_PATH__ . '/interfaces/detalhar_processos.php');

$controller = Controlador::getInstance();
$auth = $controller->usuario;

$area = $auth->ID_UNIDADE;
?>

<html>
    <head>
        <script type="text/javascript" src="plugins/datatable/media/js/jquery.dataTables.js"></script>
        <style type="text/css" title="currentStyle">
            @import "plugins/datatable/media/css/demo_table_tabs.css";

            fieldset{
                border: 1px #9ac619 dotted;
                margin: 2px;
            }
            fieldset label{
                margin: 5px;
            }

            .vermelho{
                font-size: 12px;
                color: red;
                background-color: #ffc4c4;
                cursor: pointer;
            }

            .verde{
                font-size: 12px;
                color: green;
                background-color: #d5ffd5;
                cursor: pointer;
                color: #000000;
            }

            .laranja{
                font-size: 12px;
                background-color: #ffe45c;
                cursor: pointer;
                color: #000000;
            }

            .branco{
                font-size: 12px;
                background-color: #ffffff;
                cursor: pointer;
                color: #000000;
            }
        </style>

        <script type="text/javascript">
            var area = '<?php echo $area; ?>';

            $.fx.speeds._default = 1000;

            var responder = function(v) {
                $('#dialog').dialog('open');
                $('#id_prazo').val(v);
                return false;
            }

            var detalharSolicitacao = function(id) {
                $.post("modelos/prazos/prazos.php", {
                    acao: 'carregar-solicitacao',
                    id: id
                },
                function(data) {
                    try {
                        if (data) {
                            /*Limpar Campos*/
                            $('#resultado').html('<p>' + data.solicitacao + '</p>');
                            /*Alert*/
                            $('#dialog-detalha').dialog('open');
                        }
                    } catch (e) {
                        alert('Ocorreu um erro ao tentar abrir detalhes da solicitacao!\n[' + e + ']');
                    }
                }, "json");

                return false;
            }

            var detalharResposta = function(id) {
                $.post("modelos/prazos/prazos.php", {
                    acao: 'carregar-resposta',
                    id: id
                },
                function(data) {
                    try {
                        if (data) {
                            /*Limpar Campos*/
                            $('#resultado').html('<p>' + data.resposta + '</p>');
                            /*Alert*/
                            $('#dialog-detalha').dialog('open');
                        } else {
                            alert(data);
                        }
                    } catch (e) {
                        alert('Ocorreu um erro ao tentar abrir detalhes da resposta!\n[' + e + ']');
                    }
                }, "json");

                return false;
            }

            var notificar = function(v) {
                var c = confirm('Você tem certeza que deseja enviar uma notificação para o email do destinatário?');
                if (c) {
                    $.post('../modelos/prazos/notificar_destinatario.php', {
                        id: v
                    }, function(response) {
                        if (response) {
                            if (response.error) {
                                alert(response.error);
                            } else {
                                alert('Sua notificação foi enviada com sucesso para o email: ' + response.email);
                            }
                        } else {
                            alert('Ocorreu um erro ao tentar enviar a notificação ao destinatário!');
                        }
                    }, 'json');
                }
            }

            $(document).ready(function() {
                /*Abas*/
                $(".cabecalho-caixas").tabs();
                $("#tabs").tabs();

                $('#fieldsetNumProcDig').hide();
                $('#preload_numero').hide();

                function listarProcDig(tipo) {
                    $.getJSON("modelos/prazos/listar_num_proc_dig_trab.php", {
                        'request': tipo
                    }, function(data) {
                        $('#numero').empty();
                        if (data) {
                            $.each(data.numeros, function(i) {
                                var newOption = $('<option>');
                                newOption.html(data.numeros[i].numero);
                                newOption.attr('value', data.numeros[i].numero);
                                $('#numero').append(newOption);
                            });
                        } else {
                            alert('A área de trabalho do seu setor está vazia!');
                        }
                    });
                    $('#preload_numero').fadeOut();
                    $('#fieldsetNumProcDig').show();
                }

                function salvarResposta() {
                    $('#progressbar').show();
                    if (confirm('Você tem certeza que deseja enviar esta resposta agora?\nOBS: Este procedimento não poderá ser desfeito.')) {
                        $.post("modelos/prazos/prazos.php", {
                            acao: 'salvar-resposta',
                            sq_prazo: $('#id_prazo').val(),
                            nu_proc_dig_res: $('#numero').val(),
                            tx_resposta: $('#resposta').val()
                        },
                        function(data) {
                            try {
                                if (data.success == 'true') {
                                    oTableRecebidos.fnDraw(false);
                                    oTableRecebidosSetor.fnDraw(false);
                                    $('#id_prazo').val('');
                                    $('#tipo').val('N');
                                    $('#fieldsetNumProcDig').hide();
                                    $('#numero').empty();
                                    $('#resposta').val('');
                                    $('#dialog').dialog("close");
                                    alert('Prazo Respondido com Sucesso!');
                                    $('#progressbar').hide();
                                } else {
                                    $('#progressbar').hide();
                                    alert(data.error);
                                }
                            } catch (e) {
                                $('#progressbar').hide();
                                alert('Ocorreu um erro ao responder um prazo:\n[' + e + ']');
                            }
                        }, "json");
                    } else {
                        $('#progressbar').hide();
                    }
                }

                $('#tipo').change(function(v) {
                    var o = $('#tipo').val();
                    if (o == 'D') {
                        $('#numero').removeAttr('disabled');
                        $('#preload_numero').show();
                        listarProcDig('D');
                    } else if (o == 'P') {
                        $('#numero').removeAttr('disabled');
                        $('#preload_numero').show();
                        listarProcDig('P');
                    } else if (o == 'N') {
                        $('#fieldsetNumProcDig').hide();
                        $('#numero').empty();
                        $('#numero').setAttribute('disabled', 'disabled');
                    }
                });

                // Dialog
                $('#dialog').dialog({
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    width: 500,
                    autoHeight: true,
                    buttons: {
                        Enviar: function() {
                            if ($('#id_prazo').val() != '' &&
                                    $('#resposta').val() != '' &&
                                    ($('#tipo').val() == 'N' || $('#numero').val() != ''))
                            {
                                salvarResposta();
                            } else {
                                alert('Um ou mais campos obrigatorios estao em branco');
                            }
                        },
                        Cancelar: function() {
                            $(this).dialog("close");
                        }
                    }
                });

                $('#dialog-detalha').dialog({
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 800,
                    height: 'auto',
                    maxHeight: 600,
                    buttons: {
                        Fechar: function() {
                            $(this).dialog("close");
                        }
                    }
                });

                // Datepicker
                $('#datepicker').datepicker({
                    inline: true
                });

                //hover states on the static widgets
                $('#dialog_link, ul#icons li').hover(function() {
                    $(this).addClass('ui-state-hover');
                },
                        function() {
                            $(this).removeClass('ui-state-hover');
                        }
                );

            });
        </script>
    </head>

    <body>
        <div class="cabecalho-caixas">
            <div class="logo-controle-prazos"></div>
            <div class="titulo-controle-prazos">Controle de Prazos</div>
            <div class="menu-auxiliar">
                <?php Util::montaMenus($controller->botoes, array('class' => 'botao32')); ?>
            </div>
        </div>

        <div id="tabs">
            <?php Util::mostraAbas($controller->recurso->abas); ?>
        </div>

        <div id="dialog-detalha" title="Detalhamento">
            <div class="detalha-prazo" id="resultado" style="max-height: 680px; overflow-y: auto;"></div>
        </div>

        <div id="dialog" title="Responder ao Prazo">
            <fieldset>
                <fieldset>
                    <label for="tipo">Resposta via:</label>
                    <select id="tipo" class="ui-corner-all FUNDOCAIXA1">
                        <option value="N" selected="selected">Nenhum</option>
                        <option value="D">Digital</option>
                        <option value="P">Processo</option>
                    </select>
                </fieldset>
                <fieldset id="fieldsetNumProcDig">
                    <label for="numero">Numero:</label>
                    <select disabled id="numero" class="ui-corner-all FUNDOCAIXA1"></select>
                    <img alt="carregando..."  id="preload_numero" src="imagens/spinner_auto_complete.gif">
                </fieldset>
                <fieldset>
                    <label for="resposta">*Conteudo da Resposta:</label>
                    <textarea onkeyup="DigitaLetraSeguro(this)" cols="1" rows="1"  id="resposta" class="ui-corner-all FUNDOCAIXA1" style="height: 200px; width: 100%;"></textarea>
                    <input type="hidden" id="id_prazo"/>
                </fieldset>
            </fieldset>
        </div>
    </body>
</html>
