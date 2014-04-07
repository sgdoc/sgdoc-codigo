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
include("verificador_caixas.php");

$controller = Controlador::getInstance();
?>
<html>
    <head>
        <title>Menu de pesquisas</title>
        <script type="text/javascript" charset="utf-8">
            $(document).ready(function() {
                $('#botao-voltar-form-pesquisar').click(function(){
                    window.location = 'sistemas.php';
                });
            });
        </script>
        <style type="text/css">
            #central {
                padding: 10px;
                min-width: 200px;
                max-width: 850px;
                height: 180px;
                margin: 200px auto auto;
                overflow: hidden;
                width: 200px;
            }
            #central label {
                font-size: 24px;
                font-family: Tahoma;
                font-weight: bold;
                float: right;
            }
            #label-pesquisar{
                margin: auto auto;
                width: 170px;
                line-height: 45px;
            }
            #menus {
                margin-top: 30px;
            }
            .dock-item {
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <div id="central" class ="ui-widget-content ui-corner-all">
            <div id="label-pesquisar">
                <img alt=""  src="imagens/pesquisar-2.png"/>
                <label>Pesquisar</label>
            </div>
            <div id="menus">
                <?php Util::montaMenus($controller->botoes); ?>
            </div>
        </div>
        <span class="style13 rodape"><?php echo __RODAPE__; ?></span>
    </body>
</html>