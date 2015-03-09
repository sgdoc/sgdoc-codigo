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

<script type="text/javascript" src="javascripts/form_desmembrar_processos.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#processo_desmembrar").val(numero_processo);
    });
</script>      

<!--Desmembrar pecas de processo-->
<h3>Termo de desmembramento</h3>
<div id="div-form-desmembrar">
    <div class="ui-dialog-content">
        <div class="row">
            <label for="processo_desmembrar">* Numero processo:</label>
            <div class="conteudo">
                <input type="text" id="processo_desmembrar" value="<?php echo $numero_processo; ?>" readonly disabled class="FUNDOCAIXA1" />
            </div>
        </div>
        <div class="row">
            <label for="diretoria_desmembrar">* Setor:</label>
            <div class="conteudo">
                <select id="diretoria_desmembrar" class="FUNDOCAIXA1"></select>
            </div>
        </div>
        <div class="row">
            <label for="filtro-diretoria-desmembrar">Filtro:</label>
            <div class="conteudo">
                <input type="text" id="filtro-diretoria-desmembrar" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1" />
            </div>
        </div>
        <div class="row">
            <label for="pecas-desmembrar">* Nº Pecas:</label>
            <div class="conteudo">
                <input type="text" id="pecas-desmembrar" class="FUNDOCAIXA1" /><img title="Como preencher o campo." class="button-help" src="imagens/fam/error.png">
            </div>
        </div>
        <div class="row">
            <label for="novo_processo_desmembrar">* Novo processo:</label>
            <div class="conteudo">
                <input type="text" id="novo_processo_desmembrar" maxlength="20"  onKeyUp="formatar_numero_processo(this)" class="FUNDOCAIXA1" />
            </div>
        </div>
        <p>&nbsp;Os campos marcados com (*) s&atilde;o de preenchimento obrigat&oacute;rio!</p>
        <div id="button-desmembrar">
            <button class="button-cadastrar-desmembrar">Cadastrar</button>
            <button class="button-cancelar-desmembrar">Cancelar</button>
        </div>
    </div>
</div>