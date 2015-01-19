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
        /*Combo Unidades*/
        $("#FILTRO_UNIDADE_DISTRIBUICAO_ETIQUETAS").autocompleteonline({
            url: 'modelos/combos/autocomplete.php',
            idComboBox:'UNIDADE_DISTRIBUICAO_ETIQUETAS',
            extraParams: {
                action: 'unidades-internas',
                type: 'IN'
            }
        });

        $('#botao-distribuicao-etiquetas').click(function(){
            $('#div-distribuicao-etiquetas').dialog('open');
        });

        $('#div-distribuicao-etiquetas').dialog({
            title: 'Ativar Etiquetas',
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 420,
            autoHeight: true,
            buttons: {
                'Ativar':function(){
                    if(confirm('Você tem certeza que deseja ativar um lote de etiquetas para a unidade escolhida?\nAtenção! este procedimento nao pode ser desfeito!')){
                        ativar();
                    }
                }
            }
        });
    });
    
    /*Functions*/
    function ativar(){
        try{
            $.post('modelos/etiquetas/ativar_etiquetas.php', {
                paginas: $('#PAGINAS_DISTRIBUICAO_ETIQUETAS').val(),
                unidade: $('#UNIDADE_DISTRIBUICAO_ETIQUETAS').val()
            }, function(data){
                $('#status-ativacao-etiquetas').text('Atencao :: Lote '+data.lote+' gerado com sucesso! ('+data.inicio+' - '+data.fim+')');
            }, 'json');
        }catch(e){
            alert(e);
        }
    }
</script>      
<!--Distribuicao-->
<div id="div-distribuicao-etiquetas" class="div-form-dialog">
    <div class="row">
        <label class="label style13">Quantidade de paginas:</label>
        <div class="conteudo">
            <select class='FUNDOCAIXA1' id='PAGINAS_DISTRIBUICAO_ETIQUETAS'>
                <?php
                for ($k = 1; $k <= 20; $k++) {
                    print("<option value='{$k}'>{$k} pagina(s) = " . ($k * 65) . " Etiquetas.</option>");
                }
                ?>
            </select>
        </div>
    </div>

    <div class="row">
        <label class="label style13">Filtro:</label>
        <div class="conteudo">
            <input type="text" class='FUNDOCAIXA1' id='FILTRO_UNIDADE_DISTRIBUICAO_ETIQUETAS'>
        </div>
    </div>

    <div class="row">
        <label class="label style13">Unidade:</label>
        <div class="conteudo">
            <select class='FUNDOCAIXA1' id='UNIDADE_DISTRIBUICAO_ETIQUETAS'></select>
        </div>
    </div>

    <div class="row">
        <br>
        <div class="conteudo" id="status-ativacao-etiquetas"></div>
    </div>
</div>
