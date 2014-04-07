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

    var oTableDocumentos;

    $(document).ready(function() {
        /*DataTable*/
        oTableDocumentos = $('#tabela_documentos').dataTable({
            aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: true,
            bProcessing: false,
            bServerSide: true,
            bJQueryUI: true,
            sPaginationType: "full_numbers",
            sAjaxSource: "modelos/caixas/listar_documentos_fora.php",
            aoColumnDefs: [{bSortable: false, aTargets: [5]}],
            aoColumns: [{"sWidth": "5%"},
                {"sWidth": "7%"},
                {"sWidth": "8%"},
                {"sWidth": "30%"},
                {"sWidth": "42%"},
                {"sWidth": "6%"}
            ],
            oLanguage: {
                sProcessing: "Carregando...",
                sLengthMenu: "_MENU_ por página",
                sZeroRecords: "Nenhum documento encontrado.",
                sInfo: "_START_ a _END_ de _TOTAL_ documentos.",
                sInfoEmpty: "Nao foi possivel localizar documentos com os parametros informados!",
                sInfoFiltered: "(Total _MAX_ documentos)",
                sInfoPostFix: "",
                sSearch: "Pesquisar:",
                oPaginate: {
                    sFirst: "Primeira",
                    sPrevious: "Anterior",
                    sNext: "Proxima",
                    sLast: "Ultima"
                }
            },
            fnServerData: function(sSource, aoData, fnCallback) {
                $.getJSON(sSource, aoData, function(json) {
                    fnCallback(json);
                });
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                nRow.setAttribute('title', aData[1]);

                var $line = $('td:eq(5)', nRow);
                $line.html('<div title="">');

                if (aData[2] == '') {
                    $('td:eq(2)', nRow).html('<div title=""></div>');
                }

<?php
// verifica a existencia da permissao para alterar uma classificacao de documento
if (array_key_exists('10811', Controlador::getInstance()->recurso->dependencias)) {
    ?>
                    // Alterar
                    $("<img/>", {
                        src: 'imagens/alterar.png',
                        title: 'Alterar Classificação',
                        'class': 'botao32'
                    }).bind("click", function() {
                        jquery_detalhar_documentos(aData[0], aData[5]);
                    }).appendTo($line);

    <?php
}
?>
                $("</div>").appendTo($line);

                return nRow;
            }
        });
    });
</script>      

<table class="display" border="0" id="tabela_documentos">
    <thead>
        <tr>
            <th class="style13">#</th>
            <th class="style13">Digital</th>
            <th class="style13">Número do Processo</th>
            <th class="style13">Classificação</th>
            <th class="style13">Assunto</th>
            <th class="style13">Opções</th>
        </tr>
    </thead>
</table>
