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

$oUsuario = Controlador::getInstance()->usuario;
?>
<script type="text/javascript">

    var oTableRecebidosSetor = null;

    $(document).ready(function() {
        $('#tabs-prazos-rec-set').click(function() {
            oTableRecebidosSetor.fnDraw(false);
        });

        var unidadeDoUsuario = <?php echo $oUsuario->ID_UNIDADE_ORIGINAL; ?>;

        /*DataTable*/
        oTableRecebidosSetor = $('#tabela_prazos_recebidos_setor').dataTable({
            aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            aoColumnDefs: [
                {"bVisible": false, "aTargets": [9]}
            ],
            bAutoWidth: false,
            //sScrollY: '75%',
            //sScrollX: "100%",
            //sScrollXInner: "100%",
            bStateSave: false,
            bPaginate: true,
            bProcessing: true,
            bServerSide: true,
            bJQueryUI: true,
            sPaginationType: "full_numbers",
            sAjaxSource: "modelos/prazos/listar_prazos_recebidos_setor.php",
            oLanguage: {
                sProcessing: "Carregando...",
                sLengthMenu: "_MENU_ por página",
                sZeroRecords: "Nenhum registro encontrado.",
                sInfo: "_START_ a _END_ de _TOTAL_ registros",
                sInfoEmpty: "Nao foi possivel localizar registros com o parametros informados!",
                sInfoFiltered: "(Total _MAX_ registros)",
                sInfoPostFix: "",
                sSearch: "Pesquisar:",
                oPaginate: {
                    sFirst: "Primeiro",
                    sPrevious: "Anterior",
                    sNext: "Próximo",
                    sLast: "Ultimo"
                }
            },
            fnServerData: function(sSource, aoData, fnCallback) {
                $.getJSON(sSource, aoData, function(json) {
                    fnCallback(json);
                    if (json.iTotalRecords == 0) {
                        jquery_datatable_complementa_mensagem_vazia('tabela_prazos_recebidos_setor');
                    }
                });
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                /* Append the grade to the default row class name */
                nRow.setAttribute('title', aData[4]);
                nRow.setAttribute('id', 'tr' + aData[0]);

                var unidadePermitida = aData[10];
                if (unidadeDoUsuario == unidadePermitida) {
                    $('td:eq(9)', nRow).html('<img title="Responder" src="imagens/responder_prazo.png" class="botao32" onclick="responderPrazo(' + aData[0] + ', \'prazos\');" />');
                } else {
                    $('td:eq(9)', nRow).html('<img title="Responder" src="imagens/responder_prazo_desabilitado.png" class="botao32" onclick="__emiteMsgRespostaDesabilitada();" />');
                }

                if (aData[1].length == 7) {
                    $('td:eq(1)', nRow).html('<span title="Detalhar Documento" onclick=jquery_detalhar_documento("' + aData[1] + '");>' + aData[1] + '</span>');
                }
                if (aData[2].length == 7) {
                    $('td:eq(2)', nRow).html('<span title="Detalhar Documento" onclick=jquery_detalhar_documento("' + aData[2] + '");>' + aData[2] + '</span>');
                }
                
                 if (aData[1].length == 20 || aData[1].length == 18) {
                    $('td:eq(1)', nRow).html('<span title="Detalhar Processo" onclick=jquery_detalhar_processo("' + aData[1] + '","' + area + '");>' + aData[1] + '</span>');
                }
                if (aData[1] == '') {
                    $('td:eq(1)', nRow).html('<div></div>');
                }
                
                if (aData[2].length == 20 || aData[2].length == 18) {
                    $('td:eq(2)', nRow).html('<span title="Detalhar Processo" onclick=jquery_detalhar_processo("' + aData[2] + '","' + area + '");>' + aData[2] + '</span>');
                }
                if (aData[2] == '') {
                    $('td:eq(2)', nRow).html('<div></div>');
                }

                $('td:eq(6)', nRow).html('<img title="Detalhar Solicitação" src="imagens/alterar.png" class=botao32 onclick=detalharSolicitacao(' + aData[0] + ');>');

                if (aData[8] == 1 || aData[8] == 2) {
                    $('td', nRow).removeClass('vermelho');
                    $('td', nRow).addClass('vermelho');
                    $('td:eq(8)', nRow).html('<blink>Atencao! Falta(m) ' + (aData[8]) + ' dia(s)</blink>');
                } else if (aData[8] <= 0) {
                    $('td', nRow).removeClass('vermelho');
                    $('td', nRow).addClass('vermelho');
                    if (aData[8] == 0) {
                        $('td:eq(8)', nRow).html('<blink>Atencao! Esgota hoje!</blink>');
                    } else {
                        $('td:eq(8)', nRow).html('<blink>Atencao! Esgotado a ' + (-1 * aData[8]) + ' dia(s)<blink>');
                    }
                } else if (aData[8] <= 7) {
                    $('td', nRow).removeClass('laranja');
                    $('td', nRow).addClass('laranja');
                    $('td:eq(8)', nRow).html((aData[7]) + ' dia(s)');
                } else if (aData[8] <= 15) {
                    $('td', nRow).removeClass('verde');
                    $('td', nRow).addClass('verde');
                    $('td:eq(8)', nRow).html((aData[8]) + ' dia(s)');
                } else if (aData[8]) {
                    $('td', nRow).removeClass('branco');
                    $('td', nRow).addClass('branco');
                    $('td:eq(8)', nRow).html((aData[8]) + ' dia(s)');
                }
                return nRow;
            }
        });
    });

    function __emiteMsgRespostaDesabilitada() {
        alert('Usuário logado não possui permissão para Responder');
    }
</script>
      
<table class="display" border="0" id="tabela_prazos_recebidos_setor">
    <thead>
        <tr>
            <th class="style13">#</th>
            <th class="style13">D. Principal</th>
            <th class="style13">N. Referência</th>
            <th class="style13">Interessado</th>
            <th class="style13">Remetente</th>
            <th class="style13">Setor Remetente</th>
            <th class="style13">Solicitação</th>
            <th class="style13">Prazo</th>
            <th class="style13">Dias Restantes</th>
            <th class="style13">Responder</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="5" title="test" class="dataTables_empty">Você não possui prazos para resposta!</td>
        </tr>
    </tbody>
</table>
