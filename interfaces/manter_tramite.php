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

<style type="text/css">
    /** multiselect styles */
    .campo-error{
        background-color: mistyrose;
    }
    #tabs-tramites {
        text-align: left;
        float:left;
        width: 100%;
    }
    .multiselect {
        width: 400px !important;
        height: 200px !important;
        float:left;
    }
    .containerMultiselect {
        width: 860px;
        float: left;
        padding-left:10px;
    }
    .containerMultiselect label {
        height: 20px;
        width: 400px;
        float: left;
    }
    .filtroUnidade {
        padding: 10px;
    }
    #selecionaUnidade {
        width:40px;
        padding-top: 30px;
        height: 220px;
        text-align: center;
        float:left;
    }
    .divMultiselect {
        width: 400px;
        height: 220px;
        float: left;
    }
    .select {
        width: 400px;
    }
    #formClone {
        margin: 0;
        padding: 0;
    }
    #clone {
        float: right;
        margin: 10px;
    }
    /*Tab 3*/
    ul.explorer1{
        padding: 5px;
        margin: 0;
        font-size: 10px;
    }

    ul.explorer1 ul{
        padding: 0;
        margin: 0 0 0 10px;
    }
    ul.explorer1 li a{ font-family: Arial; text-decoration: none; color: black; }
    ul.explorer1 li a:hover{ color: blue; }
    /*    ul.explorer1 li{ list-style: none; background-image: url("/img/dotted.gif"); background-repeat: repeat-y; padding: 2px 0 2px 20px; text-indent: -19px; background-position: 9px 0; }
        ul.explorer1 li::before{ content: url("/img/bullete.gif") " "; }*/
    ul.explorer1 li{ list-style: none; background-image: url("imagens/dotted.gif"); background-repeat: repeat-y; padding: 4px 0 4px 20px; text-indent: -19px; background-position: 9px 0; }
    ul.explorer1 li::before{ content: url("imagens/bullete.gif"); }
    .explorer-selected{
        font-weight: bold;
    }
