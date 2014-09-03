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
include(__BASE_PATH__ . '/extensoes/pr_snas/1.2/interfaces/detalhar_documentos.php');
include(__BASE_PATH__ . '/interfaces/detalhar_processos.php');

require_once __BASE_PATH__ . '/extensoes/pr_snas/1.2/interfaces/dialog_responder_prazo.php';

$controller = Controlador::getInstance();
$auth = $controller->usuario;

$area = $auth->ID_UNIDADE;
?>

<html>
    <head>
        <script type="text/javascript" src="plugins/datatable/media/js/jquery.dataTables.js"></script>
        <style type="text/css" title="currentStyle">
            @import "plugins/datatable/media/css/demo_table_tabs.css";

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
    </body>
</html>
