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

    var oTabelaPrivilegiosUnidade;

    $(document).ready(function() {
        $("#tabs-privilegio").tabs();
        aclPrivilegio.init();
    });

    /**
     * Atribuir privilégio para as unidades
     */
    var aclPrivilegio = {
        idUnidade:0,
        init: function() {
            $('#formGrupoPrivilegios').submit(function(e) {
                e.preventDefault();
                aclPrivilegio.salvarGrupoPrivilegio();
            });

            $('#comboGruposPrivilegios').bind('change', function() {
                aclPrivilegio.getGrupoPrivilegioRecursos(this.value);
            });

            $('a[href="#tab-divRecursosAcl"]').click(function() {
                aclPrivilegio.dataTableRecurso(aclPrivilegio.idUnidade);
            })
        },
        dataTableRecurso: function(id_unidade) {
            oTabelaPrivilegiosUnidade = $('#tabela_privilegios').dataTable({
                aLengthMenu: [[7, 25, 50, 100], [7, 25, 50, 100]],
                'iDisplayLength': 7,
                'bDestroy':true,
                bStateSave: false,
                bPaginate: true,
                bProcessing: true,
                bServerSide: true,
                sScrollY: "215px",
                bJQueryUI: true,
                sPaginationType: "full_numbers",
                sAjaxSource: "modelos/recursos/grid_recursos_unidade.php?unidade="+id_unidade,
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
                        sFirst:    "Primeiro",
                        sPrevious: "Anterior",
                        sNext:     "Próximo",
                        sLast:     "Ultimo"
                    }
                },
                aoColumnDefs: [
                    { bSortable: false, aTargets: [3] }
                ],
                fnServerData: function ( sSource, aoData, fnCallback ) {
                    $.getJSON( sSource, aoData, function (json) {
                        fnCallback(json);
                    } );
                },
                fnRowCallback: function(nRow, aData, iDisplayIndex) {
                    var checked = false;
                    if(aData[4] == 1) {
                        checked = 'checked';
                    }
                    var line = $('td:eq(4)', nRow);
                    line.html('');
                    var id_recurso = aData[0];
                    $("<input/>", {
                        type:  'checkbox',
                        title: 'Ativar/Desativar',
                        name:  "permissao["+id_recurso+"]",
                        id:    'permissao-'+id_recurso,
                        'class': 'permissao',
                        checked: checked
                    }).bind( "change", function() {
                        aclPrivilegio.salvaPrivilegio(id_unidade, id_recurso, $(this).attr('checked'));
                    }).appendTo(line);
                    return nRow;
                }
            });
        },
        jquery_acl_privilegio: function(id, nome)
        {
            this.dataTableRecurso(id);
            this.getComboGruposPrivilegios(id);
            this.idUnidade = id;
            $('#divRecursosAcl').dialog({
                autoOpen: true,
                title: 'SGDOC '+nome,
                width:850,
                height: 470,
                resizable: false,
                modal: true,
                buttons: {
                    'Fechar': function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
        },
        salvaPrivilegio: function(id_unidade, id_recurso, permissao) {
            $.ajax({
                url: 'modelos/recursos/recursos_unidade.php',
                type: 'post',
                dataType: 'json',
                context: this,
                data: {
                    acao:       'salvar-privilegio',
                    id_unidade: id_unidade,
                    id_recurso: id_recurso,
                    permissao:  permissao
                },
                success: function(data) {
                    if(data.success == 'true') {
                        if(data.message != undefined && data.message != '') {
                            $('#formRecursosAcl #saidaSucesso').html(data.message);
                            setTimeout(function() {
                                $('#formRecursosAcl #saidaSucesso').html('');
                            }, 2000);
                            oTabelaPrivilegiosUnidade.fnDraw(false);
                        }
                    }
                }
            });
        },
        getComboGruposPrivilegios: function(idUnidade) {
            $('#comboGruposPrivilegios').html('');
            $.ajax({
                url:'modelos/recursos/recursos_unidade.php',
                type:'POST',
                dataType:'json',
                data: {
                    acao:'get-combo-grupos-privilegios',
                    id_unidade:idUnidade
                },
                success: function(data) {
                    /**
                     * @todo parei aqui
                     */
                    var option = '<option value="0">Selecione...</option>';
                    for(var i in data) {
                        option += "<option value="+data[i].ID+">"+data[i].NOME+"</option>";
                    }
                    $('#comboGruposPrivilegios').append(option);
                }
            });
        },
        getGrupoPrivilegioRecursos: function(id_grupos_privilegios) {
            $.ajax({
                url:'modelos/recursos/recursos_unidade.php',
                type:'POST',
                dataType:'json',
                data: {
                    acao:'get-grupo-privilegio-recursos',
                    id_unidade:this.idUnidade,
                    id_grupo_privilegio:id_grupos_privilegios
                },
                success: function(data) {
                    var html = '<table class="display" border="0" id="tabela_grupo_privilegios">';
                    for(var i in data) {
                        if(i % 2 == 0) {
                            html += '<tr class="linhaPar">';
                        } else {
                            html += '<tr>';
                        }
                        html += '<td class="style13">';
                        html += data[i].NOME;
                        html += '</td>';
                        html += '<td class="style13">';
                        if(data[i].PERMISSAO == 1) {
                            html += "<span class='green'>Permitido</span>";
                        } else {
                            html += "<span class='red'>Negado</span>";
                        }
                        html += '</td>';
                        html += '</tr>';
                    }
                    html += '</table>';
                    $('#tabela_grupo_privilegios').html(html);
                    if(data == '') {
                        alert('Não existe nenhuma ligação para este grupo');
                    }
                }
            });
        },
        salvarGrupoPrivilegio: function() {
            $.ajax({
                url:'modelos/recursos/recursos_unidade.php',
                type:'POST',
                dataType:'json',
                data: {
                    acao:'salvar-grupos-privilegios',
                    id_unidade:this.idUnidade,
                    id_grupo_privilegio:$('#comboGruposPrivilegios option:selected').attr('value')
                },
                success: function(data) {
                    if(data.success == 'true') {
                        var saida = $('#formGrupoPrivilegios #saidaSucesso').text(data.message);
                        setTimeout(function() {
                            saida.text('');
                        }, 2000);
                    }
                }
            });
        }
    }
</script>
<style type="text/css">
    #comboGruposPrivilegios {
        width: 250px;
    }
    .linhaTitulo {
        background-color: #f8f8f8;
    }
    .linhaPar {
        background-color: #9EB56E;
    }
    .green {
        color:green;
    }
    .red {
        color: #FF5511;
    }
    #tabela_grupo_privilegios {
        width:100%;
    }
    #containerGrupoPrivilegios {        
        height: 270px;
        overflow: auto;
    }
