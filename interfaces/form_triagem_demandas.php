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
        /*Detalhar Encaminhar Demandas*/
        $('#box-detalhar-encaminhar-demandas').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 650,
            autoHeight: true,
            buttons: {
                Encaminhar: function() {
                    if(confirm('Você tem certeza de que deseja encaminhar as demandas selecionadas?')){
                        $('#progressbar').show();
                        $.post("modelos/suporte/suporte.php", {
                            acao: 'encaminhar-demandas',
                            demandas: jquery_get_checked_combos('CHECK_TRIAGEM_DEMANDAS'),
                            comentario: $('#COMENTARIO_ENCAMINHAR_DEMANDAS').val(),                                       
                            atendente: $('#ATENDENTE_ENCAMINHAR_DEMANDAS').val()                                       
                        },
                        function(data){
                            if(data.success == 'true'){
                                $('#box-detalhar-encaminhar-demandas').dialog("close");
                                oTableDemandasTriagem.fnDraw(false);
                                alert(data.message);
                                $('#progressbar').hide();
                            }else{
                                alert('Ocorreu um erro ao tentar encaminhar a demanda!\n['+data.error+']');
                                $('#progressbar').hide();    
                            }
                        }, "json");
                    }
                }
            }
        });

        /*Listeners*/
        $('#botao-encaminhar-multiplas-demandas').click(function(){
            if(jquery_get_checked_combos('CHECK_TRIAGEM_DEMANDAS')){
                $('#box-detalhar-encaminhar-demandas').dialog('open');
            }else{
                alert('Nenhuma demanda foi selecionada!');
            }

        });

        /*Carregar Combo Atendentes*/
        $('#ATENDENTE_ENCAMINHAR_DEMANDAS').combobox('modelos/combos/suporte.php?tipo=atendentes', {
            tipo: 'atendentes'
        });
    });
    
    /*Functions*/
    function jquery_validar_nova_caixa(formulario){
        if(($('#ID_UNIDADE_'+formulario+'_CAIXA').val() != '0') && 
            $('#NU_'+formulario+'_CAIXA').val() &&
            $('#NU_ANO_'+formulario+'_CAIXA').val() &&
           ($('#ID_CLASSIFICACAO_'+formulario+'_CAIXA').val() != '0') ){
           
            // Foi tudo preenchido, fazer verificação ajax para validar se pode cadastrar ou não
            var id_caixa = 0;
            if (formulario == 'DETALHAR') {
                id_caixa = $('#ID_'+formulario+'_CAIXA').val();
            }
            $.ajaxSetup({async:false});
            var retorno = 0;
            $.post("modelos/caixas/caixas.php", {
                acao: 'unique',
                id: id_caixa,
                id_classificacao: $('#ID_CLASSIFICACAO_'+formulario+'_CAIXA').val(),
                nu_caixa: $('#NU_'+formulario+'_CAIXA').val(),
                id_unidade: $('#ID_UNIDADE_'+formulario+'_CAIXA').val(),
                nu_ano_caixa: $('#NU_ANO_'+formulario+'_CAIXA').val()
            },
            function(data){
                if(data.success == 'true'){
                    retorno = true;
                }else{
                    retorno = data.error;
                }
            }, "json");
            return retorno;
        } else {
            return 'Campo(s) obrigatorio(s) em branco ou preenchido(s) de forma invalida!';
        }

    }

</script>      

    <div id="box-detalhar-encaminhar-demandas" class="div-form-dialog" title="Encaminhas múltiplas demandas">
        <fieldset>
            <div class="row">
                <label class="label">ATENDENTE:</label>
                <span class="conteudo">
                    <select class="FUNDOCAIXA1" id="ATENDENTE_ENCAMINHAR_DEMANDAS"></select>
                </span>
            </div>
            <div class="row">
                <label class="label">COMENTÁRIO TRIAGEM:</label>
                <span class="conteudo">
                    <textarea onkeyup="DigitaLetraSeguro(this);" cols="72" rows="3" class="FUNDOCAIXA1" id="COMENTARIO_ENCAMINHAR_DEMANDAS"></textarea>
                </span>
            </div>
        </fieldset>
    </div>
