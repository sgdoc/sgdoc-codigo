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
$auth = $controller->usuario;

$area = $auth->ID_UNIDADE;
?>

<html>
    <head>
        <script type="text/javascript"  src="plugins/datatable/media/js/jquery.dataTables.js"></script>
        <style type="text/css">
            @import "plugins/datatable/media/css/demo_table_tabs.css";
            body{
                margin: 10px;
            }
        </style>
        <!--Otimizar performance-->
        <script type="text/javascript">
            var area = '<?php echo $area; ?>';

            $(document).ready(function() {

                $("#tabs").tabs();
                $(".cabecalho-caixas").tabs();

            });

        </script>
    </head>
    <body>
        <div class="cabecalho-caixas">
            <div class="logo-caixa-entrada"></div>
            <div class="titulo-caixa-entrada">Caixa de Entrada</div>
            <div class="menu-auxiliar">
                <?php Util::montaMenus($controller->botoes, array('class' => 'botao32')); ?>
            </div>
        </div>
        <div id="tabs">
            <?php Util::mostraAbas($controller->recurso->abas); ?>
        </div>
        <span class="style13 rodape"><?php echo __RODAPE__; ?></span>
    </body>
</html>
