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
var langManager = new languageManager();

langManager.load("pt");

var treeOps = new TreeOperations();
$(document).ready(function() {
	
    // binding menu functions
    $('#myMenu1 .addDoc').click(function()  {
        treeOps.addElementReq();
    });
    $('#myMenu1 .addFolder').click(function()  {
        treeOps.addElementReq(true);
    });
    $('#myMenu1 .edit, #myMenu2 .edit').click(function() {
        treeOps.updateElementNameReq();
    });
    $('#myMenu1 .delete, #myMenu2 .delete').click(function() {
        treeOps.deleteElementReq();
    });
    $('#myMenu1 .expandAll').click(function (){
        treeOps.expandAll($('.simpleTree > .root > ul'));
    });
    $('#myMenu1 .collapseAll').click(function (){
        treeOps.collapseAll();
    });
	
	
    // setting menu texts
    $('#myMenu1 .addDoc').append(langManager.addDocMenu);
    $('#myMenu1 .addFolder').append(langManager.addFolderMenu);
    $('#myMenu1 .edit, #myMenu2 .edit').append(langManager.editMenu);
    $('#myMenu1 .delete, #myMenu2 .delete').append(langManager.deleteMenu);
    $('#myMenu1 .expandAll').append(langManager.expandAll);
    $('#myMenu1 .collapseAll').append(langManager.collapseAll);
	
		
    // initialization of tree
    simpleTree = $('.simpleTree').simpleTree({
        autoclose: false,
        /**
		 * restore tree state according the cookies it stored.
		 */
        restoreTreeState: true,
		
        /**
		 * Callback function is called when one item is clicked
		 */	
        afterClick:function(node){
        //alert($('span:first', node).text() + " clicked");
        //alert($('span:first',node).parent().attr('id'));
        },
        /**
		 * Callback function is called when one item is double-clicked
		 */	
        afterDblClick:function(node){
            alert($('span:first',node).text() + " double clicked");
        },
        afterMove:function(destination, source, pos) {
            //	alert("destination-"+destination.attr('id')+" source-"+source.attr('id')+" pos-"+pos);
            if (dragOperation == true)
            {
				
                var params = "action=changeOrder&elementId="+source.attr('id')+"&destOwnerEl="+destination.attr('id')+
                "&position="+pos + "&oldOwnerEl=" + simpleTree.get(0).ownerElOfDraggingItem;
				
                treeOps.ajaxReq(params, structureManagerURL, null, function(result)
                {
                    treeOps.treeBusy = false;
                    treeOps.showInProcessInfo(false);
                    try {
                        var t = eval(result);
                        // if result is less than 0, it means an error occured
                        if (treeOps.isInt(t) == true  && t < 0) {
                            alert(eval("langManager.error_" + Math.abs(t)) + "\n"+langManager.willReload);
                            window.location.reload();
                        }
                        else {
                            var info = eval("(" + result + ")");
                            $('#' + info.oldElementId).attr("id", info.elementId);
                        }
                    }
                    catch(ex) {
                        var info = eval("(" + result + ")");
                        $('#' + info.oldElementId).attr("id", info.elementId);
                    }
                });
            }
        },
        afterAjax:function(node)
        {
            if (node.html().length == 1) {
                node.html("<li class='line-last'></li>");
            }
        },
        afterContextMenu: function(element, event)
        {
            var className = element.attr('class');
            if (className.indexOf('doc') >= 0) {
                $('#myMenu2').css('top',event.pageY).css('left',event.pageX).show();
            }
            else {
                if (className.indexOf('root') >= 0) {
                    $('#myMenu1 .edit, #myMenu1 .delete').hide();
                    $('#myMenu1 .expandAll, #myMenu1 .collapseAll').show();
                }
                else {
                    $('#myMenu1 .expandAll, #myMenu1 .collapseAll').hide();
                }
                $('#myMenu1').css('top',event.pageY).css('left',event.pageX).show();
            }
			
            $('*').click(function() {
                $('#myMenu1, #myMenu2').hide();
                $('#myMenu1 .edit, #myMenu1 .delete').show();
            });
			
        },
        animate:true

    });
});