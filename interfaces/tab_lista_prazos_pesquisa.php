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

    var oTablePesquisaPrazos = null;

    $(document).ready(function() {
        $('#tabs-prazos-pesq').click(function() {
            oTablePesquisaPrazos.fnDraw(false);
        });

        /*DataTable*/
        oTablePesquisaPrazos = $('#tabela_prazos_pesquisa').dataTable({
            aoColumnDefs: [
                {"bVisible": false, "aTargets": [11, 12]}
            ],
            aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bAutoWidth: false,
            //sScrollY: '75%',
            bStateSave: false,
            //sScrollX: "100%",
            //sScrollXInner: "100%",
            bPaginate: true,
            bProcessing: true,
            bServerSide: true,
            bJQueryUI: true,
            sPaginationType: "full_numbers",
            sAjaxSource: "modelos/prazos/pesquisar_prazos.php",
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
                    "sFirst": "Primeiro",
                    "sPrevious": "Anterior",
                    "sNext": "Próximo",
                    "sLast": "Ultimo"
                }
            },
            fnServerData: function(sSource, aoData, fnCallback) {
                $.getJSON(sSource, aoData, function(json) {
                    fnCallback(json);
                    if (json.iTotalRecords == 0) {
                        jquery_datatable_complementa_mensagem_vazia('tabela_prazos_pesquisa');
                    }
                });
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                if (aData[2].length == 7) {
                    $('td:eq(2)', nRow).html('<span title="Detalhar Documento" onclick=jquery_detalhar_documento("' + aData[2] + '");>' + aData[2] + '</span>');
                }

                var dias_restantes = 10;
                var dias_resposta = 11;
                var id_usuario_resposta = 12;
                //Se não tiver sido respondido
                if (aData[id_usuario_resposta] == '') {
                    if (aData[dias_restantes] == 0) {
                        $('td:eq(10)', nRow).html('<blink>Atenção! Esgota hoje!</blink>');
                    }
                    if (aData[dias_restantes] > 0) {
                        $('td:eq(10)', nRow).html('<blink>Atenção! Falta(m) ' + (aData[dias_restantes]) + ' dia(s)</blink>');
                    }
                    if (aData[dias_restantes] < 0) {
                        $('td:eq(10)', nRow).html('<blink>Atenção! Esgotado há ' + (-1 * aData[dias_restantes]) + ' dia(s)<blink>');
                    }
                    $('td', nRow).removeClass('vermelho');
                    $('td', nRow).removeClass('laranja');
                    $('td', nRow).removeClass('verde');
                    $('td', nRow).removeClass('branco');
                    if (aData[dias_restantes] <= 0) {
                        $('td', nRow).addClass('vermelho');
                    }
                    else if (aData[dias_restantes] <= 7) {
                        $('td', nRow).addClass('laranja');
                    }
                    else if (aData[dias_restantes] <= 15) {
                        $('td', nRow).addClass('verde');
                    }
                    else {
                        $('td', nRow).addClass('branco');
                    }

                } else {//Se já tiver sido respondido
                    //Se Qtd de Dias a partir da Resposta for valor positivo
                    if (aData[dias_resposta] == 0) {
                        $('td:eq(10)', nRow).html('Respondido no dia de vencimento do prazo.');
                    }
                    if (aData[dias_resposta] > 0) {
                        $('td:eq(10)', nRow).html('Respondido ' + aData[dias_resposta] + ' dia(s) antes do vencimento.');
                    }
                    if (aData[dias_resposta] < 0) {
                        $('td:eq(10)', nRow).html('Respondido com atraso de ' + (-1 * aData[dias_resposta]) + ' dia(s)');
                    }
                }//if( aData[id_usuario_resposta] == '' )

                return nRow;
            }
        });

    });

</script>      

<table class="display" border="0" id="tabela_prazos_pesquisa">
    <thead>
        <tr>
            <th class="style13">#</th>
            <th class="style13">Assunto</th>
            <th class="style13">Digital</th>
            <th class="style13">Tipo</th>
            <th class="style13">Destino</th>
            <th class="style13">Solicitação</th>
            <th class="style13">Resposta</th>
            <th class="style13">Prazo</th>
            <th class="style13">Remetente</th>
            <th class="style13">Interessado</th>
            <th class="style13">Situação</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="5" title="test" class="dataTables_empty">Nenhum documento localizado!</td>
        </tr>
    </tbody>
</table>
