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
    var total_documentos;

    /*Funcoes*/
    function jquery_cancelar_tramite_documentos(digital) {
        try {
            var c = confirm('Você tem certeza que deseja cancelar o tramite deste(s) documento(s)?\n[' + digital + ']\nObs:Todos documentos vinculados terão o seu trâmite cancelado!');
            if (c) {
                $("#progressbar").show();
                $.post("modelos/documentos/tramite.php", {
                    acao: 'cancelar',
                    digitais: digital.toString()
                },
                function(data) {
                    if (data.success == 'true') {
                        oTableDocumentos.fnDraw(false);
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
        oTableDocumentos = $('#TabelaDocumentos').dataTable({
            aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: true,
            bProcessing: false,
            bServerSide: true,
            bJQueryUI: true,
            sPaginationType: "full_numbers",
            sAjaxSource: "modelos/documentos/listar_documentos_caixa_saida.php",
            oLanguage: {
                sProcessing: "Carregando...",
                sLengthMenu: "_MENU_ por página",
                sZeroRecords: "Nenhum documento encontrado.",
                sInfo: "_START_ a _END_ de _TOTAL_ documentos",
                sInfoEmpty: "Não foi possível localizar documentos com o parametros informados!",
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
                        jquery_datatable_complementa_mensagem_vazia('TabelaDocumentos');
                    }
                });
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                total_documentos = (iDisplayIndex + 1);

                $('td:eq(0)', nRow).html('<input type="checkbox" id="DIGITAL[' + iDisplayIndex + ']" value="' + aData[3] + '">');
                var $line = $('td:eq(10)', nRow);
                $line.html('<div title="">');

                /* Flags de Prazo e Vinculacao - Inicio */
                if (aData[1] != '') {
                    $('td:eq(1)', nRow).html('<div onclick=jquery_listar_vinculacao_documento("' + aData[3] + '"); class="flag-possui-relacao" title="Este documento possui relacao com outros documentos."></div>');
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

                /* Converter formato Date para String (dd/mm/aaaa) */
                $('td:eq(4)', nRow).html(convertDateToString(aData[4]));

                $('td:eq(5)', nRow).html(aData[5]);

                if (aData[7] == '') {
                    $('td:eq(7)', nRow).html('<div title=""></div>');
                }

                if (aData[8] != '') {
                    $('td:eq(8)', nRow).html(aData[8]);
                } else {
                    $('td:eq(8)', nRow).html('<div title=""></div>');
                }

                if (aData[9] == '') {
                    $('td:eq(9)', nRow).html('<div title=""></div>');
                }

<?php
// verifica a existencia da permissao para cancelar recebimento de tramite
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(401))) {
    ?>
                    // Autuar
                    $("<img/>", {
                        src: 'imagens/cancelar.png',
                        title: 'Cancelar Trâmite',
                        'class': 'botao30'
                    }).bind("click", function() {
                        jquery_cancelar_tramite_documentos(aData[3]);
                    }).appendTo($line);

    <?php
}
// verifica a existencia da permissao para visualizar anexos/apensos
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(3101))) {
    ?>
                    // Anexos/Apensos
                    $("<img/>", {
                        src: 'imagens/lista_anexos.png',
                        title: 'Anexos/Apensos',
                        'class': 'botao30'
                    }).bind("click", function() {
                        jquery_listar_vinculacao_documento(aData[3], false);
                    }).appendTo($line);

    <?php
}
// verifica a existencia da permissao para detalhar documentos
if (AclFactory::checaPermissao(Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(3102))) {
    ?>
                    // Alterar
                    $("<img/>", {
                        src: 'imagens/alterar.png',
                        title: 'Detalhar',
                        'class': 'botao30'
                    }).bind("click", function() {
                        jquery_detalhar_documento(aData[3]);
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
                    visualizar_imagens_documento(aData[3]);
                }).appendTo($line);

                $("</div>").appendTo($line);
                return nRow;
            },
            fnDrawCallback: function(oSettings, nRow) {
            },
            aoColumnDefs: [
                {bSortable: false, aTargets: [0, 9, 10]}
            ]
        });
    });
</script>      

<table class="display" border="0" id="TabelaDocumentos">
    <thead>
        <tr>
            <th class="column-checkbox" align="center"><input type="checkbox" id="marcadorD" onChange="marcar_todos_documentos(total_documentos);"></th>
            <th title="Anexos/Apensos" class="style13 column-checkbox"></th>
            <th title="Prazos" class="style13 column-checkbox"></th>
            <th class="style13 column-digital">Digital</th>
            <th class="style13 column-numero">Cadastro</th>
            <th class="style13 column-assunto">Assunto</th>
            <th class="style13 column-numero">Número</th>
            <th class="style13 column-tipo">Tipo</th>
            <th class="style13 column-origem">Origem</th>
            <th class="style13 column-movimentacao">Movimentação</th>
            <th class="style13 column-opcao-5">Opções</th>
        </tr>
    </thead>
</table>