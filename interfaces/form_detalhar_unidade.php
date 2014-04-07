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
    var selected = null;

    $(document).ready(function() {
        /*Detalhar Unidade*/
        $('#box-detalhar-unidades').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 600,
            autoHeight: true,
            buttons: {
                Salvar: function() {
                    if (jquery_validar_nova_unidade('DETALHAR')) {
                        var c = confirm('Você tem certeza que deseja salvar esta nova unidade agora?');
                        if (c) {
                            $.post("modelos/unidades/unidades.php", {
                                acao: 'alterar',
                                id: $('#ID_DETALHAR_UNIDADE').val(),
                                nome: $('#NOME_DETALHAR_UNIDADE').val(),
                                sigla: $('#SIGLA_DETALHAR_UNIDADE').val(),
                                uaaf: $('#UAAF_DETALHAR_UNIDADE').val(),
                                cr: $('#CR_DETALHAR_UNIDADE').val(),
                                superior: $('#SUPERIOR_DETALHAR_UNIDADE').val(),
                                diretoria: $('#DIRETORIA_DETALHAR_UNIDADE').val(),
                                tipo: $('#TIPO_DETALHAR_UNIDADE').val(),
                                up: $('#UP_DETALHAR_UNIDADE').val(),
                                codigo: $('#CODIGO_DETALHAR_UNIDADE').val(),
                                uf: $('#UF_DETALHAR_UNIDADE').val(),
                                email: $('#EMAIL_DETALHAR_UNIDADE').val(),
                                uop: $('#UNIDADE_ORGAO_PRINCIPAL_DETALHAR_UNIDADE').val(),
                                isuop: $('#EH_UNIDADE_ORGAO_PRINCIPAL_DETALHAR_UNIDADE').val(),
                                clear: selected
                            },
                            function(data) {
                                if (data.success == 'true') {
                                    $('#box-detalhar-unidades').dialog("close");
                                    oTableUnidades.fnDraw(false);
                                    alert(data.message);
                                } else {
                                    alert('Ocorreu um erro ao tentar salvar as informacoes da unidade!\n[' + data.error + ']');
                                }
                            }, "json");
                        }
                    } else {
                        alert('Campo(s) obrigatorio(s) em branco ou preenchido(s) de forma invalida!');
                    }
                }
            }
        });

        /*Filtro Superior Cadastrar*/
        $('#box-filtro-superior-cadastrar-unidade').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#FILTRO_SUPERIOR_CADASTRAR_UNIDADE").val('');
            }
        });
        /*Combo Superior Cadastrar*/
        $("#FILTRO_SUPERIOR_CADASTRAR_UNIDADE").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox: 'SUPERIOR_CADASTRAR_UNIDADE',
            extraParams: {
                action: 'unidades-internas',
                type: 'IN'
            }
        });
        /*Filtro Superior Detalhar*/
        $('#box-filtro-superior-detalhar-unidade').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#FILTRO_SUPERIOR_DETALHAR_UNIDADE").val('');
            }
        });
        /*Combo Superior Detalhar*/
        $("#FILTRO_SUPERIOR_DETALHAR_UNIDADE").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox: 'SUPERIOR_DETALHAR_UNIDADE',
            extraParams: {
                action: 'unidades-internas',
                type: 'IN'
            }
        });

        /*Filtro Diretoria Cadastrar*/
        $('#box-filtro-diretoria-cadastrar-unidade').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#FILTRO_DIRETORIA_CADASTRAR_UNIDADE").val('');
            }
        });
        /*Combo Diretoria Cadastrar*/
        $("#FILTRO_DIRETORIA_CADASTRAR_UNIDADE").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox: 'DIRETORIA_CADASTRAR_UNIDADE',
            extraParams: {
                action: 'unidades-internas',
                type: 'DIR'
            }
        });
        /*Filtro Diretoria Detalhar*/
        $('#box-filtro-diretoria-detalhar-unidade').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#FILTRO_DIRETORIA_DETALHAR_UNIDADE").val('');
            }
        });
        /*Combo Diretoria Detalhar*/
        $("#FILTRO_DIRETORIA_DETALHAR_UNIDADE").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox: 'DIRETORIA_DETALHAR_UNIDADE',
            extraParams: {
                action: 'unidades-internas',
                type: 'DIR'
            }
        });

        /*Filtro Cr Cadastrar*/
        $('#box-filtro-cr-cadastrar-unidade').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#FILTRO_CR_CADASTRAR_UNIDADE").val('');
            }
        });
        /*Combo Cr Cadastrar*/
        $("#FILTRO_CR_CADASTRAR_UNIDADE").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox: 'CR_CADASTRAR_UNIDADE',
            extraParams: {
                action: 'unidades-internas',
                type: 'CR'
            }
        });
        /*Filtro Cr Detalhar*/
        $('#box-filtro-cr-detalhar-unidade').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#FILTRO_CR_DETALHAR_UNIDADE").val('');
            }
        });
        /*Combo Cr Detalhar*/
        $("#FILTRO_CR_DETALHAR_UNIDADE").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox: 'CR_DETALHAR_UNIDADE',
            extraParams: {
                action: 'unidades-internas',
                type: 'CR'
            }
        });

        /*Filtro Uaaf Cadastrar*/
        $('#box-filtro-uaaf-cadastrar-unidade').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#FILTRO_UAAF_CADASTRAR_UNIDADE").val('');
            }
        });
        /*Combo Uaaf Cadastrar*/
        $("#FILTRO_UAAF_CADASTRAR_UNIDADE").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox: 'UAAF_CADASTRAR_UNIDADE',
            extraParams: {
                action: 'unidades-internas',
                type: 'UAAF'
            }
        });
        /*Filtro Uaaf Detalhar*/
        $('#box-filtro-uaaf-detalhar-unidade').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#FILTRO_UAAF_DETALHAR_UNIDADE").val('');
            }
        });
        /*Combo Uaaf Detalhar*/
        $("#FILTRO_UAAF_DETALHAR_UNIDADE").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox: 'UAAF_DETALHAR_UNIDADE',
            extraParams: {
                action: 'unidades-internas',
                type: 'UAAF'
            }
        });

        /*Cadastrar*/
        $('#box-nova-unidade').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 600,
            autoHeight: true,
            buttons: {
                Salvar: function() {
                    if (jquery_validar_nova_unidade('CADASTRAR')) {
                        if (confirm('Você tem certeza que deseja salvar esta unidade agora?')) {
                            $.post("modelos/unidades/unidades.php", {
                                acao: 'cadastrar',
                                nome: $('#NOME_CADASTRAR_UNIDADE').val(),
                                sigla: $('#SIGLA_CADASTRAR_UNIDADE').val(),
                                uaaf: $('#UAAF_CADASTRAR_UNIDADE').val(),
                                cr: $('#CR_CADASTRAR_UNIDADE').val(),
                                superior: $('#SUPERIOR_CADASTRAR_UNIDADE').val(),
                                diretoria: $('#DIRETORIA_CADASTRAR_UNIDADE').val(),
                                tipo: $('#TIPO_CADASTRAR_UNIDADE').val(),
                                up: $('#UP_CADASTRAR_UNIDADE').val(),
                                codigo: $('#CODIGO_CADASTRAR_UNIDADE').val(),
                                uf: $('#UF_CADASTRAR_UNIDADE').val(),
                                email: $('#EMAIL_CADASTRAR_UNIDADE').val(),
                                uop: $('#UNIDADE_ORGAO_PRINCIPAL').val(),
                            },
                                    function(data) {
                                        if (data.success == 'true') {
                                            $('#box-nova-unidade').dialog("close");
                                            oTableUnidades.fnDraw(false);
                                            alert('Unidade cadastrada com sucesso!');
                                        } else {
                                            alert(data.error);
                                        }
                                    }, "json");
                        }
                    } else {
                        alert('Campo(s) obrigatorio(s) em branco ou preenchido(s) de forma invalida!');
                    }
                }
            }
        });

        /*Listeners*/
        $('#botao-nova-unidade').click(function() {
            $('#box-nova-unidade').dialog('open');
        });
        /*Filtro Superior Cadastrar*/
        $('#botao-filtro-superior-cadastrar-unidade').click(function() {
            $('#box-filtro-superior-cadastrar-unidade').dialog('open');
        });
        /*Filtro Diretoria Cadastrar*/
        $('#botao-filtro-diretoria-cadastrar-unidade').click(function() {
            $('#box-filtro-diretoria-cadastrar-unidade').dialog('open');
        });
        /*Filtro Cr Cadastrar*/
        $('#botao-filtro-cr-cadastrar-unidade').click(function() {
            $('#box-filtro-cr-cadastrar-unidade').dialog('open');
        });
        /*Filtro Uaaf Cadastrar*/
        $('#botao-filtro-uaaf-cadastrar-unidade').click(function() {
            $('#box-filtro-uaaf-cadastrar-unidade').dialog('open');
        });
        /*Filtro Superior Detalhar*/
        $('#botao-filtro-superior-detalhar-unidade').click(function() {
            $('#box-filtro-superior-detalhar-unidade').dialog('open');
        });
        /*Filtro Diretoria Detalhar*/
        $('#botao-filtro-diretoria-detalhar-unidade').click(function() {
            $('#box-filtro-diretoria-detalhar-unidade').dialog('open');
        });
        /*Filtro Cr Detalhar*/
        $('#botao-filtro-cr-detalhar-unidade').click(function() {
            $('#box-filtro-cr-detalhar-unidade').dialog('open');
        });
        /*Filtro Uaaf Detalhar*/
        $('#botao-filtro-uaaf-detalhar-unidade').click(function() {
            $('#box-filtro-uaaf-detalhar-unidade').dialog('open');
        });

        $('#UP_CADASTRAR_UNIDADE').change(function() {
            if ($(this).val() == 1) {
                $('#CODIGO_CADASTRAR_UNIDADE').removeAttr('disabled');
            } else {
                $('#CODIGO_CADASTRAR_UNIDADE').attr('disabled', 'disabled');
                $('#CODIGO_CADASTRAR_UNIDADE').val('');
            }
        });

        $('#UP_DETALHAR_UNIDADE').change(function() {
            if ($(this).val() == 1) {
                $('#CODIGO_DETALHAR_UNIDADE').removeAttr('disabled');
            } else {
                $('#CODIGO_DETALHAR_UNIDADE').attr('disabled', 'disabled');
                $('#CODIGO_DETALHAR_UNIDADE').val('');
            }
        });

        $('#EH_UNIDADE_ORGAO_PRINCIPAL').change(function() {
            if ($(this).val() == 0) {
                $('#UNIDADE_ORGAO_PRINCIPAL').removeAttr('disabled');
            } else {
                $('#UNIDADE_ORGAO_PRINCIPAL').attr('disabled', 'disabled');
                $('#UNIDADE_ORGAO_PRINCIPAL').val('');
            }
        });

        $('#EH_UNIDADE_ORGAO_PRINCIPAL_DETALHAR_UNIDADE').change(function() {
            if ($(this).val() == 0) {
                $('#UNIDADE_ORGAO_PRINCIPAL_DETALHAR_UNIDADE').removeAttr('disabled');
            } else {
                $('#UNIDADE_ORGAO_PRINCIPAL_DETALHAR_UNIDADE').attr('disabled', 'disabled');
                $('#UNIDADE_ORGAO_PRINCIPAL_DETALHAR_UNIDADE').val('');
            }
        });


        /*Carregar Combos*/
        $('#UF_CADASTRAR_UNIDADE').combobox('modelos/combos/ufs.php');
        $('#CODIGO_DETALHAR_UNIDADE').attr('disabled', 'disabled');
        $('#UF_DETALHAR_UNIDADE').combobox('modelos/combos/ufs.php');
        $('#TIPO_CADASTRAR_UNIDADE').combobox('modelos/combos/unidades_tipo.php', {tipo: 'tipos'});
        $('#TIPO_DETALHAR_UNIDADE').combobox('modelos/combos/unidades_tipo.php', {tipo: 'tipos'});

        $('#UNIDADE_ORGAO_PRINCIPAL').combobox('modelos/combos/orgaos_principais.php');
        $('#UNIDADE_ORGAO_PRINCIPAL_DETALHAR_UNIDADE').combobox('modelos/combos/orgaos_principais.php');

    });

    /*Functions*/
    function jquery_validar_nova_unidade(formulario) {
        if (!os_campos_estao_preenchidos(formulario)) {
            return false;
        }
        if (o_campo_up_eh_sim(formulario)) {
            if (o_campo_codigo_tem_5_numeros(formulario)) {
                return true;
            }
            return false;
        } else {
            return true;
        }
        return false;
    }

    function os_campos_estao_preenchidos(formulario) {
        if ($('#NOME_' + formulario + '_UNIDADE').val() &&
                $('#SIGLA_' + formulario + '_UNIDADE').val() &&
                $('#TIPO_' + formulario + '_UNIDADE').val() &&
                $('#UF_' + formulario + '_UNIDADE').val()
                ) {
            return true;
        } else {
            return false;
        }
    }

    function o_campo_up_eh_sim(formulario) {
        if ($('#UP_' + formulario + '_UNIDADE').val() == 1) {
            return true;
        }
        // se o campo UP não for sim, apaga o conteúdo do campo CODIGO
        $('#CODIGO_' + formulario + '_UNIDADE').val('');

        return false;
    }

    function o_campo_codigo_tem_5_numeros(formulario) {
        if (
                $('#CODIGO_' + formulario + '_UNIDADE').val().length != 5 ||
                isNaN($('#CODIGO_' + formulario + '_UNIDADE').val())
                ) {
            $('#CODIGO_' + formulario + '_UNIDADE').focus();
            return false;
        }
        return true;
    }

    function jquery_detalhar_unidades(id) {

        $.post("modelos/unidades/unidades.php", {
            acao: 'get',
            valor: id,
            campo: '*'
        },
        function(data) {
            if (data.success == 'true') {
                $('#ID_DETALHAR_UNIDADE').val(data.id);
                $('#NOME_DETALHAR_UNIDADE').val(data.nome);
                $('#SIGLA_DETALHAR_UNIDADE').val(data.sigla);
                $('#TIPO_DETALHAR_UNIDADE').val(data.tipo);
                $('#UP_DETALHAR_UNIDADE').val(data.up);
                $('#CODIGO_DETALHAR_UNIDADE').val(data.codigo);
                $('#UF_DETALHAR_UNIDADE').val(data.uf);
                $('#EMAIL_DETALHAR_UNIDADE').val(data.email);
                $('#box-detalhar-unidades').dialog('open');

                if (data.uop) {
                    //Se ela for UOP
                    if (data.uop == data.id) {
                        $('#EH_UNIDADE_ORGAO_PRINCIPAL_DETALHAR_UNIDADE').val(1);//SIM
                    }
                } else {
                    $('#EH_UNIDADE_ORGAO_PRINCIPAL_DETALHAR_UNIDADE').val(0);//NÃO
                }
                $('#EH_UNIDADE_ORGAO_PRINCIPAL_DETALHAR_UNIDADE').trigger("change");

                if (data.uaaf) {
                    $.post("modelos/unidades/unidades.php", {
                        acao: 'get',
                        valor: data.uaaf,
                        campo: '*'
                    },
                    function(dataSubQuery) {
                        if (dataSubQuery.success == 'true') {
                            var options = $('#UAAF_DETALHAR_UNIDADE').attr('options');
                            $('option', '#UAAF_DETALHAR_UNIDADE').remove();
                            options[0] = new Option(dataSubQuery.nome, dataSubQuery.id);
                        }
                    },
                            "json");
                }

                if (data.cr) {
                    $.post("modelos/unidades/unidades.php", {
                        acao: 'get',
                        valor: data.cr,
                        campo: '*'
                    },
                    function(dataSubQuery) {
                        if (dataSubQuery.success == 'true') {
                            var options = $('#CR_DETALHAR_UNIDADE').attr('options');
                            $('option', '#CR_DETALHAR_UNIDADE').remove();
                            options[0] = new Option(dataSubQuery.nome, dataSubQuery.id);
                        }
                    },
                            "json");
                }

                if (data.superior) {
                    $.post("modelos/unidades/unidades.php", {
                        acao: 'get',
                        valor: data.superior,
                        campo: '*'
                    },
                    function(dataSubQuery) {
                        if (dataSubQuery.success == 'true') {
                            var options = $('#SUPERIOR_DETALHAR_UNIDADE').attr('options');
                            $('option', '#SUPERIOR_DETALHAR_UNIDADE').remove();
                            options[0] = new Option(dataSubQuery.nome, dataSubQuery.id);
                        }
                    },
                            "json");
                }

                if (data.diretoria) {
                    $.post("modelos/unidades/unidades.php", {
                        acao: 'get',
                        valor: data.diretoria,
                        campo: '*'
                    },
                    function(dataSubQuery) {
                        if (dataSubQuery.success == 'true') {
                            var options = $('#DIRETORIA_DETALHAR_UNIDADE').attr('options');
                            $('option', '#DIRETORIA_DETALHAR_UNIDADE').remove();
                            options[0] = new Option(dataSubQuery.nome, dataSubQuery.id);
                        }
                    },
                            "json");
                }


                /*Setar tipo unidade para controlar alteracao do mesmo*/
                selected = data.tipo;

                $('#TIPO_DETALHAR_UNIDADE').die('change').live('change', function() {
                    if (selected != $(this).val()) {
                        alert('Se o tipo da unidade for alterado, o referencias de regra de tramite serao eliminadas!\nApos salvar este registro favor acessar e redefinir as regras de tramite!');
                    }
                });

            } else {
                alert('Ocorreu um erro ao tentar detalhar as informacoes da unidade!');
            }
        }, "json");

    }

    function jquery_cadastrar_unidade() {
        $('#box-pesquisa-avancada-unidades').dialog('open');
    }
