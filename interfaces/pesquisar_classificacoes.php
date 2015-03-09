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
        $('#box-pesquisar-classificacoes').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 600,
            autoHeight: true,
            buttons: {
                Pesquisar: function() {
                    $.post("modelos/classificacao/classificacao.php", {
                        acao: 'pesquisar',
                        id: $('#ID_PESQUISAR_CLASSIFICACAO').val(),
                        ds_classificacao: $('#DS_PESQUISAR_CLASSIFICACAO').val(),
                        nu_classificacao: $('#NU_PESQUISAR_CLASSIFICACAO').val(),
                        id_classificacao_pai: $('#ID_PAI_PESQUISAR_CLASSIFICACAO').val()
                    },
                    function(data) {
                        if (data.success == 'true') {
                            oTableClassificacao.fnDraw(false);
                            $('#box-pesquisar-classificacoes').dialog("close");
                        } else {
                            alert('Ocorreu um erro ao tentar pesquisar as classificacoes![' + data.error + ']');
                        }
                    }, "json");
                }
            }
        });
        // listener

        $('#botao-pesquisa-avancada-classificacoes').click(function() {
            $('#box-pesquisar-classificacoes').dialog('open');
        });

        $('#ID_PAI_PESQUISAR_CLASSIFICACAO').combobox('modelos/combos/classificacoes.php');
    });


</script>      

<!--Pesquisar-->
<div id="box-pesquisar-classificacoes" class="div-form-dialog" title="Pesquisar Classificação">
    <fieldset>
        <label>Informações Principais</label>
        <div class="row">
            <label class="label">*DESCRIÇÃO:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="DS_PESQUISAR_CLASSIFICACAO" onkeyup="DigitaLetraSeguro(this)">
            </span>
        </div>
        <div class="row">
            <label class="label">*NÚMERO:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="NU_PESQUISAR_CLASSIFICACAO" maxlength="15" />
            </span>
        </div>
        <div class="row">
            <label class="label">CLASSIFICAÇÃO PAI:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="ID_PAI_PESQUISAR_CLASSIFICACAO"></select>
            </span>
        </div>
    </fieldset>
</div>
