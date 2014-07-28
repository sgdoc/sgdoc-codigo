<?php
/**
 * Copyright 2011 ICMBio
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
include("verificador_caixas.php");

$controller = Controlador::getInstance();
$auth = $controller->usuario;

/**
 * Integracao SGDOC-e
 */
if (Config::factory()->getParam('extra.integration.sgdoc-e') == 'true' && AclFactory::checaPermissao(
                Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999999999)
        )) {
    IntegrationSGDOCE::factory()->load();
}
?>

<html>
    <head>
        <style type="text/css">
            @import "plugins/datatable/media/css/demo_table_tabs.css";
            body{
                margin: 10px;
            }

            .progress {
                border: 1px solid #DDDDDD;
                border-radius: 3px 3px 3px 3px;
                padding: 1px;
                position: relative;
                width: 500px;
                margin: 10px auto 0 !important;
            }

            .bar {
                background-color: #B4F5B4;
                border-radius: 3px 3px 3px 3px;
                height: 20px;
                width: 0;
            }

            .percent {
                display: inline-block;
                left: 48%;
                position: absolute;
                top: 3px;
            }
        </style>

        <?php
        if (Config::factory()->getParam('extra.integration.sgdoc-e') == 'true' && AclFactory::checaPermissao(
                        Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999999999)
                )):
            ?>
            <script type="text/javascript" src="javascripts/integration.sgdoc-e.js"></script>
        <?php endif; ?>

        <script type="text/javascript">
            $(document).ready(function() {
                var items = $('#menus img').length;
                var largura = (items * 48);
                $('#central').width(largura);
                sistemas.init();
            });
            var sistemas = {
                init: function()
                {
                    this.qualificarSuporte();
                },
                qualificarSuporte: function()
                {
<?php if (Suporte::countChamadoQualificar($auth->ID) != 0): ?>
                        var divQualificaChamados = document.createElement('div');
                        var textQualificaChamados = document.createTextNode('Atenção você possui chamados finalizados sem uma qualificação!\nDeseja qualificá-los agora?');
                        divQualificaChamados.setAttribute('id', 'errorLogin');
                        divQualificaChamados.appendChild(textQualificaChamados);
                        $(divQualificaChamados).dialog({
                            autoOpen: true,
                            height: 140,
                            resizable: false,
                            modal: true,
                            buttons: {
                                'Sim': function() {
                                    document.location.href = 'qualificar_demandas.php';
                                    return false;
                                },
                                'Não': function() {
                                    $(this).dialog("close");
                                }
                            }
                        });
<?php endif; ?>
                }
            }
        </script>
        <style type="text/css" >
            #central {
                padding: 10px;
                min-width: 200px;
                max-width: 950px;
                height: 180px;
                margin: 200px auto auto;
                overflow: hidden;
            }
            label.menu-principal {
                font-size: 24px;
                font-family: Tahoma;
                font-weight: bold;
                float: right;
            }
            #label-pesquisar{
                margin: auto auto;
                width: 230px;
                line-height: 45px;
            }
            #menus {
                margin-top: 50px;
                margin-left: auto;
                margin-right: auto;
            }
            .dock-item {
                cursor: pointer;
            }

        </style>
    </head>

    <body>
        <div id="MENU_PRINCIPAL">
            <div id="central" class ="ui-widget-content ui-corner-all">
                <div id="label-pesquisar">
                    <img  src="imagens/menu_principal.png" class="botao48">
                    <label class="menu-principal">Menu Principal</label>
                </div>
                <div id="menus">
                    <?php Util::montaMenus($controller->botoes); ?>
                </div>
            </div>
        </div>
        <span class="style13 rodape"><?php print(__RODAPE__); ?></span>
    </body>
</html>