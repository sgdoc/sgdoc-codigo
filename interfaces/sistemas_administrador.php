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

$controller = Controlador::getInstance();
$auth = $controller->usuario;
?>
<html>
    <head>
        <title>Ferramentas de Administração</title>
        <script type="text/javascript">
            $(document).ready(function() {
                var items = $('#menus img').length;
                var largura = (items * 48);
                $('#central').width(largura);
            });
        </script>
        <style type="text/css">
            #central {
                padding: 10px;
                min-width: 292px;
                max-width: 850px;
                height: 190px;
                margin: 200px auto auto;
                overflow: hidden;
            }
            #central label {
                font-size: 24px;
                font-family: Tahoma;
                font-weight: bold;
                float: right;
            }
            #label-pesquisar{
                margin: auto auto;
                width: 296px;
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
        <p class="style13" valign="top" align="left"><?php print "Olá, {$auth->NOME} - {$auth->DIRETORIA}"; ?></p>
        <div id="MENU_PRINCIPAL">
            <div id="central" class ="ui-widget-content ui-corner-all">
                <div id="label-pesquisar">
                    <img src="imagens/ferramentas_administrador.png" class="botao48" />
                    <label class="menu-principal">Menu Administração</label>
                </div>
                <div id="menus">
                    <?php Util::montaMenus($controller->botoes); ?>
                </div>
            </div>
        </div>
        <span class="style13 rodape"><?php echo __RODAPE__; ?></span>
    </body>
</html>