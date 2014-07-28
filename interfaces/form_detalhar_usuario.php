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
    .botao-auxiliar-recuo-fix-combobox{
        vertical-align: middle;
        margin-left: -40px;
        position: absolute;
        margin-top: 7px;

    }
</style>

<div id="detalhar_usuario" title="Detalhar Usuário" class="div-form-dialog">
    <form action="javascript:;" id="FORM_DETALHAR_USUARIO">
        <input type="hidden" name="USUARIO[ID]" id="ID_DETALHAR_USUARIO">
        <div>
            <label class="label">*NOME:</label>
            <span class="conteudo">
                <input maxlength="100" class="FUNDOCAIXA1 required" type="text" name="USUARIO[NOME]" id="NOME_DETALHAR_USUARIO">
            </span>
        </div>
        <div>
            <label class="label">*CPF:</label>
            <span class="conteudo">
                <input maxlength="14" class="FUNDOCAIXA1 required" type="text" id="CPF_DETALHAR_USUARIO" name="USUARIO[CPF]">
            </span>
        </div>
        <div>
            <label class="label">*USUÁRIO DO SISTEMA:</label>
            <span class="conteudo">
                <input maxlength="100" class="FUNDOCAIXA1 required" type="text" name="USUARIO[USUARIO]" id="USUARIO_DETALHAR_USUARIO">
            </span>
        </div>
        <div>
            <label class="label">*EMAIL:</label>
            <span class="conteudo">
                <input maxlength="50" class="FUNDOCAIXA1 required" type="text" name="USUARIO[EMAIL]" id="EMAIL_DETALHAR_USUARIO">
            </span>
        </div>
        <div>
            <label class="label">SKYPE:</label>
            <span class="conteudo">
                <input class="FUNDOCAIXA1" type="text" name="USUARIO[SKYPE]" id="SKYPE_DETALHAR_USUARIO">
            </span>
        </div>
        <div>
            <label class="label">*TELEFONE:</label>
            <span class="conteudo">
                <input maxlength="14" class="FUNDOCAIXA1 required" type="text" name="USUARIO[TELEFONE]" id="TELEFONE_DETALHAR_USUARIO">
            </span>
        </div>
        <hr>
        <div class="row">
            <label class="label">*UNIDADE(S):</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="UNIDADE_DETALHAR_USUARIO"></select>
            </span>
            <img title="Adicionar" class="botao-auxiliar-fix-combobox" id="botao-adicionar-unidade-usuario" src="imagens/fam/add.png">
            <img title="Filtrar" class="botao-auxiliar-recuo-fix-combobox" id="botao-filtro-unidade-novo-usuario" src="imagens/fam/application_edit.png">
        </div>
        <div class="row" id="container-unidades-usuario"></div>
    </form>
</div>

<div id="box-filtro-unidade-novo-usuario" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_UNIDADE_NOVO_USUARIO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>

<script type="text/javascript">
                var cmdNewUser = false;

                /*Template*/
                var tmpl = '<span class="conteudo">'
                        + '<select class="FUNDOCAIXA1" name="USUARIO[UNIDADE][]"><option selected="selected" value="%d">%s</option></select>'
                        + '<img title="Remover" class="botao-auxiliar-fix-combobox botao-remover-unidade-usuario" src="imagens/fam/delete.png">'
                        + '</span>';

                $(document).ready(function() {


                    /*Detalhar Usuário*/
                    $('#detalhar_usuario').dialog({
                        autoOpen: false,
                        resizable: false,
                        modal: true,
                        width: 600,
                        close: function() {
                            $('#UNIDADE_DETALHAR_USUARIO').empty();
                        },
                        buttons: {
                            Salvar: function() {
                                if ($("#FORM_DETALHAR_USUARIO").valid()) {
                                    if (confirm('Você tem certeza que deseja salvar?')) {
                                        $.post("modelos/usuarios/usuarios.php",
                                                $('#FORM_DETALHAR_USUARIO').serialize() + '&acao=salvar',
                                                function(data) {
                                                    if (data.success === 'true') {
                                                        $('#detalhar_usuario').dialog('close');
                                                        oTableUsuarios.fnDraw();
                                                        alert(data.message);
                                                    } else {
                                                        alert(data.error);
                                                    }
                                                }, "json");
                                    }
                                }
                            },
                            Cancelar: function() {
                                $(this).dialog("close");
                            }
                        }
                    });
                    /*Listeners*/
                    $('#botao-adicionar-usuario').click(function() {
                        cmdNewUser = true;
                        limparFormulario('#FORM_DETALHAR_USUARIO');
                        $('#detalhar_usuario').dialog('open');
                    });
                    $("#FORM_DETALHAR_USUARIO").validate({
                        errorPlacement: function(error, element) {
                            for (i = 0; i < element.length; i++) {
                                $(element[i]).addClass('campo-error');
                                $(element[i]).blur(function() {
                                    $(this).removeClass('campo-error');
                                });
                            }
                        }
                    });

                    maskFieldsUser();

                    /*Filtro Assunto*/
                    $('#box-filtro-unidade-novo-usuario').dialog({
                        title: 'Filtro',
                        autoOpen: false,
                        resizable: false,
                        modal: false,
                        width: 380,
                        height: 90,
                        open: function() {
                            $("#FILTRO_UNIDADE_NOVO_USUARIO").val('');
                        }
                    });
                    /*Filtro Assunto*/
                    $('#botao-filtro-unidade-novo-usuario').click(function() {
                        $('#box-filtro-unidade-novo-usuario').dialog('open');
                    });
                    /*Combo Unidades*/
                    $("#FILTRO_UNIDADE_NOVO_USUARIO").autocompleteonline({
                        url: 'modelos/combos/autocomplete.php',
                        idComboBox: 'UNIDADE_DETALHAR_USUARIO',
                        extraParams: {
                            action: 'unidades-internas',
                            type: 'IN'
                        }
                    });
                    /*Remover*/
                    $('.botao-remover-unidade-usuario').live('click', function() {
                        $(this).parent('.conteudo').empty();
                    });
                    /*Adicionar*/
                    $('#botao-adicionar-unidade-usuario').click(function() {
                        if (!$('#UNIDADE_DETALHAR_USUARIO').val()) {
                            return false;
                        }
                        $('#container-unidades-usuario').append(tmpl.replace('%d', $('#UNIDADE_DETALHAR_USUARIO').val()).replace('%s', $('#UNIDADE_DETALHAR_USUARIO option:selected').text()));
                    });
                });

</script>