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

    var oTableProcessos;
    var total_processos;

    /*Funcoes*/
    function jquery_resgatar_processos(numero_processo) {
        try {
            var c = confirm('Você tem certeza que deseja resgatar este(s) processo(s) para a sua área de trabalho?\n[' + numero_processo + ']\nObs:Todos processos vinculados tambem serao resgatados!');
            if (c) {
                $("#progressbar").show();
                $.post("modelos/processos/tramite.php", {
                    acao: 'resgatar',
                    processos: numero_processo.toString()
                },
                function(data) {
                    if (data.success == 'true') {
                        oTableProcessos.fnDraw(false);
                        $("#progressbar").hide();
                        alert(data.message);
                    } else {
                        $("#progressbar").hide();
                        alert(data.error);
                    }
                }, "json");
            }
        } catch (e) {
            alert('Ocorreu um erro:\n[' + e + ']');
        }
    }

    $(document).ready(function() {
        /*DataTable*/
        oTableProcessos = $('#TabelaProcessos').dataTable({
            aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: true,
            bProcessing: false,
            bServerSide: true,
            bJQueryUI: true,
            bDeferRender: true,
            sPaginationType: "full_numbers",
            sAjaxSource: "modelos/processos/listar_processos_caixa_externos.php",
            oLanguage: {
                sProcessing: "Carregando...",
                sLengthMenu: "_MENU_ por página",
                sZeroRecords: "Nenhum processo encontrado.",
                sInfo: "_START_ a _END_ de _TOTAL_ processos",
                sInfoEmpty: "Nao foi possivel localizar processos com o parametros informados!",
                sInfoFiltered: "",
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
                        jquery_datatable_complementa_mensagem_vazia('TabelaProcessos');
                    }
                });
            },
            fnDrawCallback: function(oSettings, nRow) {

            },
            aoColumnDefs: [
                {bSortable: false, "aTargets": [0, 7, 8]}
            ],
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                total_processos = (iDisplayIndex + 1);
                /* Opcoes */
                $('td:eq(0)', nRow).html('<input type="checkbox" id="NUMERO_PROCESSO[' + iDisplayIndex + ']" value="' + aData[3] + '">');
                var $line = $('td:eq(8)', nRow);
                $line.html('<div title="">');

                /* Flags de Prazo e Vinculacao - Inicio */
                if (aData[1] != '') {
                    $('td:eq(1)', nRow).html('<div onClick=jquery_listar_vinculacao_processo("' + aData[3] + '"); class="flag-possui-relacao" title="Este documento possui relacao com outros documentos."></div>');
                } else {
                    $('td:eq(1)', nRow).html('<div title=""></div>');
                }

                if (aData[2] != '') {
                    if (aData[2] == 1 || aData[2] == 2) {
                        $('td:eq(2)', nRow).html('<div class="flag-prazo-vermelho" title="Atencao! Falta(m)' + (aData[2]) + ' dia(s)"></div>');
                    } else if (aData[2] <= 0) {
                        if (aData[2] == 0) {
                            $('td:eq(2)', nRow).html('<div class="flag-prazo-vermelho" title="Atencao! Esgota hoje."></div>');
                        } else {
                            $('td:eq(2)', nRow).html('<div class="flag-prazo-vermelho" title="Atencao! Esgotado a ' + (-1 * aData[2]) + ' dia(s)"></div>');
                        }
                    } else if (aData[2] <= 7) {
                        $('td:eq(2)', nRow).html('<div class="flag-prazo-amarelo" title="Falta(m) ' + (aData[2]) + ' dia(s)"></div>');
                    } else if (aData[2] <= 15) {
                        $('td:eq(2)', nRow).html('<div class="flag-prazo-verde" title="Falta(m) ' + (aData[2]) + ' dia(s)"></div>');
                    } else if (aData[2]) {
                        $('td:eq(2)', nRow).html('<div class="flag-prazo-verde" title="Falta(m) ' + (aData[2]) + ' dia(s)"></div>');
                    }
                } else {
                    $('td:eq(2)', nRow).html('<div title=""></div>');
                }

<?php
// verifica a existencia da permissao para retornar processos externos
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(502))) {
    ?>
                    // Resgatar
                    $("<img/>", {
                        src: 'imagens/voltar.png',
                        title: 'Retornar Documento',
                        'class': 'botao30'
                    }).bind("click", function() {
                        jquery_resgatar_processos(aData[3]);
                    }).appendTo($line);

    <?php
}
// verifica a existencia da permissao para visualizar anexos/apensos
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(3111))) {
    ?>
                    // Anexos/Apensos
                    $("<img/>", {
                        src: 'imagens/lista_anexos.png',
                        title: 'Peças/Vínculos',
                        'class': 'botao30'
                    }).bind("click", function() {
                        jquery_listar_vinculacao_processo(aData[3], false);
                    }).appendTo($line);

    <?php
}
// verifica a existencia da permissao para detalhar processos
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(3112))) {
    ?>
                    // Alterar
                    $("<img/>", {
                        src: 'imagens/alterar.png',
                        title: 'Detalhar',
                        'class': 'botao30'
                    }).bind("click", function() {
                        jquery_detalhar_processo(aData[3], area);
                    }).appendTo($line);

    <?php
}
?>

                // Visualizar Imagens
                $("<img/>", {
                    src: 'imagens/visualizar.png',
                    title: 'Visualizar Imagem',
                    'class': 'botao30'
                }).bind("click", function() {
                    visualizar_imagens_processo(aData[3]);
                }).appendTo($line);

                $("</div>").appendTo($line);
                return nRow;
            },
            fnDrawCallback: function(oSettings, nRow) {
            },
                    aoColumnDefs: [
                {bSortable: false, aTargets: [0, 7, 8]}
            ]
        });
    });
</script>      

<table class="display" border="0" id="TabelaProcessos">
    <thead>
        <tr>
            <th class="style13 column-checkbox"><input type="checkbox" id="marcadorP" onChange="marcar_todos_processos();"></th>
            <th title="Anexos/Apensos" class="style13 column-checkbox"></th>
            <th title="Prazos" class="style13 column-checkbox"></th>
            <th class="style13 column-numero-processo">Processo</th>
            <th class="style13 column-interessado">Interessado</th>
            <th class="style13 column-assunto">Assunto</th>
            <th class="style13 column-data">Origem</th>
            <th class="style13 column-movimentacao">Movimentação</th>
            <th class="style13 column-opcao-2">Opções</th>
        </tr>
    </thead>
</table>