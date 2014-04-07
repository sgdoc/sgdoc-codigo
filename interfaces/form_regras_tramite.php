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
    var oTableRegras;
    var aUnidadeRegras;
    var iUnidadeAtiva;

    $(document).ready(function() {
        /*DataTable*/
        oTableRegras = $('#tabela_regras').dataTable({
            aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: true,
            bProcessing: true,
            bServerSide: true,
            sScrollY: "300px",
            bJQueryUI: true,
            sPaginationType: "full_numbers",
            sAjaxSource: "modelos/unidades/listar_unidades_regras_tramite.php",
            oLanguage: {
                sProcessing: "Carregando...",
                sLengthMenu: "_MENU_ por página",
                sZeroRecords: "Nenhum unidade encontrado.",
                sInfo: "_START_ a _END_ de _TOTAL_ unidades.",
                sInfoEmpty: "Nao foi possivel localizar unidades com os parametros informados!",
                sInfoFiltered: "(Total _MAX_ unidades)",
                sInfoPostFix: "",
                sSearch: "Pesquisar:",
                oPaginate: {
                    sFirst: "Primeiro",
                    sPrevious: "Anterior",
                    sNext: "Próximo",
                    sLast: "Ultimo"
                }
            },
            aoColumnDefs: [
                {bSortable: false, aTargets: [3]}
            ],
            fnServerData: function(sSource, aoData, fnCallback) {
                $.getJSON(sSource, aoData, function(json) {
                    fnCallback(json);
                });
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {

                var line = $('td:eq(3)', nRow);
                line.html('');

                var ativo = $("<input/>", {
                    type: 'checkbox',
                    title: 'Ativar/Desativar'
                }).bind("change", function() {
                    jquery_alterar_regra_unidade(aData[0], (this.checked ? 1 : 0));
                }).appendTo(line);

                if (aUnidadeRegras) {
                    if (aUnidadeRegras.indexOf(parseInt(aData[0])) > -1) {
                        ativo.attr('checked', 'checked');
                    }
                }

                return nRow;
            }
        });

        /*Regras de Tramite*/
        $('#box-detalhar-regras-tramite').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 700,
            height: 500
        });
    });

    /*Functions*/
    function jquery_alterar_regra_unidade(referencia, status) {
        $('#progressbar').show();
        $.post("modelos/unidades/unidades.php",
                {
                    acao: 'alterar-visibilidade',
                    id_unidade: iUnidadeAtiva,
                    id_referencia: referencia,
                    status: parseInt(status)
                },
        function(data) {
            $('#progressbar').hide();
            if (data.success) {
                jquery_carregar_regras_unidade(iUnidadeAtiva);
                alert(data.message);
            } else {
                alert('Ocorreu um erro ao tentar alterar a regra de tramite da unidade!');
            }
        }, "json");
    }

    function jquery_carregar_regras_unidade(id) {
        $.post("modelos/unidades/unidades.php",
                {
                    acao: 'regras-tramite',
                    id_unidade: id
                },
        function(data) {
            if (data.success == 'true') {
                data = eval(data);
                aUnidadeRegras = data.roles;
            } else {
                alert('Ocorreu um erro ao tentar carragar as regras desta unidade!');
            }
        }, "json");
    }

    function jquery_detalhar_regras_tramite(id) {
        jquery_carregar_regras_unidade(id);
        iUnidadeAtiva = id;
        oTableRegras.fnDraw(false);
        $('#box-detalhar-regras-tramite').dialog('open');
    }

</script>

<!--Regras de Tramite-->
<div id="box-detalhar-regras-tramite" class="div-form-dialog" title="Regras de Trâmite">
    <table class="display" border="0" id="tabela_regras">
        <thead>
            <tr>
                <th class="style13">#</th>
                <th class="style13">Nome</th>
                <th class="style13">Sigla</th>
                <th class="style13">Permissão</th>
            </tr>
        </thead>
    </table>
</div>