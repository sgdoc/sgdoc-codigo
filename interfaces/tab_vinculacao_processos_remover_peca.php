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

        /*Carregar processos anexados*/
        $('#link-remover-peca').click(function() {
            $('#DIGITAL_REMOVER_PECA_PROCESSO').combobox('modelos/processos/vinculacao.php', {
                acao: 'carregar-pecas',
                numero_processo: '<?php print($contexto['numero_processo']); ?>'
            });
        });

        /*Botao remover peca processo*/
        $('#botao-remover-nova-peca-processo').click(function() {
            if ($('#DIGITAL_REMOVER_PECA_PROCESSO').val()) {
                $('#progressbar').show();
                if (confirm('Você tem certeza que deseja remover esta peça do processo agora?')) {
                    $.post("modelos/processos/vinculacao.php", {
                        acao: 'remover-peca',
                        numero_processo: '<?php echo $contexto['numero_processo']; ?>',
                        digital: $('#DIGITAL_REMOVER_PECA_PROCESSO').val()
                    },
                    function(data) {
                        try {
                            if (data.success == 'true') {
                                /*Carregar digitais passiveis de vinculacao*/
                                $('#DIGITAL_REMOVER_PECA_PROCESSO').combobox('modelos/processos/vinculacao.php', {
                                    acao: 'carregar-pecas',
                                    numero_processo: '<?php echo $contexto['numero_processo']; ?>'
                                });
                                $('#progressbar').hide();
                                alert(data.message);
                            } else {
                                $('#progressbar').hide();
                                alert(data.error);
                            }
                        } catch (e) {
                            $('#progressbar').hide();
                            alert('Ocorreu um erro ao tentar remover a peca deste processo!\n[' + e + ']');
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
        <label>Digital:</label>
        <div class="conteudo">
            <select id="DIGITAL_REMOVER_PECA_PROCESSO" class="FUNDOCAIXA1"></select>
        </div>
    </div>
    <button id="botao-remover-nova-peca-processo">Remover</button>
</div>