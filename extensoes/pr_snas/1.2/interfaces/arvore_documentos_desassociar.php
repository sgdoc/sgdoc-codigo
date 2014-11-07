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

include_once("function/auto_load_statics.php");
include(__BASE_PATH__ . '/extensoes/pr_snas/1.2/interfaces/detalhar_documentos.php');


$urlManager = __APPMODELS__ . "arvores/listar_documentos_associados.php";
$digital = $_GET['digital'];
$arvore = new Arvore();


$vinculacao = new Vinculacao();
$root = $vinculacao->setDocumentoRoot($digital, 3/* Associado */);
$arvore->setRootId($root);
$elementos = $arvore->getVinculacaoDocumento($root, $urlManager, 3/* Associado */);
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
                treeOperations = new TreeOperations($('#tree-documentos-associados'),'<?php print($urlManager); ?>');

                /*Arvore */
                $('#tree-documentos-associados').simpleTree({
                    animate:true,
                    autoclose: false,
                    restoreTreeState: false,
                    afterClick:function(node){
                        jquery_detalhar_documento($('span:first',node).text());
                    },
                    afterDblClick:function(node){
                        //jquery_detalhar_documento($('span:first',node).text());
                    },
                    afterMove:function(destination, source, pos) {},
                    afterAjax:function(node){
                    
                        /*Documento Selecionado*/
                        if(node.attr('idElemento')=='<?php print($digital); ?>'){
                            $('#'+node.attr('idElemento')).attr('class','folder-open-target');
                            $('#'+node.attr('idElemento')).attr('title','Documento selecionado');
                            if (node.html().length == 1) {
                                $('#'+node.attr('idElemento')).attr('class','folder-close-target');
                            }
                        }

                        /*Documento ausente*/
                        if(node.attr('stAusente')=='true'){
                            $('#'+node.attr('idElemento')).attr('class','folder-open-ausente');
                            $('#'+node.attr('idElemento')).attr('title','Este documento nao esta na sua area de trabalho');
                            if (node.html().length == 1) {
                                $('#'+node.attr('idElemento')).attr('class','folder-close-ausente');
                            }
                        }
                    },
                    afterContextMenu: function(element, event){
                        var className = element.attr('class');
                        if (className.indexOf('root') >= 0) {
                            $('#menu-tree-documentos-associados .edit, #menu-tree-documentos-associados .delete').hide();
                            $('#menu-tree-documentos-associados .expandAll, #menu-tree-documentos-associados .collapseAll').show();
                        }
                        else {
                            $('#menu-tree-documentos-associados .expandAll, #menu-tree-documentos-associados .collapseAll').hide();
                        }
                        $('#menu-tree-documentos-associados').css('top',event.pageY).css('left',event.pageX).show();
                      
                        $('*').click(function() {
                            $('#menu-tree-documentos-associados').hide();
                        });
                    }

                });

                /*Carregar todos os vinculos*/
                treeOperations.expandAll($('#tree-documentos-associados > .root > ul'));

                /*Menus*/
                $('#menu-tree-documentos-associados .expandAll').click(function (){
                    treeOperations.expandAll($('#tree-documentos-associados > .root > ul'));
                });
                $('#menu-tree-documentos-associados .collapseAll').click(function (){
                    treeOperations.collapseAll();
                });

                $('#menu-tree-documentos-associados .expandAll').append(langManager.expandAll);
                $('#menu-tree-documentos-associados .collapseAll').append(langManager.collapseAll);

            });
        </script>
    </head>
    <body>
        <div class="contextMenu" id="menu-tree-documentos-associados">
            <li class="expandAll"><img alt="" src="plugins/tree/css/images/expand.png"/></li>
            <li class="collapseAll"><img alt="" src="plugins/tree/css/images/collapse.png"/></li>
        </div>
        <ul id="tree-anexos-documentos" class="arvoreDocumentos">
            <li class='<?php print(Documento::validarDocumentoAreaDeTrabalho($root) ? 'root' : 'root root-ausente') ?>' id='<?php print($arvore->getRootId()); ?>'><span title="Documento mais relevante da arvore"><?php print($root); ?></span>
                <ul><?php print($elementos); ?></ul>
            </li>
        </ul>
    </body>
</html>
