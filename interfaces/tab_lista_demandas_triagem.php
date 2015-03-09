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

    var oTableDemandasTriagem;

    $(document).ready(function() {
        /*DataTable*/
        oTableDemandasTriagem = $('#grid_demandas_triagem').dataTable({
            aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: true,
            bProcessing: true,
            bServerSide: true,
            bJQueryUI: true,
            sPaginationType: "full_numbers",
            sAjaxSource: "modelos/suporte/listar.php?acao=demandas-triagem",
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
                var $check = $('td:eq(0)', nRow);
                $line.html('');
                $check.html('');

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

                /*Checkbox*/
                $("<input/>", {
                    value: aData[0],
                    type: 'checkbox',
                    'class': 'CHECK_TRIAGEM_DEMANDAS'
                }).appendTo($check);

                /*Resolver demandas*/
                $("<img/>", {
                    src: 'imagens/encaminhar_demanda.png',
                    title: 'Encaminhar demanda',
                    'class': 'botao32'
                }).bind( "click", function(){
                    jquery_detalhar_encaminhar_demanda(aData[0]);
                }).appendTo($line);

                return nRow;
            }
        });
        
        /*Encaminhar Demanda*/
        $('#box-detalhar-encaminhar-demanda').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 650,
            autoHeight: true,
            buttons: {
                Encaminhar: function() {
                    if(confirm('Você certeza que deseja encaminhar esta demanda?')){
                        $('#progressbar').show();
                        $.post("modelos/suporte/suporte.php", {
                            acao: 'encaminhar-demanda',
                            demanda: $('#ID_DETALHAR_ENCAMINHAR_DEMANDA').val(),
                            comentario: $('#COMENTARIO_ENCAMINHAR_DEMANDA').val(),                             
                            atendente: $('#ATENDENTE_ENCAMINHAR_DEMANDA').val()                                       
                        },
                        function(data){
                            if(data.success == 'true'){
                                $('#box-detalhar-encaminhar-demanda').dialog("close");
                                oTableDemandasTriagem.fnDraw(false);
                                alert(data.message);
                                $('#progressbar').hide();
                            }else{
                                alert('Ocorreu um erro ao tentar encaminhar a demanda!\n['+data.error+']');
                                $('#progressbar').hide();
                            }
                        }, "json");
                    }
                }
            }
        });

        $('#ATENDENTE_ENCAMINHAR_DEMANDA').combobox('modelos/combos/suporte.php?tipo=atendentes', {
            tipo: 'atendentes'
        });

    });
    
    /*Encaminhar demanda*/
    function jquery_detalhar_encaminhar_demanda(id){
        $.post("modelos/suporte/suporte.php", {
            acao: 'detalhar-demanda',
            demanda: id
        },
        function(data){
            if(data.success == 'true'){

                if(data.demanda.tx_comentario){
                    $('#fieldset-comentario-atendente').show();
                }else{
                    $('#fieldset-comentario-atendente').hide();
                }

                $('#ID_DETALHAR_ENCAMINHAR_DEMANDA').val(data.demanda.id);
                $('#PROTOCOLO_DETALHAR_ENCAMINHAR_DEMANDA').val(data.demanda.cd_protocolo);
                $('#DATA_ABERTURA_DETALHAR_ENCAMINHAR_DEMANDA').val(datetimeToPtBrFormat(""+data.demanda.dt_abertura+""));
                $('#ASSUNTO_DETALHAR_ENCAMINHAR_DEMANDA').val(data.demanda.tx_assunto);
                $('#DESCRICAO_DETALHAR_ENCAMINHAR_DEMANDA').val(data.demanda.tx_descricao);
                $('#NOME_ATENDENTE_DETALHAR_ENCAMINHAR_DEMANDA').val(data.demanda.nm_atendente);
                $('#COMENTARIO_ATENDENTE_DETALHAR_ENCAMINHAR_DEMANDA').val(data.demanda.tx_comentario);
                $('#USUARIO_DETALHAR_ENCAMINHAR_DEMANDA').val(data.demanda.nm_usuario);
                $('#UNIDADE_DETALHAR_ENCAMINHAR_DEMANDA').val(data.demanda.nm_unidade);
                $('#EMAIL_DETALHAR_ENCAMINHAR_DEMANDA').val(data.demanda.tx_email);
                $('#SKYPE_DETALHAR_ENCAMINHAR_DEMANDA').val(data.demanda.tx_skype);
                $('#TELEFONE_DETALHAR_ENCAMINHAR_DEMANDA').val(data.demanda.nu_telefone);
                $('#box-detalhar-encaminhar-demanda').dialog('open');
            }else{
                alert('Ocorreu um erro ao tentar detalhar as informacoes da demanda!');
            }
        }, "json");

    }

</script>      

    <table class="display" border="0" id="grid_demandas_triagem">
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

    <div id="box-detalhar-encaminhar-demanda" class="div-form-dialog" title="Encaminhar demanda">
        <fieldset>
            <input class="FUNDOCAIXA1" id="ID_DETALHAR_ENCAMINHAR_DEMANDA" type="hidden">
            <div class="row">
                <label class="label">PROTOCOLO:</label>
                <span class="conteudo">
                    <input type="text" readonly class="FUNDOCAIXA1" id="PROTOCOLO_DETALHAR_ENCAMINHAR_DEMANDA">
                </span>
            </div>
            <div class="row">
                <label class="label">DATA ABERTURA:</label>
                <span class="conteudo">
                    <input type="text" readonly class="FUNDOCAIXA1" id="DATA_ABERTURA_DETALHAR_ENCAMINHAR_DEMANDA">
                </span>
            </div>
            <div class="row">
                <label class="label">ASSUNTO:</label>
                <span class="conteudo">
                    <input type="text" readonly class="FUNDOCAIXA1" id="ASSUNTO_DETALHAR_ENCAMINHAR_DEMANDA">
                </span>
            </div>
            <div class="row">
                <label class="label">DESCRIÇÃO:</label>
                <span class="conteudo">
                    <textarea cols="72" rows="3" readonly class="FUNDOCAIXA1" id="DESCRICAO_DETALHAR_ENCAMINHAR_DEMANDA"></textarea>
                </span>
            </div>
        </fieldset>

        <fieldset id="fieldset-comentario-atendente">
            <div class="row">
                <label class="label">ATENDENTE:</label>
                <span class="conteudo">
                    <input type="text" readonly class="FUNDOCAIXA1" id="NOME_ATENDENTE_DETALHAR_ENCAMINHAR_DEMANDA">
                </span>
            </div>

            <div class="row">
                <label class="label">COMENTÁRIO ATENDENTE:</label>
                <span class="conteudo">
                    <textarea cols="72" rows="3" readonly class="FUNDOCAIXA1" id="COMENTARIO_ATENDENTE_DETALHAR_ENCAMINHAR_DEMANDA"></textarea>
                </span>
            </div>
        </fieldset>

        <fieldset>
            <div class="row">
                <label class="label">ATENDENTE:</label>
                <span class="conteudo">
                    <select class="FUNDOCAIXA1" id="ATENDENTE_ENCAMINHAR_DEMANDA"></select>
                </span>
            </div>
            <div class="row">
                <label class="label">COMENTÁRIO TRIAGEM:</label>
                <span class="conteudo">
                    <textarea onkeyup="DigitaLetraSeguro(this);" cols="72" rows="3" class="FUNDOCAIXA1" id="COMENTARIO_ENCAMINHAR_DEMANDA"></textarea>
                </span>
            </div>
        </fieldset>

    </div>
