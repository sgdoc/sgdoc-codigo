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
        $('#box-pesquisar-documentos-fora').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 600,
            autoHeight: true,
            buttons: {
                Pesquisar: function() {
                    $.post("modelos/classificacao/classificacao.php", {
                        acao: 'pesquisar-documentos',
                        id_classificacao: $('#ID_CLASSIFICACAO_PESQUISAR_DOCUMENTO').val(),
                        digital: $('#DIGITAL_PESQUISAR_DOCUMENTO').val(),
                        processo: $('#NUMERO_PROCESSO_PESQUISAR_DOCUMENTO').val(),
                        assunto: $('#ASSUNTO_PESQUISAR_DOCUMENTO').val()
                    },
                    function(data){
                        if(data.success == 'true'){
                            oTableDocumentos.fnDraw(false);
                            $('#box-pesquisar-documentos').dialog("close");
                        }else{
                            alert('Ocorreu um erro ao tentar pesquisar documentos!\n['+data.error+']');
                        }
                    }, "json");

                }
            }
        });
        /*Listeners*/
        $('#botao-pesquisa-avancada-documentos').click(function(){
            $('#box-pesquisar-documentos-fora').dialog('open');
        });

        $('#ID_CLASSIFICACAO_PESQUISAR_DOCUMENTO').combobox('modelos/combos/classificacoes.php', {'tipo':'filhos'});
    });
    
    /*Functions*/
    function jquery_pesquisa_avancada_documentos(){
        $('#box-pesquisa-avancada-documentos').dialog('open');
    }


</script>      

<!--Pesquisar-->
<div id="box-pesquisar-documentos-fora" class="div-form-dialog" title="Pesquisar Documentos">
    <fieldset>
        <label>Informações Principais</label>
        <div class="row">
            <label class="label">CLASSIFICACAO:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="ID_CLASSIFICACAO_PESQUISAR_DOCUMENTO"></select>
            </span>
        </div>
        <div class="row">
            <label class="label">DIGITAL:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="DIGITAL_PESQUISAR_DOCUMENTO" maxlength="7" onkeyup="DigitaNumero(this)" />
            </span>
        </div>
        <div class="row">
            <label class="label">PROCESSO:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="NUMERO_PROCESSO_PESQUISAR_DOCUMENTO" onkeyup="DigitaNumero(this)" />
            </span>
        </div>
        <div class="row">
            <label class="label">ASSUNTO:</label>
            <span class="conteudo">
                <input type="text" class="FUNDOCAIXA1" id="ASSUNTO_PESQUISAR_DOCUMENTO" onkeyup="DigitaLetraSeguro(this)" />
            </span>
        </div>
    </fieldset>
</div>