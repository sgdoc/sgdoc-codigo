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
 * Manter o fundo branco...
 */
$DONT_RENDER_BACKGROUND = TRUE;

include("function/auto_load_statics.php");
include("interfaces/detalhar_documentos.php");


$vinculacao = new Vinculacao();
$urlManager = __APPMODELS__ . "arvores/listar_todos_vinculos_documentos.php";
$numero_processo = $_GET['numero_processo'];
$arvore = new Arvore();

$elementos = $arvore->getPecasProcesso($numero_processo, $urlManager);
?>

<script type="text/javascript" src="plugins/tree/js/jquery.simple.tree.js"></script>
<script type="text/javascript" src="plugins/tree/js/langManager.js"></script>
<script type="text/javascript" src="plugins/tree/js/treeOperations.js"></script>
<script type="text/javascript">

    /*Inicializando controladoar de idiomas*/
    var langManager = new languageManager();

    /*Objeto Operation Tree*/
    var treeOperations = null;

    $(document).ready(function() {
        treeOperations = new TreeOperations($('#tree-apensos-processos'), '<?php print($urlManager); ?>');

        /*Arvore */
        $('#tree-apensos-processos').simpleTree({
            animate: true,
            autoclose: false,
            restoreTreeState: false,
            afterClick: function(node) {
                if ($('span:first', node).text().length == 7) {
                    jquery_detalhar_documento($('span:first', node).text());
                }
            },
            afterDblClick: function(node) {
            },
            afterMove: function(destination, source, pos) {
            },
            afterAjax: function(node) {
                /**
                 *Documento ausente
                 **/
                if (node.attr('stAusente') == 'true') {
                    $('#' + node.attr('idElemento')).attr('class', 'folder-open-ausente');
                    $('#' + node.attr('idElemento')).attr('title', 'Este documento nao esta na sua área de trabalho');
                    if (node.html().length == 1) {
                        $('#' + node.attr('idElemento')).attr('class', 'folder-close-ausente');
                    }
                }

            },
            afterContextMenu: function(element, event) {
                var className = element.attr('class');
                if (className.indexOf('root') >= 0) {
                    $('#menu-tree-apensos-processos .edit, #menu-tree-apensos-processos .delete').hide();
                    $('#menu-tree-apensos-processos .expandAll, #menu-tree-apensos-processos .collapseAll').show();
                }
                else {
                    $('#menu-tree-apensos-processos .expandAll, #menu-tree-apensos-processos .collapseAll').hide();
                }
                $('#menu-tree-apensos-processos').css('top', event.pageY).css('left', event.pageX).show();

                $('*').click(function() {
                    $('#menu-tree-apensos-processos').hide();
                });
            }

        });

        /*Carregar todos os vinculos*/
        treeOperations.expandAll($('#tree-apensos-processos > .root > ul'));

        /*Menus*/
        $('#menu-tree-apensos-processos .expandAll').click(function() {
            treeOperations.expandAll($('#tree-apensos-processos > .root > ul'));
        });
        $('#menu-tree-apensos-processos .collapseAll').click(function() {
            treeOperations.collapseAll();
        });

        $('#menu-tree-apensos-processos .expandAll').append(langManager.expandAll);
        $('#menu-tree-apensos-processos .collapseAll').append(langManager.collapseAll);

    });
</script>

<html>
    <head>
        <title>Apensos</title>
        <style type="text/css">
            body{
                margin: 0px;
                padding: 0px;
            }
            .ausente{
                color: red;
            }
        </style>
        <link rel="stylesheet" type="text/css" href="plugins/tree/css/style.css" />
    </head>
    <body>
        <div class="contextMenu" id="menu-tree-apensos-processos">
            <li class="expandAll"><img alt="" src="plugins/tree/css/images/expand.png"/></li>
            <li class="collapseAll"><img alt="" src="plugins/tree/css/images/collapse.png"/></li>
        </div>
        <ul id="tree-apensos-processos" class="arvoreDocumentos">
            <li class="<?php print(Processo::validarProcessoAreaDeTrabalho($numero_processo) ? 'root' : 'root root-ausente') ?>" id='<?php print($arvore->getRootId()); ?>'><span title="Processo mais relevante da arvore"><?php print($numero_processo); ?></span>
                <ul><?php print($elementos); ?></ul>
            </li>
        </ul>
    </body>
</html>

<style type="text/css">

    .ui-progressbar-value { 
        background-image: url('imagens/jqueryui_progressbar.gif'); 
    }
    #progressbar-default{
        width: 300px;
        height: 22px; 
    }
    #progressbar #progressbar-default-container{
        position: absolute;
        margin: 20px;
        right: 0px;
        bottom: 0px;
    }
    #progressbar{
        display: none;
        position: fixed;
        top: 0px;
        bottom: 0px;
        right: 0px;
        left: 0px;   
        z-index: 999999;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.9);
        border: 0px solid white;
    }

</style>

<div id="visualizador_popup" style="display:none">
    <input type="hidden" id="">
    <input class="exitButtonPopup" id="btnClosePopup" type="image" src="imagens/cancelar.png" title="Fechar" onclick="stop_visualizador_processo();">
    <iframe name="frame_visualizador_popup" id="frame_visualizador_popup"></iframe>
</div>