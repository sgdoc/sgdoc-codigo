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

$objeto = DaoDocumento::getDocumento($_GET['digital']);
$controller->setContexto($objeto);
$controller->botoes = Util::getMenus($auth, $controller->recurso, $controller->acl);
foreach ($controller->recurso->dependencias as $arquivo) {
    include_once('interfaces/'.$arquivo);
}
?>

<html>
    <head>
        <script type="text/javascript" src="plugins/datatable/media/js/jquery.dataTables.js"></script>
        <style type="text/css">
            @import "plugins/datatable/media/css/demo_table_tabs.css";
            body{
                margin: 10px;
                background-color: #101c01;
                background-image: url('imagens/<?php print(__BACKGROUND__); ?>');
                background-position: bottom right;
                background-repeat: no-repeat;
            }

            #form-novo-despacho-documento textarea,#form-novo-despacho-documento input{
                width: 270px;
            }

        </style>

        <script type="text/javascript">

            var TabelaHistoricos = null;

            $(document).ready(function() {

                $("#tabs").tabs();
                $(".cabecalho-caixas").tabs();

                /*DataTable*/
                oTabelaHistoricos = $('#TabelaHistoricos').dataTable({
                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    bStateSave: false,
                    bPaginate: true,
                    bProcessing: true,
                    bServerSide: true,
                    bJQueryUI: true,
                    sPaginationType: "full_numbers",
                    aaSorting: [[ 3, "desc" ]],
                    "aoColumns": [
                        null,
                        null,
                        null,
                        { "asSorting": [ "desc" , "asc" ] },
                        { "asSorting": [ "desc" , "asc" ] },
                        null,
                        null,
                        null
                    ],
                    sAjaxSource: "modelos/historicos/listar_historicos_documentos_processos.php?usercase=historico-despachos-documentos&digital=<?php echo $_GET['digital'] ?>",
                    oLanguage: {
                        sProcessing: "Carregando...",
                        sLengthMenu: "_MENU_ por página",
                        sZeroRecords: "Nenhum despacho encontrado.",
                        sInfo: "_START_ a _END_ de _TOTAL_ despachos",
                        sInfoEmpty: "Nao foi possivel localizar despachos com o parametros informados!",
                        sInfoFiltered: "",
                        sInfoPostFix: "",
                        sSearch: "Pesquisar:",
                        oPaginate: {
                            sFirst: "Primeiro",
                            sPrevious: "Anterior",
                            sNext: "Próximo",
                            sLast: "Ultimo"
                        }
                    },
                    fnServerData: function ( sSource, aoData, fnCallback ) {
                        $.getJSON( sSource, aoData, function (json) {
                            fnCallback(json);
                        });
                    },
                    fnRowCallback: function( nRow, aData, iDisplayIndex ) {
                        /* Contador */
                        $('td:eq(0)', nRow).html(iDisplayIndex+1);
                        /* Complemento Em Branco */
                        if(aData[6]==''){
                            $('td:eq(6)', nRow).html('Em Branco');
                        }
                        $('td:eq(3)', nRow).html(convertDateToString(aData[3]));
                        $('td:eq(4)', nRow).html(convertDateToString(aData[4]));
                        
                        /*Carregar digital no dialogo de novo despacho*/
                        $('#FAKE_DIGITAL_DESPACHO_DOCUMENTO').val(aData[1]);
                        $('#DIGITAL_DESPACHO_DOCUMENTO').val(aData[1]);
                       
                        /*Retorna a linha modificada*/
                        return nRow;
                    }
                });
            });
        </script>

        <title>Despachos</title>
    </head>
    <body>
        <div class="cabecalho-caixas">
            <div class="logo-historico-despachos"></div>
            <div class="titulo-historico">Despachos - <?php echo $_GET['digital'] ?></div>
            <div class="menu-auxiliar">
                <?php Util::montaMenus($controller->botoes, array('class' => 'botao32')); ?>
            </div>
        </div>
        <div id="tabs">
            <table class="display" border="0" id="TabelaHistoricos">
                <thead>
                    <tr>
                        <th class="style13 column-checkbox">#</th>
                        <th class="style13 column-interessado">Usuario</th>
                        <th class="style13 column-digital">Unidade</th>
                        <th class="style13 column-data">Data Cadastro</th>
                        <th class="style13 column-data">Data Despacho</th>
                        <th class="style13 column-movimentacao">Despacho</th>
                        <th class="style13 column-interessado">Complemento</th>
                        <th class="style13 column-interessado">Assinatura</th>
                    </tr>
                </thead>
            </table>
        </div>
    </body>
</html>
