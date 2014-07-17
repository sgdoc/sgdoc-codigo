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

<script type="text/javascript" src="javascripts/form_processos_apensar_anexar.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#processo_apensar").val(numero_processo);
    });
</script>      

<!--Apensacao e Anexacao de processos-->
<h3>Termo de juntada por <strong>Apensação ou Anexação</strong></h3>
<div id="div-form-apensar">
    <div class="ui-dialog-content">
        <div class="row">
            <label for="tip_vincular">* Modelo de termo:</label>
            <div class="conteudo">
                <select class="FUNDOCAIXA1" id="tip_vincular">
                    <option value="anexar">Anexação</option>
                    <option value="apensar">Apensação</option>
                </select>
            </div>
        </div>
        <div class="row">
            <label for="processo_apensar">* Numero processo:</label>
            <div class="conteudo">
                <input type="text" id="processo_apensar" value="<?php echo $numero_processo; ?>" readonly disabled class="FUNDOCAIXA1" />
            </div>
        </div>
        <div class="row">
            <label for="data_apensar">* Data do pedido:</label>
            <div class="conteudo">
                <input type="text" id="data_apensar" readonly  onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1" />
            </div>
        </div>
        <div class="row">
            <label for="diretoria_apensar">* Setor:</label>
            <div class="conteudo">
                <select id="diretoria_apensar" class="FUNDOCAIXA1"></select>
            </div>
        </div>
        <div class="row">
            <label for="filtro-diretoria-apensar">Filtro:</label>
            <div class="conteudo">
                <input type="text" id="filtro-diretoria-apensar" class="FUNDOCAIXA1" />
            </div>
        </div>
        <div class="row">
            <label for="processo_0">* Processos:</label>
            <div class="conteudo">
                <input type="text" id="processo_0" maxlength="20" class="FUNDOCAIXA1" onKeyUp="formatar_numero_processo(this);" /><img title="Apensar novo processo" class="button-adicionar-apensar" src="imagens/fam/add.png" />
            </div>
        </div>
        <div id="apensar-processos"></div>
        <div id="button-apensar">
            <button class="button-cadastrar-apensar">Cadastrar</button>
            <button class="button-cancelar-apensar">Cancelar</button>
        </div>
    </div>
</div>