</style>
<script type="text/javascript">
    function jquery_detalhar_regras_tramite(id) {
        tramite.init(id);
        $('#box-detalhar-regras-tramite').dialog('open');
    }

    var tramite = {
        idUnidade: 0,
        init: function(idUnidade) {
            this.idUnidade = idUnidade;
            this.getUnidade();
            this.hierarquiaTramite();
            $('#unidadesDisponiveis, #unidadesDisponiveisClone').html('');
            $('#qtdUnidadesDisponiveis, #qtdUnidadesDisponiveisClone').text('');
            $("#tabs-tramites").tabs();

            //tabs-tramites-1
            this.getUnidadesAtivas();
            $("a[href='#tabs-tramites-1']").click(function() {
                tramite.getUnidadesAtivas();
            });
            $('#tipos').change(function() {
                tramite.getUnidadesDisponiveis(this.value, 'unidadesDisponiveis', 'qtdUnidadesDisponiveis', null, $('#orgaoSuperior option:selected').val());
            });
            $("#leftToRight").click(function() {
                tramite.moverItem("unidadesDisponiveis", "unidadesAtivas");
            });
            $("#rightToLeft").click(function() {
                tramite.moverItem("unidadesAtivas", "unidadesDisponiveis");
            });

            //tabs-tramites-2
            $('#tiposClone').change(function() {
                tramite.getUnidadesDisponiveis(this.value, 'unidadesDisponiveisClone', 'qtdUnidadesDisponiveisClone', 'ALL', $('#orgaoSuperiorClone option:selected').val());
            });
            $('#formClone').submit(function(e) {
                e.preventDefault();
                tramite.clonarTramite();
            });
            //tabs-tramites-3
            $('.checkboxTreeTramite').die('click').live('click', function() {
                tramite.salvarHierarquia(this);
            });
            $('.checkboxTreeAll').die('click').live('click', function() {
                tramite.salvarTodosHierarquia(this);
            });
        },
        getUnidade: function() {
            $.ajax({
                url: 'modelos/tramite/tramite.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    'acao': 'get-unidade',
                    'idUnidade': this.idUnidade
                },
                success: function(data) {
                    if (data.success == true) {
                        $('#box-detalhar-regras-tramite').dialog({'title': 'Regras de Tramite da Unidade: ' + data.nome});
                    } else {
                        alert(data.error);
                        $('#box-detalhar-regras-tramite').dialog('close');
                    }
                }
            });
        },
        getUnidadesTipo: function(idCombo, uop) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'modelos/tramite/tramite.php',
                data: {
                    'uop': uop,
                    'acao': 'get-unidades-tipo'
                },
                success: function(data) {
                    var options = '<option value="0">Selecione</option>';
                    for (var i in data) {
                        options += '<option value="' + data[i].ID + '">' + data[i].NOME + '</option>';
                    }
                    $('#' + idCombo).html(options);
                }
            });
        },
        getUnidadesDisponiveis: function(tipo, idCombo, idQtd, listar, uop) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'modelos/tramite/tramite.php',
                data: {
                    'acao': 'get-unidades-disponiveis',
                    'tipo': tipo,
                    'idUnidade': this.idUnidade,
                    'listarTodos': listar,
                    'uop': uop
                },
                success: function(data) {
                    var options = '';
                    var j = 0;
                    for (var i in data) {
                        options += '<option value="' + data[i].ID + '">' + data[i].NOME + '</option>';
                        j++;
                    }
                    $('#' + idQtd).text(j);
                    $('#' + idCombo).html(options);
                }
            });
        },
        getUnidadesAtivas: function() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'modelos/tramite/tramite.php',
                data: {
                    'acao': 'get-unidades-ativas',
                    'idUnidade': this.idUnidade
                },
                success: function(data) {
                    var options = '';
                    var j = 0;
                    var aux = '';
                    for (var i in data) {
                        if (aux != data[i].TIPO) {
                            if (j > 0) {
                                options += '</optgroup>';
                            }
                            options += '<optgroup id="' + data[i].ID_TIPO + '" class="' + data[i].TIPO + '" label="' + data[i].TIPO + '">';
                        }
                        options += '<option value="' + data[i].ID + '">' + data[i].NOME + '</option>';
                        j++;
                        aux = data[i].TIPO;
                    }
                    $('#unidadesAtivas').html(options);
                    $('#qtdUnidadesAtivas').text(j);
                }
            });
        },
        moverItem: function(de, para) {
            $("#" + de + " option:selected").each(function() {
                tramite.adicionarItem(para, $(this).val(), $(this).text());
                $(this).remove();
            });
        },
        adicionarItem: function(select, val, tex) {
            this.salvarTramite(select, val);
            var option = "<option value='" + val + "'>" + tex + "</option>";
            var qtdUnidadesAtivas = parseInt($('#qtdUnidadesAtivas').text());
            var qtdUnidadesDisponiveis = parseInt($('#qtdUnidadesDisponiveis').text());
            if (select == 'unidadesAtivas') {
                var tipo = $('#tipos option:selected').text();
                var idTipo = $('#tipos option:selected').attr('value');
                if ($("#unidadesAtivas optgroup[label='" + tipo + "']").hasClass(tipo) == true) {
                    $("#unidadesAtivas optgroup[label='" + tipo + "']").append(option);
                } else {
                    $("#unidadesAtivas").append('<optgroup id="' + idTipo + '" class="' + tipo + '" label="' + tipo + '">' + option + '</optgroup>');
                }
                $('#qtdUnidadesAtivas').text(++qtdUnidadesAtivas);
                $('#qtdUnidadesDisponiveis').text(--qtdUnidadesDisponiveis);
            } else {
                var idTipo = $("#unidadesAtivas option[value='" + val + "']").parent().attr('id');
                var tipo = $("#unidadesAtivas option[value='" + val + "']").parent().attr('label');
                $('#tipos').val(idTipo).trigger('change');

                $('#qtdUnidadesAtivas').text(--qtdUnidadesAtivas);
                $('#qtdUnidadesDisponiveis').text(++qtdUnidadesDisponiveis);
            }
        },
        salvarTramite: function(tratamento, id) {
            $.ajax({
                type: 'POST',
                async: false,
                dataType: 'json',
                url: 'modelos/tramite/tramite.php',
                data: {
                    'acao': 'salvar-tramite',
                    'tratamento': tratamento,
                    'idReferencia': id,
                    'idUnidade': this.idUnidade
                },
                success: function(data) {
                    tramite.saidaSucesso('.saidaSucesso', data.message);
                    if (tratamento == 'unidadesAtivas') {
                        $(".checkboxTreeTramite[value='" + id + "']").attr('checked', 'checked');
                    } else {
                        $(".checkboxTreeTramite[value='" + id + "']").removeAttr('checked');
                    }
                }
            });
        },
        clonarTramite: function() {
            var idClone = $('#unidadesDisponiveisClone option:selected').attr('value');
            $.ajax({
                url: 'modelos/tramite/tramite.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    'acao': 'clonar-tramite',
                    'idClone': idClone,
                    'idUnidade': this.idUnidade
                },
                success: function(data) {
                    tramite.saidaSucesso('#tabs-tramites-2 .saidaSucesso', data.message);
                }
            });
        },
        hierarquiaTramite: function() {
            $.ajax({
                url: 'modelos/tramite/tramite.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    'acao': 'get-hierarquia',
                    'idUnidade': this.idUnidade
                },
                success: function(data) {
                    $('#arvore-unidade').html(data.html);
                    $('.checkboxTreeAll').attr('checked', 'checked');
                    $('.checkboxTreeTramite').each(function(a, b) {
                        if (b.checked == false) {
                            $('.checkboxTreeAll').removeAttr('checked');
                        }
                    });
                }
            });
        },
        salvarHierarquia: function(div) {
            var tratamento = '';
            if (div.checked == true) {
                var tratamento = 'unidadesAtivas';
            } else {
                $('.checkboxTreeAll').removeAttr('checked');
            }
            this.salvarTramite(tratamento, div.value);
        },
        salvarTodosHierarquia: function(div) {
            $('.checkboxTreeTramite').attr('checked', div.checked);
            var dados = [];
            var checked = $('.checkboxTreeAll').attr('checked');
            if (checked == true) {
                $('.checkboxTreeTramite').each(function(a, b) {
                    dados.push(b.value);
                });
            }
            $.ajax({
                url: 'modelos/tramite/tramite.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    'acao': 'salvar-todos-hierarquia',
                    'idReferencias': dados,
                    'idUnidade': this.idUnidade,
                    'checked': checked
                },
                success: function(data) {
                    tramite.saidaSucesso('#tabs-tramites-3 .saidaSucesso', data.message);
                }
            });
        },
        saidaSucesso: function(divId, message) {
            var saidaSucesso = $(divId).html(message).show();
            setTimeout(function() {
                saidaSucesso.hide();
            }, 1000);
        }
    }


    $(document).ready(function() {
        /*Regras de Tramite*/
        $('#box-detalhar-regras-tramite').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 920,
            height: 550
        });

        $('#orgaoSuperior').combobox('modelos/combos/orgaos_principais.php');
        $('#orgaoSuperiorClone').combobox('modelos/combos/orgaos_principais.php');

        $('#orgaoSuperior').change(function() {
            $('#unidadesDisponiveis').empty();
            $('#qtdUnidadesDisponiveis').text('');
            tramite.getUnidadesTipo('tipos', $('#orgaoSuperior option:selected').val());
        });

        $('#orgaoSuperiorClone').change(function() {
            tramite.getUnidadesTipo('tiposClone', $('#orgaoSuperiorClone option:selected').val());
        });

    });

