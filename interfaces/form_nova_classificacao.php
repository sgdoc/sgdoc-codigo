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
        /*Cadastrar*/
        $('#box-nova-classificacao').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 600,
            autoHeight: true,
            open: function(event, ui) {
                $('#ID_PAI_CADASTRAR_CLASSIFICACAO').val('');
                $('#NU_CADASTRAR_CLASSIFICACAO').val('');
                $('#DS_CADASTRAR_CLASSIFICACAO').val('');
            },
            buttons: {
                Salvar: function() {
                    if (jquery_validar_nova_classificacao('CADASTRAR')) {
                        if (confirm('Você tem certeza que deseja salvar esta nova classificacao agora?')) {
                            $.post("modelos/classificacao/classificacao.php", {
                                acao: 'cadastrar',
                                ds_classificacao: $('#DS_CADASTRAR_CLASSIFICACAO').val(),
                                nu_classificacao: $('#NU_CADASTRAR_CLASSIFICACAO').val(),
                                id_classificacao_pai: $('#ID_PAI_CADASTRAR_CLASSIFICACAO').val()
                            },
                            function(data) {
                                if (data.success == 'true') {
                                    $('#box-nova-classificacao').dialog("close");
                                    oTableClassificacao.fnDraw(false);
                                    alert('Classificação cadastrada com sucesso!');
                                } else {
                                    alert('Ocorreu um erro ao tentar cadastrar a nova classificação!\n[' + data.error + ']');
                                }
                            }, "json");
                        }
                    } else {
                        alert('Campo(s) obrigatorio(s) em branco ou preenchido(s) de forma invalida!');
                    }
                }
            }
        });
        // listener

        $('#botao-nova-classificacao').click(function() {
            $('#box-nova-classificacao').dialog('open');
        });

        $('#ID_PAI_CADASTRAR_CLASSIFICACAO').combobox('modelos/combos/classificacoes.php');
    });

    /*Functions*/
    function jquery_validar_nova_classificacao(formulario) {
        if (os_campos_estao_preenchidos(formulario)) {
            if (o_campo_numero_eh_numerico(formulario)) {
                return true;
            }
        }

        return false;
    }

    function os_campos_estao_preenchidos(formulario) {
        if ($('#DS_' + formulario + '_CLASSIFICACAO').val() != '' &&
                $('#NU_' + formulario + '_CLASSIFICACAO').val() != '') {
            return true;
        } else {
            return false;
        }
    }

    function o_campo_numero_eh_numerico(formulario) {
        if (isNaN($('#NU_' + formulario + '_CLASSIFICACAO').val())) {
            $('#NU_' + formulario + '_CLASSIFICACAO').focus();
            return false;
        }
        return true;
    }

</script>      

<!--Cadastrar-->
<div id="box-nova-classificacao" class="div-form-dialog" title="Nova Classificação">
    <fieldset>
        <label>Informações Principais</label>
        <div class="row">
            <label class="label">*DESCRIÇÃO:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="DS_CADASTRAR_CLASSIFICACAO" onkeyup="DigitaLetraSeguro(this)" />
            </span>
        </div>
        <div class="row">
            <label class="label">*NUMERO:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="NU_CADASTRAR_CLASSIFICACAO" maxlength="15" />
            </span>
        </div>
        <div class="row">
            <label class="label">CLASSIFICAÇÂO PAI:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="ID_PAI_CADASTRAR_CLASSIFICACAO"></select>
            </span>
        </div>
    </fieldset>
</div>
