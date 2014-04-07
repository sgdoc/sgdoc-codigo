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
        $('#box-pesquisar-documentos-dentro').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 600,
            autoHeight: true,
            buttons: {
                Pesquisar: function() {
                    $.post("modelos/caixas/caixas.php", {
                        acao: 'pesquisar-documentos',
                        id_classificacao: $('#ID_CLASSIFICACAO_PESQUISAR_DOCUMENTOS').val(),
                        digital: $('#DIGITAL_PESQUISAR_DOCUMENTOS').val(),
                        numero: $('#NUMERO_NUMERO_PESQUISAR_DOCUMENTOS').val(),
                        assunto: $('#ASSUNTO_PESQUISAR_DOCUMENTOS').val(),
                        assinatura: $('#ASSINATURA_PESQUISAR_DOCUMENTOS').val(),
                        destino: $('#DESTINO_PESQUISAR_DOCUMENTOS').val()
                    },
                    function(data){
                        if(data.success == 'true'){
                            oTableCaixasDocs.fnDraw(false);
                            $('#box-pesquisar-documentos').dialog("close");
                        }else{
                            alert('Ocorreu um erro ao tentar pesquisar os documentos\n!['+data.error+']');
                        }
                    }, "json");
                }
            }
        });
        /*Listeners*/
        $('#botao-pesquisa-avancada-documentos-dentro').click(function(){
            $('#box-pesquisar-documentos-dentro').dialog('open');
        });

        /*Carregar Combos*/
        $('#ID_CLASSIFICACAO_PESQUISAR_DOCUMENTOS').combobox('modelos/combos/classificacoes.php', {'tipo':'filhos'});
    });
    
    /*Functions*/
    function jquery_pesquisa_avancada_documentos(){
        $('#box-pesquisa-avancada-documentos-dentro').dialog('open');
    }


</script>      

<!--Pesquisar-->
<div id="box-pesquisar-documentos-dentro" class="div-form-dialog" title="Pesquisar Documentos">
    <fieldset>
        <label>Informações Principais</label>
        <div class="row">
            <label class="label">CLASSIFICACAO:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="ID_CLASSIFICACAO_PESQUISAR_DOCUMENTOS"></select>
            </span>
        </div>
        <div class="row">
            <label class="label">DIGITAL:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="DIGITAL_PESQUISAR_DOCUMENTOS" maxlength="7" onkeyup="DigitaNumero(this)" />
            </span>
        </div>
        <div class="row">
            <label class="label">NUMERO:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="NUMERO_NUMERO_PESQUISAR_DOCUMENTOS" />
            </span>
        </div>
        <div class="row">
            <label class="label">ASSUNTO:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="ASSUNTO_PESQUISAR_DOCUMENTOS" onkeyup="DigitaLetraSeguro(this)" />
            </span>
        </div>
        <div class="row">
            <label class="label">ASSINATURA:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="ASSINATURA_PESQUISAR_DOCUMENTOS" onkeyup="DigitaLetraSeguro(this)" />
            </span>
        </div>
        <div class="row">
            <label class="label">DESTINO:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="DESTINO_PESQUISAR_DOCUMENTOS" onkeyup="DigitaLetraSeguro(this)" />
            </span>
        </div>
    </fieldset>
</div>