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

    var jquery_validar_novo_prazo = function() {
        if ($('#tx_solicitacao').val() != '' &&
                $('#dt_prazo').val() != '' &&
                $('#id_unid_destino').val() != '' &&
                ($('#tipo_ref').val() == 'N' || $('#nu_proc_dig_ref').val() != ''))
        {
            return true;
        } else {
            return 'Um ou mais campos obrigatorios estao em branco';
        }
    };

    var listarUsuarios = function(unidade) {
        $.getJSON("modelos/combos/mostrar_usuarios_unidades.php", {
            diretoria: unidade,
            request: 'usuarios'
        }, function(data) {
            if (data) {
                var newOption = $('<option>');
                newOption.html('Nenhum');
                newOption.attr('value', '');
                $('#id_usuario_destino').append(newOption);
                $.each(data.usuarios, function(i) {
                    var newOption = $('<option>');
                    newOption.html(data.usuarios[i].nome);
                    newOption.attr('value', data.usuarios[i].id);
                    $('#id_usuario_destino').append(newOption);
                });
            } else {
                alert('Esta unidade não possui usuários cadastrados!');
            }
        });
    }

    $(document).ready(function() {
        /*Cadastrar*/
        $('#box-novo-prazo').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 600,
            autoHeight: true,
            open: function(event, ui) {
            },
            buttons: {
                Salvar: function() {
                    var validou = jquery_validar_novo_prazo();
                    if (validou == true) {
                        if (confirm('Você tem certeza que deseja enviar este novo prazo?\nOBS: Esta operação não poderá ser desfeita!')) {
                            $.post("modelos/prazos/prazos.php", {
                                acao: 'cadastrar',
                                nu_proc_dig_ref: $('#nu_proc_dig_ref').val(),
                                id_unid_destino: $('#id_unid_destino').val(),
                                id_usuario_destino: $('#id_usuario_destino').val(),
                                dt_prazo: $('#dt_prazo').val(),
                                tx_solicitacao: $('#tx_solicitacao').val()
                            },
                            function(data) {
                                try {
                                    if (data.success == 'true') {
                                        $("#box-novo-prazo").dialog("close");
                                        $('#tabs-prazos-env').click();
                                        $('#tipo_ref').val('N');
                                        $('#nu_proc_dig_ref').val('');
                                        $('#nu_proc_dig_ref').attr('disabled', 'disabled');
                                        $('#id_unid_destino').val('');
                                        $('#id_usuario_destino').val('');
                                        $('#dt_prazo').val('');
                                        $('#tx_solicitacao').val('');
                                        $('#progressbar').hide();
                                        alert('Novo prazo cadastrado com sucesso!');
                                    } else {
                                        $('#progressbar').hide();
                                        alert(data.error);
                                    }
                                } catch (e) {
                                    $('#progressbar').hide();
                                    alert('Ocorreu um erro ao inserir um novo prazo:\n[' + e + ']');
                                }
                            }, "json");
                        } else {
                            $('#progressbar').hide();
                        }
                    } else {
                        alert(validou);
                    }
                }
            }
        });

        // listener
        $('#botao-novo-prazo').click(function() {
            $('#box-novo-prazo').dialog('open');
        });

        $("#dt_prazo").datepicker({
            minDate: 0
        });

        $('#tipo_ref').change(function() {
            var o = $('#tipo_ref').val();
            if (o == 'D') {
                $('#nu_proc_dig_ref').removeAttr('disabled');
                $('#nu_proc_dig_ref').attr('maxLength', '7');
            } else if (o == 'P') {
                $('#nu_proc_dig_ref').removeAttr('disabled');
                $('#nu_proc_dig_ref').attr('maxLength', '20');
            } else if (o == 'N') {
                $('#nu_proc_dig_ref').attr('disabled', 'disabled');
                $('#nu_proc_dig_ref').val('');
            }
        });

        $('#id_unid_destino').change(function(e) {
            $('#id_usuario_destino').empty();
            listarUsuarios($('#id_unid_destino').val());
        });

        /*Filtro Unidade*/
        $('#box-filtro-unidade-novo-prazo').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#FILTRO_UNIDADE_NOVO_PRAZO").val('');
            }
        });

        $('#botao-filtro-unidade-novo-prazo').click(function() {
            $('#box-filtro-unidade-novo-prazo').dialog('open');
        });
        /*Combo Unidades*/
        $("#FILTRO_UNIDADE_NOVO_PRAZO").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox: 'id_unid_destino',
            extraParams: {
                action: 'unidades-internas',
                type: 'IN'
            },
            onFinish: function() {
                $('#id_usuario_destino').empty();
                listarUsuarios($('#id_unid_destino').val());
            }
        });

    });

</script>      


<div id="box-novo-prazo" class="div-form-dialog" title="Novo Prazo">
    <fieldset>
        <label>Informações Principais</label>
        <div class="row">
            <label for="tipo_ref" class="label">*Tipo Referência:</label>
            <span class="conteudo">
                <select id="tipo_ref" class="FUNDOCAIXA1">
                    <option value="N" selected="selected">Selecione</option>
                    <option value="D">Digital</option>
                    <option value="P">Processo</option>
                </select>
            </span>
        </div>
        <div class="row">
            <label for="nu_proc_dig_ref" class="label">*Número Ref:</label>
            <span class="conteudo">
                <input type="text" disabled="disabled" id="nu_proc_dig_ref" class="FUNDOCAIXA1">
            </span>
        </div>
        <div class="row">
            <label for="id_unid_destino" class="label">*Setor Destino:</label>
            <span class="conteudo">
                <select id="id_unid_destino" class="FUNDOCAIXA1"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-unidade-novo-prazo" src="imagens/fam/application_edit.png">
        </div>
        <div class="row">
            <label for="id_usuario_destino" class="label">Destinatário:</label>
            <span class="conteudo">
                <select id="id_usuario_destino" class="FUNDOCAIXA1">
                    <option value="">Nenhum</option>
                </select>
            </span>
        </div>
        <div class="row">
            <label for="dt_prazo" class="label">*Data do Prazo:</label>
            <span class="conteudo">
                <input type="text" id="dt_prazo" class="FUNDOCAIXA1">
            </span>
        </div>
        <div class="row">
            <label for="tx_solicitacao" class="label" style="float:left;">*Conteúdo da Solicitação:</label>
            <span class="conteudo">
                <textarea id="tx_solicitacao" class="FUNDOCAIXA1" cols="60" rows="1" onkeyup="DigitaLetraSeguro(this)" style="height: 200;"></textarea>
            </span>
        </div>
    </fieldset>
</div>

<div id="box-filtro-unidade-novo-prazo" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_UNIDADE_NOVO_PRAZO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>
