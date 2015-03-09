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

    var oTableDemandasHistorico;

    $(document).ready(function() {
        /*DataTable*/
        oTableDemandasHistorico = $('#grid_demandas_historico').dataTable({
            aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: true,
            bProcessing: true,
            bServerSide: true,
            bJQueryUI: true,
            sPaginationType: "full_numbers",
            sAjaxSource: "modelos/suporte/listar.php?acao=historico-de-demandas",
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

                /*Formatar datas*/
                $('td:eq(2)', nRow).html(datetimeToPtBrFormat(aData[2]));
                $('td:eq(10)', nRow).html(datetimeToPtBrFormat(aData[10]));
                $('td:eq(12)', nRow).html(datetimeToPtBrFormat(aData[12]));

                var titles = {
                    0:'Péssimo',
                    1:'Muito Ruim',
                    2:'Ruim',
                    3:'Bom',
                    4:'Ótimo',
                    5:'Excelente'
                };

                $('td:eq(14)', nRow).html(titles[aData[14]]);

                return nRow;
            }
        });
    });
    
</script>      

    <table class="display" border="0" id="grid_demandas_historico">
        <thead>
            <tr>
                <th class="style13">#</th>
                <th class="style13">Protocolo</th>
                <th class="style13">Abertura</th>
                <th class="style13">Usuário</th>
                <th class="style13">Telefone</th>
                <th class="style13">Setor</th>
                <th class="style13">Assunto</th>
                <th class="style13">Descrição</th>
                <th class="style13">Comentário</th>
                <th class="style13">Triado por</th>
                <th class="style13">Triagem</th>
                <th class="style13">Atendente</th>
                <th class="style13">Finalização</th>
                <th class="style13">Situação</th>
                <th class="style13">Qualificacao</th>
            </tr>
        </thead>
    </table>