</script>      
<!--Detalhar-->
<div id="box-detalhar-unidades" class="div-form-dialog" title="Detalhes da Unidade">
    <fieldset>
        <label class="label">Informações Principais</label>
        <input class="FUNDOCAIXA1" id="ID_DETALHAR_UNIDADE" type="hidden">
        <div class="row">
            <label class="label">*NOME:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="NOME_DETALHAR_UNIDADE" onkeyup="DigitaLetraSeguro(this)">
            </span>
        </div>
        <div class="row">
            <label class="label">*SIGLA:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="SIGLA_DETALHAR_UNIDADE">
            </span>
        </div>
        <div class="row">
            <label class="label">*UF:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="UF_DETALHAR_UNIDADE"></select>
            </span>
        </div>
        <div class="row">
            <label class="label">EMAIL:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="EMAIL_DETALHAR_UNIDADE">
            </span>
        </div>
        <div class="row">
            <label class="label">*UP:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="UP_DETALHAR_UNIDADE">
                    <option value="1">Sim</option>
                    <option value="0">Nao</option>
                </select>
            </span>
        </div>
        <div class="row">
            <label class="label">CODIGO:</label>
            <span class="conteudo">
                <input type="text" disabled maxlength="5" onkeyup="DigitaNumero(this)" class="FUNDOCAIXA1" id="CODIGO_DETALHAR_UNIDADE">
            </span>
        </div>

        <div class="row">
            <label class="label">*É U.O.P?:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="EH_UNIDADE_ORGAO_PRINCIPAL_DETALHAR_UNIDADE">
                    <option value="1">Sim</option>
                    <option value="0">Nao</option>
                </select>
            </span>
        </div>
        <div class="row">
            <label class="label">U.O.P:</label>
            <span class="conteudo">
                <select id="UNIDADE_ORGAO_PRINCIPAL_DETALHAR_UNIDADE" class="select" disabled="disabled">
                    <option value="0">Selecione</option>
                </select>
            </span>
        </div>

    </fieldset>

    <fieldset>
        <label class="label">Informacoes Hierarquicas</label>
        <div class="row">
            <label class="label">*TIPO DE UNIDADE:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="TIPO_DETALHAR_UNIDADE"></select>
            </span>
        </div>
        <div class="row">
            <label class="label">UAAF:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="UAAF_DETALHAR_UNIDADE"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-uaaf-detalhar-unidade" src="imagens/fam/application_edit.png">
        </div>
        <div class="row">
            <label class="label">CR:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="CR_DETALHAR_UNIDADE"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-cr-detalhar-unidade" src="imagens/fam/application_edit.png">
        </div>
        <div class="row">
            <label class="label">SUPERIOR:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="SUPERIOR_DETALHAR_UNIDADE"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-superior-detalhar-unidade" src="imagens/fam/application_edit.png">
        </div>
        <div class="row">
            <label class="label">DIRETORIA:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="DIRETORIA_DETALHAR_UNIDADE"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-diretoria-detalhar-unidade" src="imagens/fam/application_edit.png">
        </div>
    </fieldset>
