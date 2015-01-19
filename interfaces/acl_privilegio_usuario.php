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

include("function/auto_load_statics.php");

$controller = Controlador::getInstance();

$idUsuario = (integer) $_GET['usuario'];

$getUsuario = (array) current(CFModelUsuario::factory()->find($idUsuario));

if ($getUsuario):
    ?>
    <html>
        <head>
            <script type="text/javascript" src="plugins/datatable/media/js/jquery.dataTables.js"></script>
            <script type="text/javascript">

                var oTabelaPrivilegiosUsuario = null;

                $(document).ready(function() {
                    $("#tabs").tabs();
                    $(".cabecalho-caixas").tabs();
                    aclPrivilegioUsuario.init();
                });
                var aclPrivilegioUsuario = {
                    init: function() {
                        this.dataTableRecurso('<?php echo $idUsuario; ?>', '<?php echo $controller->usuario->ID_UNIDADE; ?>');
                    },
                    dataTableRecurso: function(id_usuario, id_unidade) {
                        oTabelaPrivilegiosUsuario = $('#tabela_privilegios').dataTable({
                            aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                            'iDisplayLength': 10,
                            'bDestroy': true,
                            bStateSave: false,
                            bPaginate: true,
                            bProcessing: true,
                            bServerSide: true,
                            bJQueryUI: true,
                            sPaginationType: "full_numbers",
                            sAjaxSource: "modelos/recursos/grid_recursos_usuario.php?usuario=" + id_usuario + "&unidade=" + id_unidade,
                            oLanguage: {
                                sProcessing: "Carregando...",
                                sLengthMenu: "_MENU_ por página",
                                sZeroRecords: "Nenhum recurso encontrado.",
                                sInfo: "_START_ a _END_ de _TOTAL_ recursos.",
                                sInfoEmpty: "Nao foi possivel localizar unidades com os parametros informados!",
                                sInfoFiltered: "(Total _MAX_ recursos)",
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
                                var line = $('td:eq(5)', nRow);
                                line.html('');
                                if (aData[3] == '') {
                                    $('td:eq(3)', nRow).html('<div title=""></div>');
                                }
                                var id_recurso = aData[0];
                                var padrao = aData[4];
                                var permissao = aData[5];
                                var checkedDefault = false;
                                var checkedAllow = false;
                                var checkedDeny = false;

                                if (permissao == '1') {
                                    var checkedAllow = true;
                                } else if (permissao == '0') {
                                    var checkedDeny = true;
                                } else {
                                    var checkedDefault = true;
                                }

                                $('td:eq(4)', nRow).html('<div title="">');
                                if (padrao == 1) {
                                    $('td:eq(4)', nRow).append('Permitido');
                                } else if (padrao == 0) {
                                    $('td:eq(4)', nRow).append('Negado');
                                }
                                $('td:eq(4)', nRow).append('</div>');

                                $("<input/>", {
                                    type: 'radio',
                                    title: 'Padrão',
                                    name: "permissao[" + id_recurso + "]",
                                    id: 'permissao_default-' + id_recurso,
                                    'class': 'permissao',
                                    checked: checkedDefault
                                }).bind("change", function() {
                                    aclPrivilegioUsuario.excluirPrivilegio(id_usuario, id_recurso);
                                }).appendTo(line);

                                $('<label/>', {
                                    'for': 'permissao_default-' + id_recurso,
                                    'text': 'Padrão',
                                    'class': 'permissao'
                                }).appendTo(line);

                                $("<input/>", {
                                    type: 'radio',
                                    title: 'Permitir',
                                    name: "permissao[" + id_recurso + "]",
                                    id: 'permissao_allow-' + id_recurso,
                                    'class': 'permissao',
                                    checked: checkedAllow
                                }).bind("change", function() {
                                    aclPrivilegioUsuario.salvarPrivilegio(id_usuario, id_recurso, 1);
                                }).appendTo(line);

                                $('<label/>', {
                                    'for': 'permissao_allow-' + id_recurso,
                                    'text': 'Permitir',
                                    'class': 'permissao'
                                }).appendTo(line);

                                $("<input/>", {
                                    type: 'radio',
                                    title: 'Negar',
                                    name: "permissao[" + id_recurso + "]",
                                    id: 'permissao_deny-' + id_recurso,
                                    'class': 'permissao',
                                    checked: checkedDeny
                                }).bind("change", function() {
                                    aclPrivilegioUsuario.salvarPrivilegio(id_usuario, id_recurso, 0);
                                }).appendTo(line);

                                $('<label/>', {
                                    'for': 'permissao_deny-' + id_recurso,
                                    'text': 'Negar',
                                    'class': 'permissao'
                                }).appendTo(line);

                                return nRow;
                            }
                        });
                    },
                    salvarPrivilegio: function(id_usuario, id_recurso, permissao) {
                        $.ajax({
                            url: 'modelos/recursos/recursos_usuario.php',
                            type: 'post',
                            dataType: 'json',
                            context: this,
                            data: {
                                acao: 'salvar-privilegio-usuario',
                                id_usuario: id_usuario,
                                id_recurso: id_recurso,
                                permissao: permissao
                            },
                            success: function(data) {
                                if (data.success == 'true') {
                                    if (data.message != undefined && data.message != '') {
                                        $('#saidaSucesso').html(data.message);
                                        setTimeout(function() {
                                            $('#saidaSucesso').html('');
                                        }, 2000);
                                    }
                                    oTabelaPrivilegiosUsuario.fnDraw(false);
                                }
                            }
                        });
                    },
                    excluirPrivilegio: function(id_usuario, id_recurso) {
                        $.ajax({
                            url: 'modelos/recursos/recursos_usuario.php',
                            type: 'post',
                            dataType: 'json',
                            context: this,
                            data: {
                                acao: 'excluir-privilegio-usuario',
                                id_usuario: id_usuario,
                                id_recurso: id_recurso
                            },
                            success: function(data) {
                                if (data.success == 'true') {
                                    if (data.message != undefined && data.message != '') {
                                        $('#saidaSucesso').html(data.message);
                                        setTimeout(function() {
                                            $('#saidaSucesso').html('');
                                        }, 2000);
                                        oTabelaPrivilegiosUsuario.fnDraw(false);
                                    }
                                }
                            }
                        });
                    }
                }
            </script>
            <style type="text/css" title="currentStyle">
                @import "plugins/datatable/media/css/demo_table_tabs.css";
                .campo-error{
                    background-color: mistyrose;
                }
            </style>
        </head>
        <body>
            <div class="cabecalho-caixas">
                <div class="logo-manter-privilegios"></div>
                <div class="titulo-manter-privilegios">Permissões do usuário</div>
                <div class="menu-auxiliar">
                    <?php Util::montaMenus($controller->botoes, array('class' => 'botao32')); ?>
                </div>
            </div>

            <!--Privilégio-->
            <div id="tabs">
                <h3>Usuário: <?php echo $getUsuario['nome']; ?></h3>
                <div id="tabs-1">
                    <form id="formRecursosAcl" method="POST">
                        <table class="display" border="0" id="tabela_privilegios">
                            <thead>
                                <tr>
                                    <th class="style13">#</th>
                                    <th class="style13">Recurso</th>
                                    <th class="style13">Descrição</th>
                                    <th class="style13">Tipo</th>
                                    <th class="style13">Permissao Unidade</th>
                                    <th class="style13">Permissão Usuário</th>
                                </tr>
                            </thead>
                        </table>
                    </form>
                    <span id="saidaSucesso"></span>
                </div>
            </div>
        </body>
    </html>
<?php else: ?>
    <?php header('Location: manter_usuarios.php'); ?>
<?php endif; ?>