</script>

<div id="box-detalhar-regras-tramite" class="div-form-dialog" title="Regras de Trâmite">

    <div id="tabs-tramites">
        <ul>
            <li class="liTabs">
                <a href="#tabs-tramites-1">Gerenciar Trâmites por Tipo de Unidade</a>
            </li>
            <li class="liTabs">
                <a href="#tabs-tramites-2">Clonar Trâmite de Unidade</a>
            </li>
            <li class="liTabs">
                <a href="#tabs-tramites-3">Trâmite por Hierárquia da Unidade</a>
            </li>
        </ul>

        <div id="tabs-tramites-1">
            <h3>Gerenciar Trâmites por Tipo de Unidade</h3>
            <div class="filtroUnidade">
                <label>Orgão principal:</label>
                <br />
                <select id="orgaoSuperior" class="select">
                    <option value="0">Selecione</option>
                </select>
            </div>
            <div class="filtroUnidade">
                <label>Tipo de Unidade:</label>
                <br />
                <select id="tipos" class="select" name="tipos">
                    <option value="0">Selecione</option>
                </select>
            </div>
            <div class="containerMultiselect">
                <div class="divMultiselect">
                    <label>Unidades Disponíveis: <span id="qtdUnidadesDisponiveis"></span></label>
                    <select id='unidadesDisponiveis' multiple='multiple' name="unidadesDisponiveis[]" class="multiselect"></select>
                    <div class="saidaSucesso"></div>
                </div>
                <div id="selecionaUnidade">
                    <span id="leftToRight">
                        <img src="imagens/arrow_right.png" alt="adicionar ao tramite" />
                    </span>
                    <span>&nbsp;</span>
                    <span id="rightToLeft">
                        <img src="imagens/arrow_left.png" alt="retirar do tramite" />
                    </span>
                </div>
                <div class="divMultiselect">
                    <label>Unidades Ativas: <span id="qtdUnidadesAtivas"></span></label>
                    <select id='unidadesAtivas' multiple='multiple' name="unidadesAtivas[]" class="multiselect"></select>
                </div>
            </div>
        </div>

        <div id="tabs-tramites-2">

            <h3>Clonar fluxo de tramites</h3>
            <div class="filtroUnidade">
                <label>Orgão principal:</label>
                <br />
                <select id="orgaoSuperiorClone" class="select">
                    <option value="0">Selecione</option>
                </select>
            </div>
            <div class="filtroUnidade">
                <label>Tipo de Unidade:</label>
                <br />
                <select id="tiposClone" class="select" name="tiposClone">
                    <option value="0">Selecione</option>
                </select>
            </div>
            <form name="formClone" id="formClone" method="post">
                <div class="containerMultiselect">
                    <div class="divMultiselect">
                        <label>Unidades que podem ser clonadas: <span id="qtdUnidadesDisponiveisClone"></span></label>
                        <br />
                        <select id='unidadesDisponiveisClone' name="unidadesDisponiveisClone" class="select">
                            <option value="0">Selecione</option>
                        </select>
                        <input type="submit" name="clone" value="Clonar!" id="clone" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
                        <div class="saidaSucesso"></div>
                    </div>
                </div>
            </form>

        </div>
        <div id="tabs-tramites-3">
            <h3>Gerenciar Trâmite por sua hierárquia organizacional</h3>

            <div class="containerMultiselect">
                <div>ATIVAR/DESATIVAR TODOS<input type="checkbox" class="checkboxTreeAll"></div>
                <div id="arvore-unidade"></div>
                <div class="saidaSucesso"></div>
            </div>
        </div>
    </div>
</div>