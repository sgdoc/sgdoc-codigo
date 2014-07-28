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
            body{
                margin: 10px;
            }
        </style>


        <script type="text/javascript">

            var oTableDemandasQualificar;
            var oTableDemandasHistorico;
                       
            $(document).ready(function() {
                /*Listeners*/
                $('#tab-1').click(function(){oTableDemandasQualificar.fnDraw(false);});
                $('#tab-2').click(function(){oTableDemandasHistorico.fnDraw(false);});
           
                /*Tabs*/
                $("#tabs").tabs();
                $(".cabecalho-caixas").tabs();

                /*DataTable*/
                oTableDemandasQualificar = $('#grid_demandas_qualificar').dataTable({
                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    bStateSave: false,
                    bPaginate: true,
                    bProcessing: true,
                    bServerSide: true,
                    bJQueryUI: true,
                    sPaginationType: "full_numbers",
                    sAjaxSource: "modelos/suporte/listar.php?acao=demandas-qualificar",
                    aoColumnDefs: [{ bSortable: false, aTargets: [8] }],
                    oLanguage: {
                        sProcessing: "Carregando...",
                        sLengthMenu: "_MENU_ por página",
                        sZeroRecords: "Nenhum chamado encontrado.",
                        sInfo: "_START_ a _END_ de _TOTAL_ demandas.",
                        sInfoEmpty: "Nao foi possivel localizar demandas com os parametros informados!",
                        sInfoFiltered: "(Total _MAX_ demandas)",
                        sInfoPostFix: "",
                        sSearch: "Pesquisar:",
                        oPaginate: {
                            sFirst: "Primeiro",
                            sPrevious: "Anterior",
                            sNext:  "Próximo",
                            sLast:  "Ultimo"
                        }
                    },
                    fnServerData: function ( sSource, aoData, fnCallback ) {
                        $.getJSON( sSource, aoData, function (json) {
                            fnCallback(json);
                            if(json.iTotalRecords == 0){
                                jquery_datatable_complementa_mensagem_vazia('grid_demandas_qualificar');
                            }
                        } );
                    },
                    fnRowCallback: function(nRow, aData, iDisplayIndex) {
          
                        var $line = $('td:eq(8)', nRow);
                        $line.html('');
                        
                        /*Formatar datas*/
                        $('td:eq(2)', nRow).html(datetimeToPtBrFormat(aData[2]));
                        $('td:eq(4)', nRow).html(datetimeToPtBrFormat(aData[4]));
                                                                  
                        /*Combo Notas*/
                        var combo = $("<select/>", {
                            id: 'qualificar-demanda-'+aData[0],
                            'class': 'FUNDOCAIXA1'
                        }).appendTo($line);   
                        
                        var titles = {
                            '':'Selecione',
                            0:'Péssimo',
                            1:'Muito Ruim',
                            2:'Ruim',
                            3:'Bom',
                            4:'Ótimo',
                            5:'Excelente'
                        };
                              
                        $.each(titles, function(val, text) { 
                            combo.append('<option value='+val+'>'+text+'</option>');(titles); 
                        });
                      
                        /*Botao Qualificar*/
                        $("<input/>", {
                            title: 'Salvar a qualificação',
                            type: 'button',
                            value: 'OK',
                            'class': 'FUNDOCAIXA'
                        }).bind( "click", function(){
                            jquery_qualificar_demanda(aData[0],$('#qualificar-demanda-'+aData[0]).val());
                        }).appendTo($line);
                        
                        return nRow;
                    }
                });
                
                oTableDemandasHistorico= $('#grid_historico_demandas').dataTable({
                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    bStateSave: false,
                    bPaginate: true,
                    bProcessing: true,
                    bServerSide: true,
                    bJQueryUI: true,
                    sPaginationType: "full_numbers",
                    sAjaxSource: "modelos/suporte/listar.php?acao=historico-demandas-usuario",
                    oLanguage: {
                        sProcessing: "Carregando...",
                        sLengthMenu: "_MENU_ por página",
                        sZeroRecords: "Nenhum chamado encontrado.",
                        sInfo: "_START_ a _END_ de _TOTAL_ demandas.",
                        sInfoEmpty: "Nao foi possivel localizar demandas com os parametros informados!",
                        sInfoFiltered: "(Total _MAX_ demandas)",
                        sInfoPostFix: "",
                        sSearch: "Pesquisar:",
                        oPaginate: {
                            sFirst: "Primeiro",
                            sPrevious: "Anterior",
                            sNext:  "Próximo",
                            sLast:  "Ultimo"
                        }
                    },
                    fnServerData: function ( sSource, aoData, fnCallback ) {
                        $.getJSON( sSource, aoData, function (json) {
                            fnCallback(json);
                            if(json.iTotalRecords == 0){
                                jquery_datatable_complementa_mensagem_vazia('grid_historico_demandas');
                            }
                        } );
                    },
                    fnRowCallback: function(nRow, aData, iDisplayIndex) {
          
                        var $line = $('td:eq(8)', nRow);
                        $line.html('');
                        
                        /*Formatar datas*/
                        $('td:eq(2)', nRow).html(datetimeToPtBrFormat(aData[2]));
                        $('td:eq(4)', nRow).html(datetimeToPtBrFormat(aData[4]));
                        
                        var titles = {
                            0:'Péssimo',
                            1:'Muito Ruim',
                            2:'Ruim',
                            3:'Bom',
                            4:'Ótimo',
                            5:'Excelente'
                        };               
                
                        $('td:eq(8)', nRow).html(titles[aData[8]]);
                
                        return nRow;
                    }
                });
              
            });

            /*Detalhar Resolver Demanda*/
            function jquery_qualificar_demanda(demanda,nota){
                if(!$('#qualificar-demanda-'+demanda).val()){
                    alert('A nota para qualificação é obrigatória!'); 
                    return false;
                }
                
                var titles = {
                    0:'Péssimo',
                    1:'Muito Ruim',
                    2:'Ruim',
                    3:'Bom',
                    4:'Ótimo',
                    5:'Excelente'
                };
                
                if(confirm('Você tem certeza deseja atribuir a qualificação "'+titles[nota]+'" para este chamado?')){
                    $.post("modelos/suporte/suporte.php", {
                        acao: 'qualificar-demanda',
                        demanda: demanda,
                        nota: nota
                    },
                    function(data){
                        if(data.success == 'true'){
                            oTableDemandasQualificar.fnDraw(false);
                        }else{
                            alert('Ocorreu um erro ao tentar qualificar este chamado!');
                        }
                    }, "json");

                }
            }
                 
        </script>
    </head>
    <body>
        <div class="cabecalho-caixas">
            <div class="logo-manter-unidades"></div>
            <div class="titulo-manter-unidades">Histórico de Chamados</div>
            <div class="menu-auxiliar">
                <?php Util::montaMenus($controller->botoes, array('class' => 'botao32')); ?>
            </div>
        </div>
        <div id="tabs">
            <ul>
                <li><a id="tab-1" title="Este chamados já foram finalizados pelos atendentes" href="#tabs-1">Chamados sem Qualificação</a></li>
                <li><a id="tab-2" title="Todos os chamados finalizados e qualificados" href="#tabs-2">Histórico de Chamados</a></li>
            </ul>
            <div id="tabs-1">
                <table class="display" border="0" id="grid_demandas_qualificar">
                    <thead>
                        <tr>
                            <th class="style13">#</th>
                            <th class="style13">Protocolo</th>
                            <th class="style13">Abertura</th>
                            <th class="style13">Atendente</th>
                            <th class="style13">Finalização</th>
                            <th class="style13">Assunto</th>
                            <th class="style13">Descrição</th>
                            <th class="style13">Comentário</th>
                            <th class="style13">Qualificação</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="tabs-2">
                <table class="display" border="0" id="grid_historico_demandas">
                    <thead>
                        <tr> 
                            <th class="style13">#</th>
                            <th class="style13">Protocolo</th>
                            <th class="style13">Abertura</th>
                            <th class="style13">Atendente</th>
                            <th class="style13">Finalização</th>
                            <th class="style13">Assunto</th>
                            <th class="style13">Descrição</th>
                            <th class="style13">Comentário</th>
                            <th class="style13">Qualificação</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </body>
</html>
