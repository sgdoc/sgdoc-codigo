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

<script type="text/javascript" src="javascripts/form_processos_desapensar.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#processo_desapensar").val(numero_processo);
    });
</script>      

<!--Desanexacao de processos-->
<h3>Termo de desapensação</h3>
<div id="div-form-desapensar">
    <div class="ui-dialog-content">
        <div class="row">
            <label for="processo_desapensar">* Numero processo:</label>
            <div class="conteudo">
                <input type="text" id="processo_desapensar" value="<?php echo $numero_processo; ?>" readonly disabled class="FUNDOCAIXA1" />
            </div>
        </div>
        <div class="row">
            <label for="data_desapensar">* Data do pedido:</label>
            <div class="conteudo">
                <input type="text" id="data_desapensar" readonly onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1" />
            </div>
        </div>
        <div class="row">
            <label for="diretoria_desapensar">* Setor:</label>
            <div class="conteudo">
                <select id="diretoria_desapensar" class="FUNDOCAIXA1"></select>
            </div>
        </div>
        <div class="row">
            <label for="filtro-diretoria-desapensar">Filtro:</label>
            <div class="conteudo">
                <input type="text" id="filtro-diretoria-desapensar" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1" />
            </div>
        </div>
        <p>Atenção: Remova da lista abaixo o(s) processo(s) que deverão ser desapensado(s).</p>
        <div id="desapensar-processos"></div>
        <div id="button-desapensar">
            <button class="button-cadastrar-desapensar">Desapensar</button>
        </div>
    </div>
</div>