</style>

<div id="divRecursosAcl">

    <div id="tabs-privilegio">
        <ul>
            <li class="liTabs">
                <a href="#tab-divRecursosAcl">Definir permissões para Unidade</a>
            </li>
            <li class="liTabs">
                <a href="#tab-divRecursosAclTipo">Definir permissão para Unidade por Grupo</a>
            </li>
        </ul>

        <div id="tab-divRecursosAcl">
            <!--Privilégio-->
            <form id="formRecursosAcl" method="POST">
                <table class="display" border="0" id="tabela_privilegios">
                    <thead>
                        <tr>
                            <th class="style13">#</th>
                            <th class="style13">Recurso</th>
                            <th class="style13">Descrição</th>
                            <th class="style13">Tipo</th>
                            <th class="style13">Permissão</th>
                        </tr>
                    </thead>
                </table>
            </form>
            <span id="saidaSucesso"></span>
        </div>

        <div id="tab-divRecursosAclTipo">
            
            <form id="formGrupoPrivilegios" name="formGrupoPrivilegios" method="post" action="">
                <select id="comboGruposPrivilegios"></select>&nbsp;<input type="submit" name="salvar" value="Salvar" />
                <span id="saidaSucesso"></span>
            </form>
            <div id="containerGrupoPrivilegios">                
                <table border="0" id="tabela_grupo_privilegios"></table>
            </div>
        </div>
    </div>
</div>