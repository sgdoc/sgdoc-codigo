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

<script type="text/javascript" src="javascripts/form_desentranhar_processos.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#processo_desentrenhar").val(numero_processo);
    });
</script>      

<!--Desentramento de pecas de processo-->
<h3>Termo de desentranhamento</h3>
<div id="div-form-desentrenhar">
    <div class="ui-dialog-content">
        <div class="row">
            <label for="processo_desentrenhar">* Numero processo:</label>
            <div class="conteudo">
                <input type="text" id="processo_desentrenhar" value="<?php echo $numero_processo; ?>" readonly disabled class="FUNDOCAIXA1" />
            </div>
        </div>
        <div class="row">
            <label for="pecas_desentrenhar">* Nº Pecas:</label>
            <div class="conteudo">
                <input type="text" id="pecas_desentrenhar" class="FUNDOCAIXA1" /><img title="Como preencher o campo." class="button-help" src="imagens/fam/error.png">
            </div>
        </div>
        <div class="row">
            <label for="texto-justificativa">* Justificativa:</label>
            <div class="conteudo">
                <textarea id="texto_justificativa_desentranhamento" rows="4" cols="40" onkeyup="DigitaLetraSeguro(this)" class="FUNDOCAIXA1"></textarea>
            </div>
        </div>
        <p>Atenção: A justificativa deve circundar com a seguinte frase: "Ao(s) xx/xx/xxxx, faço a retirada do presente processo da(s) peça(s) nº(s) x <strong>SUA JUSTIFICATIVA ENTRA AQUI</strong>, atendendo a solicitação do(a) xxxxxxx."</p>
        <div id="button-desentrenhar">
            <button class="button-cadastrar-desentrenhar">Cadastrar</button>
            <button class="button-cancelar-desentrenhar">Cancelar</button>
        </div>
    </div>
</div>