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
        /*Pesquisar*/
        $('#pesquisar_usuario').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 600,
            open: function (event, ui) {
                eventosPesquisar();
            },
            buttons: {
                Pesquisar: function(){
                    pesquisar();
                },
                Cancelar: function(){
                    $(this).dialog("close");
                }
            }
        });

        $('#botao-pesquisar-usuario').click(function(){
            $('#pesquisar_usuario').dialog('open');
        });
    });
    
    /*Functions*/
    function eventosPesquisar(){
        mascaras();
    }

    function pesquisar(){
        oTableUsuarios.fnSettings().sAjaxSource = "modelos/administrador/listar_usuarios.php";
        if(verificaFormularioPreenchido('#FORM_PESQUISAR')){
            oTableUsuarios.fnSettings().sAjaxSource += '?' + $('#FORM_PESQUISAR').serialize();
        }
        $('#pesquisar_usuario').dialog("close");
        oTableUsuarios.fnDraw();
    }

    /**
     ** Correcao 31/01/2013
     */
    $(document).ready(function(){
        /*Filtro Assunto*/
        $('#box-filtro-unidade-pesquisar-usuario').dialog({
            title: 'Filtro',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 380,
            height: 90,
            open: function() {
                $("#FILTRO_UNIDADE_PESQUISAR_USUARIO").val('');
            }
        });
        /*Filtro Assunto*/
        $('#botao-filtro-unidade-pesquisar-usuario').click(function(){
            $('#box-filtro-unidade-pesquisar-usuario').dialog('open');
        });
        /*Combo Unidades*/
        $("#FILTRO_UNIDADE_PESQUISAR_USUARIO").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox:'UNIDADE_PESQUISAR',
            extraParams: {
                action: 'unidades-internas',
                type: 'IN'
            }
        });
    });

</script>      

<!--Pesquisar-->
<div id="pesquisar_usuario" title="Pesquisar Usuários" class="div-form-dialog">
    <form action="javascript:;" id="FORM_PESQUISAR">
        <div>
            <label class="label">NOME:</label>
            <span class="conteudo">
                <input maxlength="100" class="FUNDOCAIXA1" type="text" name="PESQUISA[NOME]" id="NOME_PESQUISAR">
            </span>
        </div>
        <div>
            <label class="label">CPF:</label>
            <span class="conteudo">
                <input maxlength="14" type="text" id="CPF_PESQUISAR" name="PESQUISA[CPF]">
            </span>
        </div>
        <div>
            <label class="label">USUÁRIO DO SISTEMA:</label>
            <span class="conteudo">
                <input maxlength="100" class="FUNDOCAIXA1" type="text" name="PESQUISA[USUARIO]" id="USUARIO_PESQUISAR">
            </span>
        </div>
        <div>
            <label class="label">UNIDADE:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" name="PESQUISA[DIRETORIA]" id="UNIDADE_PESQUISAR"></select>
            </span>
            <img title="Filtrar" class="botao-auxiliar-fix-combobox" id="botao-filtro-unidade-pesquisar-usuario" src="imagens/fam/application_edit.png">
        </div>
        <div>
            <label class="label">EMAIL:</label>
            <span class="conteudo">
                <input maxlength="50" class="FUNDOCAIXA1" type="text" name="PESQUISA[EMAIL]" id="EMAIL_PESQUISAR">
            </span>
        </div>
        <div>
            <label class="label">SKYPE:</label>
            <span class="conteudo">
                <input class="FUNDOCAIXA1" type="text" name="PESQUISA[SKYPE]" id="SKYPE_PESQUISAR">
            </span>
        </div>
        <div>
            <label class="label">TELEFONE:</label>
            <span class="conteudo">
                <input maxlength="14" class="FUNDOCAIXA1" type="text" name="PESQUISA[TELEFONE]" id="TELEFONE_PESQUISAR">
            </span>
        </div>
    </form>
</div>


<div id="box-filtro-unidade-pesquisar-usuario" class="box-filtro">
    <div class="row">
        <label>Unidade:</label>
        <div class="conteudo">
            <input type="text" id="FILTRO_UNIDADE_PESQUISAR_USUARIO" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </div>
    </div>
</div>