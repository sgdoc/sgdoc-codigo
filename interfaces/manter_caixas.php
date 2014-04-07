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

            var oTableCaixas;

            $(document).ready(function() {
              
                /*Tabs*/
                $("#tabs").tabs();
                $(".cabecalho-caixas").tabs();

                /*DataTable*/
                oTableCaixas = $('#tabela_caixas').dataTable( {
                    aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    bStateSave: false,
                    bPaginate: true,
                    bProcessing: false,
                    bServerSide: true,
                    bJQueryUI: true,
                    sPaginationType: "full_numbers",
                    sAjaxSource: "modelos/caixas/listar_caixas.php",
                    aoColumnDefs: [{ bSortable: false, aTargets: [0,2,3,4,5,6,7,8] }],
                    oLanguage: {
                        sProcessing: "Carregando...",
                        sLengthMenu: "_MENU_ por página",
                        sZeroRecords: "Nenhuma caixa encontrada.",
                        sInfo: "_START_ a _END_ de _TOTAL_ caixas.",
                        sInfoEmpty: "Nao foi possivel localizar caixas com os parametros informados!",
                        sInfoFiltered: "(Total _MAX_ caixas)",
                        sInfoPostFix: "",
                        sSearch: "Pesquisar:",
                        oPaginate: {
                            sFirst:    "Primeira",
                            sPrevious: "Anterior",
                            sNext:     "Proxima",
                            sLast:     "Ultima"
                        }
                    },
                    fnServerData: function ( sSource, aoData, fnCallback ) {
                        $.getJSON( sSource, aoData, function (json) {
                            fnCallback(json);
                        } );
                    },
                    fnRowCallback: function(nRow, aData, iDisplayIndex) {
                        nRow.setAttribute( 'title', aData[1] );

                        var $line = $('td:eq(8)', nRow);
                        $line.html('');
                        
                        if(aData[3]==''){
                            $('td:eq(3)', nRow).html('<div title=""></div>');
                        }
                        
                        if(aData[4]==''){
                            $('td:eq(4)', nRow).html('<div title=""></div>');
                        }
                        
                        /*Finalizada*/
                        if(aData[6]==1){
                            $('td:eq(6)', nRow).html('Fechada');
                        }else{
                            $('td:eq(6)', nRow).html('Aberta');
                        }
                        
                        /* Converter formato Date para String (dd/mm/aaaa) */
                        $('td:eq(7)', nRow).html(convertDateToString(aData[7]));
                        
                        // esta ativa, exibe botoes para alterar, excluir e finalizar/reabrir
                        // Alterar
                        if(aData[8] == 0) {
                            $("<img/>", {
                                src: 'imagens/alterar.png',
                                title: 'Editar',
                                'class': 'botao32'
                            }).bind( "click", function(){
                                jquery_detalhar_caixas(aData[0]);
                            }).appendTo($line);
                        }
                        
                        // Excluir se não tiver documentos na caixa
                        if(aData[8] == 0) {
                            $("<img/>", {
                                src: 'imagens/cancelar_upload.png',
                                title: 'Excluir',
                                'class': 'botao32'
                            }).bind( "click", function(){
                                jquery_alterar_status_caixa(aData[0], 0);
                            }).appendTo($line);
                        }
                            
                        // Verifica se a caixa esta finalizada ou não
                        if(aData[6] == 1) {
                            // Caixa esta finalizada, exibe botao para reabrir
                            $("<img/>", {
                                src: 'imagens/icones/32/abrir-caixa.png',
                                title: 'Reabrir Caixa com '+aData[8]+' documentos',
                                'class': 'botao32'
                            }).bind( "click", function(){
                                jquery_alterar_finalizacao_caixa(aData[0], 0);
                            }).appendTo($line);
                        } else {
                            // Caixa esta aberta, exibe botao para inserir documento na caixa
                            $("<img/>", {
                                src: 'imagens/adicionar_documento_caixa.png',
                                title: 'Adicionar Documento',
                                'class': 'botao32'
                            }).bind( "click", function(){
                                jquery_adicionar_documento_caixa(aData[0]);
                            }).appendTo($line);
                            
                            // se tiver algum documento na caixa
                            if(aData[8] > 0) {
                                // listar documentos na caixa
                                $("<img/>", {
                                    src: 'imagens/search.png',
                                    title: 'Listar '+aData[8]+' Documentos na Caixa',
                                    'class': 'botao32'
                                }).bind( "click", function(){
                                    jquery_listar_documentos_caixa(aData[0]);
                                }).appendTo($line);
                                
                                // e fechar a caixa
                                $("<img/>", {
                                    src: 'imagens/icones/32/finalizar-caixa.png',
                                    title: 'Finalizar Caixa',
                                    'class': 'botao32'
                                }).bind( "click", function(){
                                    jquery_alterar_finalizacao_caixa(aData[0], 1);
                                }).appendTo($line);
                            }
                        }
                        
                        return nRow;
                    }
                });
        
                /*Dialogs*/
                /*Detalhar*/
                $('#box-detalhar-caixas').dialog({
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    width: 600,
                    autoHeight: true,
                    buttons: {
                        Salvar: function() {
                            var validou = jquery_validar_nova_caixa('DETALHAR');
                            if(validou == true){
                                var c = confirm('Você tem certeza que deseja salvar esta caixa agora?');
                                if(c){
                                    $.post("modelos/caixas/caixas.php", {
                                        acao: 'alterar',
                                        id: $('#ID_DETALHAR_CAIXA').val(),
                                        id_classificacao: $('#ID_CLASSIFICACAO_DETALHAR_CAIXA').val(),
                                        nu_caixa: $('#NU_DETALHAR_CAIXA').val(),
                                        id_unidade: $('#ID_UNIDADE_DETALHAR_CAIXA').val(),
                                        nu_ano_caixa: $('#NU_ANO_DETALHAR_CAIXA').val()
                                    },
                                    function(data){
                                        if(data.success == 'true'){
                                            $('#box-detalhar-caixas').dialog("close");
                                            oTableCaixas.fnDraw(false);
                                            alert(data.message);
                                        }else{
                                            alert('Ocorreu um erro ao tentar salvar as informacoes da caixa!\n['+data.error+']');
                                        }
                                    }, "json");
                                }
                            }else{
                                alert(validou);
                            }
                        }
                    }
                });
                /*Adicionar Documento*/
                $('#box-adicionar-documento').dialog({
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    width: 600,
                    autoHeight: true,
                    buttons: {
                        Adicionar: function() {
                            var validou = jquery_validar_adicionar_documento();
                            if(validou == true){
                                $.post("modelos/caixas/caixas.php", {
                                    acao: 'adicionar-documento',
                                    id_caixa: $('#ID_ADICIONAR_DOCUMENTO_CAIXA').val(),
                                    id_documento: $('#ID_DIGITAL_DOCUMENTO').val()
                                },
                                function(data){
                                    if(data.success == 'true'){
                                        oTableCaixas.fnDraw(false);
                                        $('#box-adicionar-documento').dialog("close");
                                    }else{
                                        alert('Ocorreu um erro ao tentar adicionar documento na caixa!\n['+data.error+']');
                                        // $('#box-adicionar-documento').dialog("close");
                                    }
                                }, "json");
                            }else{
                                alert(validou);
                            }
                        }
                    }
                });
                
                $('#box-filtro-digital-adicionar-documento').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 90
                });
                
                /*Listeners*/
                           
                /*Carregar Combos*/
                $('#ID_CLASSIFICACAO_DETALHAR_CAIXA').combobox('modelos/combos/classificacoes.php', {'tipo':'pai'});
            });

            /*Functions*/
            function jquery_validar_adicionar_documento(){
                if(($('#ID_DIGITAL_DOCUMENTO').val() != '0' && $('#ID_DIGITAL_DOCUMENTO').val() != '') &&
                    $('#ID_ADICIONAR_DOCUMENTO_CAIXA').val()) {
                    return true;
                } else{
                    return 'Selecione um documento válido!';
                }
            }
            
            function jquery_detalhar_caixas(id){
                $.post("modelos/caixas/caixas.php", {
                    acao: 'get',
                    valor: id,
                    campo: '*'
                },
                function(data){
                    if(data.success == 'true'){
                        $('#ID_DETALHAR_CAIXA').val(data.id);
                        $('#NU_DETALHAR_CAIXA').val(data.nu_caixa);
                        $('#ID_CLASSIFICACAO_DETALHAR_CAIXA').val(data.id_classificacao);
                        //$('#ID_UNIDADE_DETALHAR_CAIXA').val(data.id_unidade);
                        $('#NU_ANO_DETALHAR_CAIXA').val(data.nu_ano_caixa);
                        $('#DT_CAD_DETALHAR_CAIXA').val(data.dt_cadastro);
                        
                        /*Carregar Combos*/
                        $('#ID_UNIDADE_DETALHAR_CAIXA').combobox('modelos/combos/unidades.php', {
                            tipo: 'id',
                            id: data.id_unidade
                        });

                        $('#box-detalhar-caixas').dialog('open');
                    }else{
                        alert('Ocorreu um erro ao tentar detalhar as informacoes da caixa!');
                    }
                }, "json");

            }

            function jquery_alterar_status_caixa(id, status){
                $('#progressbar').show();
                $.post("modelos/caixas/caixas.php", 
                {
                    acao: 'alterar-status',
                    id: id,
                    status: parseInt(status)
                },
                function(data){
                    $('#progressbar').hide();
                    if(data.success == 'true'){
                        oTableCaixas.fnDraw(false);
                    } else {
                        alert('Ocorreu um erro ao tentar alterar o status da caixa!');
                    }
                },"json");        
            }
            
            function jquery_alterar_finalizacao_caixa(id, finalizacao){
                $('#progressbar').show();
                $.post("modelos/caixas/caixas.php", 
                {
                    acao: 'alterar-finalizacao',
                    id: id,
                    st_finalizada: parseInt(finalizacao)
                },
                function(data){
                    $('#progressbar').hide();
                    if(data.success == 'true'){
                        oTableCaixas.fnDraw(false);
                    } else {
                        alert('Ocorreu um erro ao tentar alterar a finalizacao da caixa!');
                    }
                },"json");
            }
            
            function jquery_adicionar_documento_caixa(id){
                $('#ID_ADICIONAR_DOCUMENTO_CAIXA').val(id);
                $('#ID_DIGITAL_DOCUMENTO').html("");
                $('#FILTRO_DIGITAL_ADICIONAR_DOCUMENTO').val("");
                $('#ID_DIGITAL_DOCUMENTO').combobox('modelos/combos/documentos.php', {'caixa':id});
                $("#FILTRO_DIGITAL_ADICIONAR_DOCUMENTO").unbind();
                $("#FILTRO_DIGITAL_ADICIONAR_DOCUMENTO").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox: 'ID_DIGITAL_DOCUMENTO',
                    delay: 2500,
                    minChars: 7,
                    extraParams: {
                        action: 'caixas-digital',
                        caixa: $("#ID_ADICIONAR_DOCUMENTO_CAIXA").val()
                    }
                });
                
                $('#box-adicionar-documento').dialog('open');
            }
            
            /**
             ** Correcao 31/01/2013
             */
            $(document).ready(function(){
                /*Filtro Assunto*/
                $('#box-filtro-unidade-detalhar-caixaa').dialog({
                    title: 'Filtro',
                    autoOpen: false,
                    resizable: false,
                    modal: false,
                    width: 380,
                    height: 90,
                    open: function() {
                        $("#FILTRO_UNIDADE_DETALHAR_CAIXA").val('');
                    }
                });
                /*Filtro Unidade*/
                $('#botao-filtro-unidade-detalhar-caixa').click(function(){
                    $('#box-filtro-unidade-detalhar-caixaa').dialog('open');
                });
                /*Combo Unidades*/
                $("#FILTRO_UNIDADE_DETALHAR_CAIXA").autocompleteonline({
                    url: 'modelos/combos/autocomplete.php',
                    idComboBox:'ID_UNIDADE_DETALHAR_CAIXA',
                    extraParams: {
                        action: 'unidades-internas',
                        type: 'IN'
                    }
                });
            });
        </script>
    </head>
    <body>
        <div class="cabecalho-caixas">
            <div class="logo-manter-unidades"></div>
            <div class="titulo-manter-unidades">Gerenciamento de Caixas</div>
            <div class="menu-auxiliar">
                <?php Util::montaMenus($controller->botoes, array('class' => 'botao32')); ?>
            </div>
        </div>
        <div id="tabs">
            <ul>
                <li><a title="" href="#tabs-1">Lista de Caixas do Arquivo</a></li>
            </ul>
            <div id="tabs-1">
                <table class="display" border="0" id="tabela_caixas">
                    <thead>
                        <tr>
                            <th class="style13">#</th>
                            <th class="style13">Número</th>
                            <th class="style13">Classificação</th>
                            <th class="style13">Usuário</th>
                            <th class="style13">Unidade</th>
                            <th class="style13">Ano Caixa</th>
                            <th class="style13">Situação</th>
                            <th class="style13">Data Cadastro</th>
                            <th class="style13">Opções</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!--Detalhar-->
        <div id="box-detalhar-caixas" class="div-form-dialog" title="Detalhes da Caixa">
            <fieldset>
                <label class="label">Informações Principais</label>
                <input class="FUNDOCAIXA1" id="ID_DETALHAR_CAIXA" type="hidden">
                <div class="row">
                    <label class="label">*NUMERO:</label>
                    <span class="conteudo">
                        <input type="text" class="FUNDOCAIXA1" id="NU_DETALHAR_CAIXA" maxlength="8" onkeyup="zeroFill(this, 7);" />
                    </span>
                </div>
                <div class="row">
                    <label class="label">*ANO CAIXA:</label>
                    <span class="conteudo">
                        <input type="text" class="FUNDOCAIXA1" id="NU_ANO_DETALHAR_CAIXA" onkeyup="DigitaNumero(this)" maxlength="4" />
                    </span>
                </div>
                <div class="row">
                    <label class="label">CLASSIFICACAO:</label>
                    <span class="conteudo">
                        <select class="FUNDOCAIXA1" id="ID_CLASSIFICACAO_DETALHAR_CAIXA"></select>
                    </span>
                </div>
                <div class="row">
                    <label class="label">*UNIDADE:</label>
                    <span class="conteudo">
                        <select class="FUNDOCAIXA1" id="ID_UNIDADE_DETALHAR_CAIXA"></select>
                    </span>
                    <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-unidade-detalhar-caixa" src="imagens/fam/application_edit.png">
                </div>

            </fieldset>
        </div>

        <!--Adicionar Documento-->
        <div id="box-adicionar-documento" class="div-form-dialog" title="Adicionar Documento">
            <fieldset>
                <label>Informações Principais</label>
                <input class="FUNDOCAIXA1" id="ID_ADICIONAR_DOCUMENTO_CAIXA" type="hidden">
                <div class="row">
                    <label class="label" for="ID_DIGITAL_DOCUMENTO">DOCUMENTO:</label>
                    <span class="conteudo">
                        <select class="FUNDOCAIXA1" id="ID_DIGITAL_DOCUMENTO"></select>
                    </span>
                </div>
                <div class="row">
                    <label class="label" for="FILTRO_DIGITAL_ADICIONAR_DOCUMENTO">FILTRAR POR DIGITAL:</label>
                    <span class="conteudo">
                        <input id="FILTRO_DIGITAL_ADICIONAR_DOCUMENTO" maxlength="7" onkeyup="DigitaNumero(this)" class="FUNDOCAIXA1" style="border: 1px solid #9AC619;-moz-border-radius: 5px;-webkit-border-radius: 5px;padding: 2px;">
                    </span>
                </div>
            </fieldset>
        </div>

        <div id="box-filtro-unidade-detalhar-caixaa" class="box-filtro">
            <div class="row">
                <label>Unidade:</label>
                <div class="conteudo">
                    <input type="text" id="FILTRO_UNIDADE_DETALHAR_CAIXA" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
                </div>
            </div>
        </div>

    </body>
</html>