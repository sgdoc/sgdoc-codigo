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
?>
<script type="text/javascript">

    var oTableDemandasAtendente;

    $(document).ready(function() {
        /*DataTable*/
        oTableDemandasAtendente = $('#grid_demandas_pendentes').dataTable({
            aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: true,
            bProcessing: true,
            bServerSide: true,
            bJQueryUI: true,
            sPaginationType: "full_numbers",
            sAjaxSource: "modelos/suporte/listar.php?acao=minha-caixa-de-demandas",
            aoColumnDefs: [{ bSortable: false, aTargets: [10] }],
            oLanguage: {
                sProcessing: "Carregando...",
                sLengthMenu: "_MENU_ por página",
                sZeroRecords: "Nenhuma demanda encontrada.",
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
                } );
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {

                var $line = $('td:eq(10)', nRow);
                $line.html('<div title="">');

                /*Formatar datas*/
                $('td:eq(2)', nRow).html(datetimeToPtBrFormat(aData[2]));

                if(aData[5] == '') {
                    $('td:eq(5)', nRow).html('Em Branco')
                }

                /*Skype Ativo*/
                if(aData[9] != '' && aData[9] != 'Em Branco'){
                    $('td:eq(9)', nRow).html('<a title="Bate-papo do Skype" href="skype:'+aData[9]+'?chat"><img class="botao32" src="imagens/skype.png"></a>');
                }else{
                    $('td:eq(9)', nRow).html('<a title="Este usuário não possui Skype!" href="#"><img class="botao32" src="imagens/skype-off.png"></a>');
                }

                /*Resolver demandas*/
                $("<img/>", {
                    src: 'imagens/resolver_demandas.png',
                    title: 'Resolver',
                    'class': 'botao32'
                }).bind( "click", function(){
                    jquery_detalhar_resolver_demanda(aData[0]);
                }).appendTo($line);

                $("</div>").appendTo($line);

                return nRow;
            }
        });
        
        /*Detalhar Resolver Demanda*/
        $('#box-detalhar-resolver-demanda').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 650,
            autoHeight: true,
            buttons: {
                Devolver:function(){
                    if($('#COMENTARIO_ATENDENTE_DETALHAR_RESOLVER_DEMANDA').val()){
                        var c = confirm('Você certeza que deseja devolver esta demanda?');
                        if(c){
                            $.post("modelos/suporte/suporte.php", {
                                acao: 'devolver-demanda',
                                demanda: $('#ID_DETALHAR_RESOLVER_DEMANDA').val(),
                                comentario: $('#COMENTARIO_ATENDENTE_DETALHAR_RESOLVER_DEMANDA').val()                                       
                            },
                            function(data){
                                if(data.success == 'true'){
                                    $('#box-detalhar-resolver-demanda').dialog("close");
                                    oTableDemandasAtendente.fnDraw(false);
                                    alert(data.message);
                                }else{
                                    alert('Ocorreu um erro ao tentar finalizar a demanda!\n['+data.error+']');
                                }
                            }, "json");
                        }
                    }else{
                        alert('O comentário do atendente é obrigatório!');
                    }
                },
                Finalizar: function() {
                    if($('#COMENTARIO_ATENDENTE_DETALHAR_RESOLVER_DEMANDA').val()){
                        var c = confirm('Você certeza que deseja finalizar esta demanda?');
                        if(c){
                            $.post("modelos/suporte/suporte.php", {
                                acao: 'finalizar-demanda',
                                demanda: $('#ID_DETALHAR_RESOLVER_DEMANDA').val(),
                                comentario: $('#COMENTARIO_ATENDENTE_DETALHAR_RESOLVER_DEMANDA').val()                                       
                            },
                            function(data){
                                if(data.success == 'true'){
                                    $('#box-detalhar-resolver-demanda').dialog("close");
                                    oTableDemandasAtendente.fnDraw(false);
                                    alert(data.message);
                                }else{
                                    alert('Ocorreu um erro ao tentar finalizar a demanda!\n['+data.error+']');
                                }
                            }, "json");
                        }
                    }else{
                        alert('O comentário do atendente é obrigatório!');
                    }
                }
            }
        });
    });
    
    /*Detalhar Resolver Demanda*/
    function jquery_detalhar_resolver_demanda(id){
        $.post("modelos/suporte/suporte.php", {
            acao: 'detalhar-demanda',
            demanda: id
        },
        function(data){
            if(data.success == 'true'){
                $('#ID_DETALHAR_RESOLVER_DEMANDA').val(data.demanda.id);
                $('#PROTOCOLO_DETALHAR_RESOLVER_DEMANDA').val(data.demanda.cd_protocolo);
                $('#DATA_ABERTURA_DETALHAR_RESOLVER_DEMANDA').val(datetimeToPtBrFormat(""+data.demanda.dt_abertura+""));
                $('#ASSUNTO_DETALHAR_RESOLVER_DEMANDA').val(data.demanda.tx_assunto);
                $('#DESCRICAO_DETALHAR_RESOLVER_DEMANDA').val(data.demanda.tx_descricao);
                $('#DATA_TRIAGEM_DETALHAR_RESOLVER_DEMANDA').val(datetimeToPtBrFormat(""+data.demanda.dt_triagem+""));
                $('#NOME_TRIAGEM_DETALHAR_RESOLVER_DEMANDA').val(data.demanda.nm_triagem);
                $('#COMENTARIO_TRIAGEM_DETALHAR_RESOLVER_DEMANDA').val(data.demanda.tx_comentario);
                $('#USUARIO_DETALHAR_RESOLVER_DEMANDA').val(data.demanda.nm_usuario);
                $('#UNIDADE_DETALHAR_RESOLVER_DEMANDA').val(data.demanda.nm_unidade);
                $('#EMAIL_DETALHAR_RESOLVER_DEMANDA').val(data.demanda.tx_email);
                $('#SKYPE_DETALHAR_RESOLVER_DEMANDA').val(data.demanda.tx_skype);
                $('#TELEFONE_DETALHAR_RESOLVER_DEMANDA').val(data.demanda.nu_telefone);
                $('#COMENTARIO_ATENDENTE_DETALHAR_RESOLVER_DEMANDA').val('');
                $('#COMENTARIO_ATENDENTE_DETALHAR_RESOLVER_DEMANDA').focus();
                $('#box-detalhar-resolver-demanda').dialog('open');
            }else{
                alert('Ocorreu um erro ao tentar detalhar as informacoes da demanda!');
            }
        }, "json");

    }

