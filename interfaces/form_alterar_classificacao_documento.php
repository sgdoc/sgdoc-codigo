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
        /*Detalhar*/
        $('#box-alterar-classificacao').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 600,
            autoHeight: true,
            buttons: {
                Salvar: function() {
                    var validou = jquery_validar_alteracao();
                    if(validou == true){
                        $.post("modelos/classificacao/classificacao.php", {
                            acao: 'alterar-documento',
                            id_classificacao: $('#ID_CLASSIFICACAO_ALTERAR_DOCUMENTO').val(),
                            id_documento: $('#ID_ALTERAR_DOCUMENTO').val()
                        },
                        function(data){
                            if(data.success == 'true'){
                                oTableDocumentos.fnDraw(false);
                                alert(data.message);
                                $('#box-alterar-classificacao').dialog("close");
                            }else{
                                alert('Ocorreu um erro ao tentar salvar as informacoes do documento!\n['+data.error+']');
                            }
                        }, "json");
                    }else{
                        alert(validou);
                    }
                }
            }
        });
        
        /*Carregar Combos*/
        $('#ID_CLASSIFICACAO_ALTERAR_DOCUMENTO').combobox('modelos/combos/classificacoes.php', {'tipo':'filhos'});
    });
    
    /*Functions*/
    function jquery_validar_alteracao() {
        if (($('#ID_CLASSIFICACAO_ALTERAR_DOCUMENTO').val() != '0')) {
            return true;
        } else {
            return 'Selecione a classificacao do documento!';
        }

    }

    function jquery_detalhar_documentos(id, id_classificacao) {
        $('#ID_ALTERAR_DOCUMENTO').val(id);
        $('#ID_CLASSIFICACAO_ALTERAR_DOCUMENTO').val(id_classificacao);
        $('#box-alterar-classificacao').dialog('open');
    }


</script>      

<!--Detalhar-->
<div id="box-alterar-classificacao" class="div-form-dialog" title="Classificação do Documento">
    <fieldset>
        <label class="label">Informações Principais</label>
        <input class="FUNDOCAIXA1" id="ID_ALTERAR_DOCUMENTO" type="hidden">
        <div class="row">
            <label class="label">CLASSIFICACAO:</label>
            <span class="conteudo">
                <select class="FUNDOCAIXA1" id="ID_CLASSIFICACAO_ALTERAR_DOCUMENTO"></select>
            </span>
        </div>
    </fieldset>
</div>