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

$(document).ready(function() {

    $('#boxEncaminharPrazos').dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        width: 600,
        autoHeight: true,
        open: function(event, ui) {
        	$('#tx_solicitacao').val('');
        },
        buttons: {
            Salvar: function() {
                var validou = jquery_validar_novo_prazo();
                if (validou == true) {
                    if (confirm('Você tem certeza que deseja enviar este novo prazo?\nOBS: Esta operação não poderá ser desfeita!')) {
                        $.post("extensoes/pr_snas/1.3/modelos/prazos/prazos.php", {
                            acao: 'cadastrar',
                            nu_proc_dig_ref: $('#nu_proc_dig_ref').val(),
                            id_unid_destino: $('#id_unid_destino').val(),
                            id_usuario_destino: $('#id_usuario_destino').val(),
                            dt_prazo: $('#dt_prazo').val(),
                            tx_solicitacao: $('#tx_solicitacao').val(),
                            nu_proc_dig_ref_pai: $('#nu_proc_dig_ref_pai').val()
                        },
                        function(data) {
                            try {
                            	carregarListaPrazos();

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

                                    if (acao_pesquisa != null) {
                                        window.location = "area_trabalho.php";
                                    }
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
});

</script>

<div id="boxEncaminharPrazos" class="div-form-dialog" title="Encaminhar Prazos">
    <fieldset>
        <div class="row">
            <label for="id_unid_destino_multiplo" class="label">*Setor Destino:</label>
            <span class="conteudo">
                <select id="id_unid_destino_multiplo" class="FUNDOCAIXA1"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-unidade-novo-prazo_multiplo" src="imagens/fam/application_edit.png">
        </div>
        <div class="row">
            <label for="id_usuario_destino_multiplo" class="label">Destinatário:</label>
            <span class="conteudo">
                <select id="id_usuario_destino_multiplo" class="FUNDOCAIXA1">
                    <option value="">Nenhum</option>
                </select>
            </span>
        </div>
        <div class="row">
            <label for="dt_prazo_multiplo" class="label">*Data do Prazo:</label>
            <span class="conteudo">
                <input type="text" id="dt_prazo_multiplo" class="FUNDOCAIXA1">
            </span>
        </div>
        <div class="row">
            <label for="tx_solicitacao_multiplo" class="label" style="float:left;">*Conteúdo da Solicitação:</label>
            <span class="conteudo">
                <textarea id="tx_solicitacao_multiplo" class="FUNDOCAIXA1" cols="83" rows="1" onkeyup="DigitaLetraSeguro(this)" style="height: 200;"></textarea>
            </span>
        </div>
    </fieldset>
</div>