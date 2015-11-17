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

<script type="text/javascript">
    $(document).ready(function() {

        /*Carregar documentos apensados*/
        $('#link-desassociar').click(function(){
            $('#FILHO_DESASSOCIAR_DOCUMENTO').combobox('modelos/documentos/vinculacao.php',{
                acao: 'carregar-vinculados',
                pai: '<?php echo $contexto['digital']; ?>',
                vinculacao: 3
            });
        });
        
        /*Botao desassociar*/
        $('#botao-desassociar-documento').click(function(){
            jquery_desvincular_documento('<?php echo $contexto['digital']; ?>',$('#FILHO_DESASSOCIAR_DOCUMENTO').val(),3/*Associação*/);
        });

    });
</script>

<div class="ui-dialog-content">
    <div class="row">
        <label>Digital:</label>
        <div class="conteudo">
            <select id="FILHO_DESASSOCIAR_DOCUMENTO" class="FUNDOCAIXA1"></select>
        </div>
    </div>
    <button id="botao-desassociar-documento">Desassociar</button>
</div>