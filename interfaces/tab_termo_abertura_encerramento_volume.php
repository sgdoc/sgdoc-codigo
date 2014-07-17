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

$contexto = Controlador::getInstance()->getContexto();
?>

<script type="text/javascript" src="javascripts/form_abertura_volume.js"></script>
<script type="text/javascript" src="javascripts/form_encerramento_volume.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#processo_abertura").val(numero_processo);

        $.post("modelos/processos/volume.php", {
            action: "prepareOpen",
            processo: numero_processo
        }, function(response) {
            if (response.success == 'true') {
                if (response.action == "open") {
                    $("#volume_abertura").val(response.volume);
                    $("#folha_abertura").val(response.folha);
                } else {
                    loadFormTermo(true);
                    $("#data_abertura").val(response.abertura);
                    $("#data_abertura").attr('disabled', 'disabled');

                    $("#volume_abertura").val(response.volume);
                    $("#folha_abertura").val(response.inicial);
                }
            }
        }, "json");
    });

    /*Funcoes*/
    /*Carregar o formulario de termos de processos*/
    function loadFormTermo(bool) {
        if (bool) {
            if ($('#abertura-volume').find('input').attr('id', 'data_encerramento').length == 0) {

                $("#abertura-volume").append("<div class='row'><label for='data_encerramento'>* Encerramento:</label><div class='conteudo'><input type='text' id='data_encerramento' readonly class='FUNDOCAIXA1' /></div></div>");
                $("#abertura-volume").append("<div class='row'><label for='folha_final_encerramento'>* Ultima folha:</label><div class='conteudo'><input type='text' maxlength='10' id='folha_final_encerramento' onKeyUp='DigitaNumero(this);' class='FUNDOCAIXA1' /></div></div>");
                $("#button-abertura").append("<button class='button-cadastrar-encerrar'>Encerrar volume</button>");

                $("#data_encerramento").datepicker({
                    changeMonth: true,
                    changeYear: true
                });

                $(".button-cadastrar-encerrar").button({
                    icons: {
                        primary: 'ui-icon-circle-check'
                    }
                }).unbind('click').click(function() {
                    closeVolume();
                });

                $("#folha_final_encerramento").click(function() {
                    $(this).removeClass("error-input-value");
                });

                $(".button-cadastrar-abertura").remove();
            }
        } else {
            $("#button-abertura").append("<button class='button-cadastrar-abertura'>Abrir volume</button>");
            $("#data_encerramento").remove();
            $("#folha_final_encerramento").remove();
        }
    }

</script>      

<!--Abertura e Encerramento de Volumes-->
<h3>Termo de abertura ou encerramento de volume</h3>
<div id="div-form-abertura">
    <div class="ui-dialog-content">
        <div class="row">
            <label for="processo_abertura">* Número processo:</label>
            <div class="conteudo">
                <input type="text" id="processo_abertura" value="<?php echo $contexto['numero_processo']; ?>" readonly disabled class="FUNDOCAIXA1" />
            </div>
        </div>
        <div class="row">
            <label for="data_abertura">* Data abertura:</label>
            <div class="conteudo">
                <input type="text" id="data_abertura" readonly class="FUNDOCAIXA1" />
            </div>
        </div>
        <div class="row">
            <label for="volume_abertura">* Volume:</label>
            <div class="conteudo">
                <input type="text" id="volume_abertura" disabled class="FUNDOCAIXA1" />
            </div>
        </div>
        <div class="row">
            <label for="folha_abertura">* Folha inicial:</label>
            <div class="conteudo">
                <input type="text" id="folha_abertura" disabled class="FUNDOCAIXA1" />
            </div>
        </div>
        <div id="abertura-volume"></div>
        <p>&nbsp;Os campos marcados com (*) s&atilde;o de preenchimento obrigat&oacute;rio!</p>
        <div id="button-abertura">
            <button class="button-cadastrar-abertura">Abrir volume</button>
        </div>
    </div>
</div>
