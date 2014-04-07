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

$controller = Controlador::getInstance();
$auth = $controller->usuario;

$file = 'manter_caixas_documentos.php';
$name_recurso = str_replace('.', '_', $file);
if (!($controller->cache->test('recurso_' . $name_recurso))) {
    $recurso = DaoRecurso::getRecursoByUrl($file);
    if (isset($recurso->id)) {
        $controller->cache->save($recurso, 'recurso_' . $name_recurso, array('recurso_' . $recurso->id, 'paginas'));
    } else {
        $recurso = null;
    }
} else {
    $recurso = $controller->cache->load('recurso_' . $name_recurso);
}

$controller->setContexto(null);
$botoes = Util::getMenus($auth, $recurso, $controller->acl);
foreach ($recurso->dependencias as $arquivo) {
    include('interfaces/' . $arquivo);
}
?>

<script type="text/javascript">

    var oTableCaixasDocs;

    $(document).ready(function() {

        /*Dialog*/

        $('#box-listar-caixa').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: '90%',
            height: 620,
            position: ['center', 100],
            open: function(event, ui) {
                var id = $("#ID_LISTAR_CAIXA").val();
                jquery_iniciar_datatables(id);
            },
            close: function(event, ui) {
                oTableCaixasDocs.fnDestroy();
                oTableCaixas.fnDraw(false);
            }
        });
    });

    /*Functions*/
    function jquery_listar_documentos_caixa(id) {
        $("#ID_LISTAR_CAIXA").val(id);
        $("#box-listar-caixa").dialog('open');
    }

    function jquery_iniciar_datatables(id) {
        /*DataTable*/
        oTableCaixasDocs = $('#tabela_caixas_documentos').dataTable({
            aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            bStateSave: false,
            bPaginate: true,
            bProcessing: false,
            bServerSide: true,
            sScrollY: "380px",
            bJQueryUI: true,
            sPaginationType: "full_numbers",
            sAjaxSource: "modelos/caixas/listar_documentos_caixas.php?id_caixa=" + id,
            aoColumnDefs: [{bSortable: false, aTargets: [9]}],
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

                var $line = $('td:eq(9)', nRow);
                $line.html('');

                if (aData[3] == '') {
                    $('td:eq(3)', nRow).html('<div title=""></div>');
                }

                if (aData[4] == '') {
                    $('td:eq(4)', nRow).html('<div title=""></div>');
                }

                if (aData[5] == '') {
                    $('td:eq(5)', nRow).html('<div title=""></div>');
                }

                $("<img/>", {
                    src: 'imagens/cancelar_upload.png',
                    title: 'Retirar documento da caixa',
                    'class': 'botao32'
                }).bind("click", function() {
                    jquery_retirar_documento_caixa(aData[0]);
                }).appendTo($line);

                return nRow;
            }
        });
    }

    function jquery_retirar_documento_caixa(id) {
        $('#progressbar').show();
        $.post("modelos/caixas/caixas.php",
                {
                    acao: 'retirar-documento',
                    id: parseInt(id)
                },
        function(data) {
            $('#progressbar').hide();
            if (data.success == 'true') {
                oTableCaixasDocs.fnDraw(false);
            } else {
                alert('Ocorreu um erro ao tentar retirar documento da caixa!');
            }
        }, "json");
    }
</script>
</head>
<body>
    <div id="box-listar-caixa" class="div-form-dialog" title="Listar Documentos">
        <input type="hidden" id="ID_LISTAR_CAIXA" />
        <div class="cabecalho-caixas">
            <div class="logo-manter-unidades"></div>
            <div class="titulo-manter-unidades">Gerenciamento de Documentos em Caixas</div>
            <div class="menu-auxiliar">
                <?php Util::montaMenus($botoes, array('class' => 'botao32')); ?>
            </div>
        </div>
        <table class="display" border="0" id="tabela_caixas_documentos">
            <thead>
                <tr>
                    <th class="style13">#</th>
                    <th class="style13">Digital</th>
                    <th class="style13">Número do Documento</th>
                    <th class="style13">Assunto</th>
                    <th class="style13">Assinatura</th>
                    <th class="style13">Destino</th>
                    <th class="style13">Classificação</th>
                    <th class="style13">Área de Trabalho</th>
                    <th class="style13">Usuário</th>
                    <th class="style13">Opções</th>
                </tr>
            </thead>
        </table>
    </div>
    <!--Adicionar Documento-->
    <div id="box-adicionar-documento2" class="div-form-dialog" title="Adicionar Documento">
        <fieldset>
            <label>Informações Principais</label>
            <input class="FUNDOCAIXA1" id="ID_ADICIONAR_DOCUMENTO_CAIXA2" type="hidden">
            <div class="row">
                <label class="label">DOCUMENTO:</label>
                <span class="conteudo">
                    <select class="FUNDOCAIXA1" id="ID_DIGITAL_DOCUMENTO2"></select>
                </span>
            </div>
        </fieldset>
    </div>