</script>      

    <table class="display" border="0" id="grid_demandas_pendentes">
        <thead>
            <tr>
                <th class="style13">#</th>
                <th class="style13">Protocolo</th>
                <th class="style13">Abertura</th>
                <th class="style13">Usuário</th>
                <th class="style13">Setor</th>
                <th class="style13">Email</th>
                <th class="style13">Telefone</th>
                <th class="style13">Assunto</th>
                <th class="style13">Descrição</th>
                <th class="style13">Skype</th>
                <th class="style13">Opções</th>
            </tr>
        </thead>
    </table>


        <!--DETALHAR DEMANDAS RESOLVER-->
        <div id="box-detalhar-resolver-demanda" class="div-form-dialog" title="Detalhes da demanda">
            <fieldset>
                <input class="FUNDOCAIXA1" id="ID_DETALHAR_RESOLVER_DEMANDA" type="hidden">
                <div class="row">
                    <label class="label">PROTOCOLO:</label>
                    <span class="conteudo">
                        <input type="text" readonly class="FUNDOCAIXA1" id="PROTOCOLO_DETALHAR_RESOLVER_DEMANDA">
                    </span>
                </div>
                <div class="row">
                    <label class="label">DATA ABERTURA:</label>
                    <span class="conteudo">
                        <input type="text" readonly class="FUNDOCAIXA1" id="DATA_ABERTURA_DETALHAR_RESOLVER_DEMANDA">
                    </span>
                </div>
                <div class="row">
                    <label class="label">ASSUNTO:</label>
                    <span class="conteudo">
                        <input type="text" readonly class="FUNDOCAIXA1" id="ASSUNTO_DETALHAR_RESOLVER_DEMANDA">
                    </span>
                </div>
                <div class="row">
                    <label class="label">DESCRIÇÃO:</label>
                    <span class="conteudo">
                        <textarea cols="72" rows="3" readonly class="FUNDOCAIXA1" id="DESCRICAO_DETALHAR_RESOLVER_DEMANDA"></textarea>
                    </span>
                </div>
            </fieldset>

            <fieldset>
                <div class="row">
                    <label class="label">DATA TRIAGEM:</label>
                    <span class="conteudo">
                        <input type="text" readonly class="FUNDOCAIXA1" id="DATA_TRIAGEM_DETALHAR_RESOLVER_DEMANDA">
                    </span>
                </div>
                <div class="row">
                    <label class="label">TRIADO POR:</label>
                    <span class="conteudo">
                        <input type="text" readonly class="FUNDOCAIXA1" id="NOME_TRIAGEM_DETALHAR_RESOLVER_DEMANDA">
                    </span>
                </div>
                <div class="row">
                    <label class="label">COMENTÁRIO TRIAGEM:</label>
                    <span class="conteudo">
                        <textarea cols="72" rows="3" readonly class="FUNDOCAIXA1" id="COMENTARIO_TRIAGEM_DETALHAR_RESOLVER_DEMANDA"></textarea>
                    </span>
                </div>
            </fieldset>


            <fieldset style="display: none;">
                <div class="row">
                    <label class="label">USUÁRIO:</label>
                    <span class="conteudo">
                        <input type="text" readonly class="FUNDOCAIXA1" id="USUARIO_DETALHAR_RESOLVER_DEMANDA">
                    </span>
                </div>
                <div class="row">
                    <label class="label">SETOR:</label>
                    <span class="conteudo">
                        <input type="text" readonly class="FUNDOCAIXA1" id="UNIDADE_DETALHAR_RESOLVER_DEMANDA">
                    </span>
                </div>
                <div class="row">
                    <label class="label">EMAIL:</label>
                    <span class="conteudo">
                        <input type="text" readonly class="FUNDOCAIXA1" id="EMAIL_DETALHAR_RESOLVER_DEMANDA">
                    </span>
                </div>
                <div class="row">
                    <label class="label">SKYPE:</label>
                    <span class="conteudo">
                        <input type="text" readonly class="FUNDOCAIXA1" id="SKYPE_DETALHAR_RESOLVER_DEMANDA">
                    </span>
                </div>
                <div class="row">
                    <label class="label">TELEFONE:</label>
                    <span class="conteudo">
                        <input type="text" readonly class="FUNDOCAIXA1" id="TELEFONE_DETALHAR_RESOLVER_DEMANDA">
                    </span>
                </div>
            </fieldset>

            <fieldset>
                <div class="row">
                    <label class="label">COMENTÁRIO ATENDENTE:</label>
                    <span class="conteudo">
                        <textarea onkeyup="DigitaLetraSeguro(this);" cols="72" rows="3" class="FUNDOCAIXA1" id="COMENTARIO_ATENDENTE_DETALHAR_RESOLVER_DEMANDA"></textarea>
                    </span>
                </div>
            </fieldset>

        </div>