</div>

<!--Cadastrar-->
<div id="box-nova-unidade" class="div-form-dialog" title="Nova Unidade">
    <fieldset>
        <label>Informações Principais</label>
        <div class="row">
            <label class="label">*NOME:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="NOME_CADASTRAR_UNIDADE" onkeyup="DigitaLetraSeguro(this)">
            </span>
        </div>
        <div class="row">
            <label class="label">*SIGLA:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="SIGLA_CADASTRAR_UNIDADE" onkeyup="DigitaLetraSeguro(this)">
            </span>
        </div>
        <div class="row">
            <label class="label">*TIPO DE UNIDADE:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="TIPO_CADASTRAR_UNIDADE"></select>
            </span>
        </div>
        <div class="row">
            <label class="label">*UF:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="UF_CADASTRAR_UNIDADE"></select>
            </span>
        </div>
        <div class="row">
            <label class="label">EMAIL:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="EMAIL_CADASTRAR_UNIDADE">
            </span>
        </div>
        <div class="row">
            <label class="label">*UP:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="UP_CADASTRAR_UNIDADE">
                    <option value="1">Sim</option>
                    <option value="0">Nao</option>
                </select>
            </span>
        </div>
        <div class="row">
            <label class="label">**CODIGO:</label>
            <span class="conteudo">
                <input type="text" maxlength="5" onkeyup="DigitaNumero(this)" class="FUNDOCAIXA1" id="CODIGO_CADASTRAR_UNIDADE">
            </span>
        </div>
        <div class="row">
            <label class="label">*É U.O.P?:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="EH_UNIDADE_ORGAO_PRINCIPAL">
                    <option value="1">Sim</option>
                    <option value="0">Nao</option>
                </select>
            </span>
        </div>
        <div class="row">
            <label class="label">U.O.P:</label>
            <span class="conteudo">
                <select id="UNIDADE_ORGAO_PRINCIPAL" class="select" disabled="disabled">
                    <option value="0">Selecione</option>
                </select>
            </span>
        </div>
    </fieldset>
    <fieldset>
        <label class="label">Informacoes Hierarquicas</label>
        <div class="row">
            <label class="label">UAAF:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="UAAF_CADASTRAR_UNIDADE"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-uaaf-cadastrar-unidade" src="imagens/fam/application_edit.png">
        </div>
        <div class="row">
            <label class="label">CR:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="CR_CADASTRAR_UNIDADE"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-cr-cadastrar-unidade" src="imagens/fam/application_edit.png">
        </div>
        <div class="row">
            <label class="label">SUPERIOR:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="SUPERIOR_CADASTRAR_UNIDADE"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-superior-cadastrar-unidade" src="imagens/fam/application_edit.png">
        </div>
        <div class="row">
            <label class="label">DIRETORIA:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="DIRETORIA_CADASTRAR_UNIDADE"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-diretoria-cadastrar-unidade" src="imagens/fam/application_edit.png">
        </div>
    </fieldset>
</div>

<!-- cadastrar superior-->
<div id="box-filtro-superior-cadastrar-unidade" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_SUPERIOR_CADASTRAR_UNIDADE" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>
<!-- cadastrar diretoria-->
<div id="box-filtro-diretoria-cadastrar-unidade" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_DIRETORIA_CADASTRAR_UNIDADE" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>
<!-- cadastrar cr-->
<div id="box-filtro-cr-cadastrar-unidade" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_CR_CADASTRAR_UNIDADE" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>
<!-- cadastrar uaaf-->
<div id="box-filtro-uaaf-cadastrar-unidade" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_UAAF_CADASTRAR_UNIDADE" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>

<!-- detalhar superior-->
<div id="box-filtro-superior-detalhar-unidade" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_SUPERIOR_DETALHAR_UNIDADE" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>
<!-- detalhar diretoria-->
<div id="box-filtro-diretoria-detalhar-unidade" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_DIRETORIA_DETALHAR_UNIDADE" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>
<!-- detalhar cr-->
<div id="box-filtro-cr-detalhar-unidade" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_CR_DETALHAR_UNIDADE" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>
<!-- detalhar uaaf-->
<div id="box-filtro-uaaf-detalhar-unidade" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_UAAF_DETALHAR_UNIDADE" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>