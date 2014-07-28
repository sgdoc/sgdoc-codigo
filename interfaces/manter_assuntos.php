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

/**
 * @todo Realizar o bloqueio de tela para indicação de busca ajax
 */

include("function/auto_load_statics.php");

$controller = Controlador::getInstance();
?>

<html>
    <head>
        <style type="text/css">
            @import "plugins/datatable/media/css/demo_table_tabs.css";
            @import "plugins/datatables-1.9.1/extras/TableTools/media/css/TableTools_JUI.css";
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
            body{
                margin: 10px;
            }
            #botaoNovo{
                height: 16px;
                width: 16px;
                background-image: url('imagens/fam/add.png');
            }
        </style>

        <script type="text/javascript" src="javascripts/jquery.form.js"></script>
        <script type="text/javascript" src="plugins/datatables-1.9.1/media/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="plugins/datatables-1.9.1/extras/TableTools/media/js/ZeroClipboard.js"></script>
        <script type="text/javascript" src="plugins/datatables-1.9.1/extras/TableTools/media/js/TableTools.min.js"></script>
        <script type="text/javascript" src="javascripts/jquery.dataTables.filterOnEnter.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {

                $(".cabecalho-caixas").tabs();
                $("#tabs").tabs({
                    "show": function(event, ui) {
                        var table = $.fn.dataTable.fnTables(true);
                        if ( table.length > 0 ) {
                            $(table).dataTable().fnAdjustColumnSizing();
                        }
                    }
                });

            });

        </script>
        <title>Gerenciamento de Assuntos</title>
    </head>
    <body>
        <div class="cabecalho-caixas">
            <div class="logo-gerenciamento-etiquetas"></div>
            <div class="titulo-gerenciamento-etiquetas">Gerenciamento de Assuntos</div>
            <div class="menu-auxiliar">
                <?php Util::montaMenus($controller->botoes, array('class' => 'botao32')); ?>
            </div>
        </div>
        <div id="tabs">
            <?php Util::mostraAbas($controller->recurso->abas); ?>
        </div>

        <span class="style13 rodape"><?php print(__RODAPE__); ?></span>
    </body>
</html>
