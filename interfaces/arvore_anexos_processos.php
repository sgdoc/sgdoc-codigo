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
include("interfaces/detalhar_processos.php");

$urlManager = __APPMODELS__ . "arvores/listar_anexos_processos.php";
$numero_processo = $_GET['numero_processo'];
$arvore = new Arvore();


$vinculacao = new Vinculacao();
$root = $vinculacao->setProcessoRoot($numero_processo, 1/* Anexos */);
$arvore->setRootId($root);
$elementos = $arvore->getVinculacaoProcesso($root, $urlManager, 1/* Anexos */);
?>

<html>
    <head>
        <title>Anexos/Apensos</title>
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
        <script type="text/javascript" src="plugins/tree/js/jquery.simple.tree.js"></script>
        <script type="text/javascript" src="plugins/tree/js/langManager.js"></script>
        <script type="text/javascript" src="plugins/tree/js/treeOperations.js"></script>
        <script type="text/javascript">

            /*Inicializando controladoar de idiomas*/
            var langManager = new languageManager();

            /*Objeto Operation Tree*/
            var treeOperations = null;

            $(document).ready(function() {
                treeOperations = new TreeOperations($('#tree-anexos-processos'), '<?php print($urlManager); ?>');

                /*Arvore */
                $('#tree-anexos-processos').simpleTree({
                    animate: true,
                    autoclose: false,
                    restoreTreeState: false,
                    afterClick: function(node) {
                        jquery_detalhar_processo($('span:first', node).text());
                    },
                    afterDblClick: function(node) {
                        //jquery_detalhar_processo($('span:first',node).text());
                    },
                    afterMove: function(destination, source, pos) {
                    },
                    afterAjax: function(node) {

                        /*Processo Selecionado*/
                        if (node.attr('idElemento') == '<?php print(str_replace(array('.', '/', '-'), array('', '', ''), $numero_processo)); ?>') {
                            $('#' + node.attr('idElemento')).attr('class', 'folder-open-target');
                            $('#' + node.attr('idElemento')).attr('title', 'Processo selecionado');
                            if (node.html().length == 1) {
                                $('#' + node.attr('idElemento')).attr('class', 'folder-close-target');
                            }
                        }

                        /*Processo ausente*/
                        if (node.attr('stAusente') == 'true') {
                            $('#' + node.attr('idElemento')).attr('class', 'folder-open-ausente');
                            $('#' + node.attr('idElemento')).attr('title', 'Este processo nao esta na sua area de trabalho');
                            if (node.html().length == 1) {
                                $('#' + node.attr('idElemento')).attr('class', 'folder-close-ausente');
                            }
                        }
                    },
                    afterContextMenu: function(element, event) {
                        var className = element.attr('class');
                        if (className.indexOf('root') >= 0) {
                            $('#menu-tree-anexos-processos .edit, #menu-tree-anexos-processos .delete').hide();
                            $('#menu-tree-anexos-processos .expandAll, #menu-tree-anexos-processos .collapseAll').show();
                        }
                        else {
                            $('#menu-tree-anexos-processos .expandAll, #menu-tree-anexos-processos .collapseAll').hide();
                        }
                        $('#menu-tree-anexos-processos').css('top', event.pageY).css('left', event.pageX).show();

                        $('*').click(function() {
                            $('#menu-tree-anexos-processos').hide();
                        });
                    }

                });

                /*Carregar todos os vinculos*/
                treeOperations.expandAll($('#tree-anexos-processos > .root > ul'));

                /*Menus*/
                $('#menu-tree-anexos-processos .expandAll').click(function() {
                    treeOperations.expandAll($('#tree-anexos-processos > .root > ul'));
                });
                $('#menu-tree-anexos-processos .collapseAll').click(function() {
                    treeOperations.collapseAll();
                });

                $('#menu-tree-anexos-processos .expandAll').append(langManager.expandAll);
                $('#menu-tree-anexos-processos .collapseAll').append(langManager.collapseAll);

            });
        </script>
    </head>
    <body>
        <div class="contextMenu" id="menu-tree-anexos-processos">
            <li class="expandAll"><img alt="" src="plugins/tree/css/images/expand.png"/></li>
            <li class="collapseAll"><img alt="" src="plugins/tree/css/images/collapse.png"/></li>
        </div>
        <ul id="tree-anexos-processos" class="arvoreProcessos">
            <li class="<?php print(Processo::validarProcessoAreaDeTrabalho($root) ? 'root' : 'root root-ausente') ?>" id='<?php print($arvore->getRootId()); ?>'><span title="Processo mais relevante da arvore"><?php print($root); ?></span>
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