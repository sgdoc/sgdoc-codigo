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
        <script type="text/javascript"  src="plugins/datatable/media/js/jquery.dataTables.js"></script>
        <style type="text/css" title="currentStyle">
            @import "plugins/datatable/media/css/demo_table_tabs.css";
            fieldset{
                border: 1px #9ac619 dotted;
                margin: 2px;
            }
            fieldset label{
                margin: 5px;
            }
            #tabela_regras_filter input[type=text]{
                width: 100px;
            }
            #tabela_regras_filter .sorting_1{
                width: 20px;
            }
        </style>

        <script type="text/javascript">

            $(document).ready(function() {
              
                /*Tabs*/
                $("#tabs").tabs();
                $(".cabecalho-caixas").tabs();

            });
        </script>
    </head>
    <body>
        <div class="cabecalho-caixas">
            <div class="logo-manter-unidades"></div>
            <div class="titulo-manter-unidades">Gerenciamento de Documentos fora de Caixas</div>
            <div class="menu-auxiliar">
                <?php Util::montaMenus($controller->botoes, array('class' => 'botao32')); ?>
            </div>
        </div>
        <div id="tabs">
            <?php Util::mostraAbas($controller->recurso->abas); ?>
        </div>
    </body>
</html>
