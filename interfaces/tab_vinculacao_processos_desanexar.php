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

        /*Carregar processos anexados*/
        $('#link-remover-anexo').click(function() {
            $('#PROCESSO_REMOVER_ANEXO_PROCESSO').combobox('modelos/processos/vinculacao.php', {
                acao: 'carregar-anexos',
                numero_processo: '<?php print($_REQUEST['numero_processo']); ?>'
            });
        });

        /**
         *Botao desanexar processo
         */
        $('#botao-remover-anexo-processo').click(function() {
            if ($('#PROCESSO_REMOVER_ANEXO_PROCESSO').val()) {
                // $('#progressbar').show();
                if (confirm('Você tem certeza que deseja desanexar este processo agora?')) {
                    $.post("modelos/processos/vinculacao.php", {
                        acao: 'desanexar',
                        numero_processo: '<?php print($_REQUEST['numero_processo']); ?>',
                        anexo: $('#PROCESSO_REMOVER_ANEXO_PROCESSO').val()
                    },
                    function(data) {
                        try {
                            if (data.success == 'true') {
                                /*Carregar processo anexos*/
                                $('#PROCESSO_REMOVER_ANEXO_PROCESSO').combobox('modelos/processos/vinculacao.php', {
                                    acao: 'carregar-anexos',
                                    numero_processo: '<?php print($_REQUEST['numero_processo']); ?>'
                                });
                                $('#progressbar').hide();
                                alert(data.message);
                            } else {
                                $('#progressbar').hide();
                                alert(data.error);
                            }
                        } catch (e) {
                            $('#progressbar').hide();
                            alert('Ocorreu um erro ao tentar desanexar processo!\n[' + e + ']');
                        }
                    }, "json");
                } else {
                    $('#progressbar').hide();
                }
            }
        });

    });
</script>      

<div class="ui-dialog-content">
    <div class="row">
        <label>Processo:</label>
        <div class="conteudo">
            <select id="PROCESSO_REMOVER_ANEXO_PROCESSO" class="FUNDOCAIXA1"></select>
        </div>
    </div>
    <button id="botao-remover-anexo-processo">Remover</button>
</div>
