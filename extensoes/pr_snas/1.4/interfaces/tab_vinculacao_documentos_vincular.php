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

        $('#link-adicionar').click(function() {
            /*Carregar digitais passiveis de vinculacao*/
            $('#FILHO_ADICIONAR_VINCULACAO_DOCUMENTO').combobox('modelos/documentos/vinculacao.php', {
                acao: 'carregar-passiveis',
                digital: '<?php echo $contexto['digital']; ?>'
            });
        });

        /*Botao salvar vinculacao*/
        $('#botao-salvar-vinculacao-documento').click(function() {
            $('#progressbar').show();
            if (confirm('Você tem certeza que deseja salvar esta vinculação agora?')) {
                $.post("modelos/documentos/vinculacao.php", {
                    acao: 'vincular',
                    pai: '<?php echo $contexto['digital']; ?>',
                    filho: $('#FILHO_ADICIONAR_VINCULACAO_DOCUMENTO').val(),
                    vinculacao: $('#TIPO_VINCULACAO_DOCUMENTO').val()
                },
                function(data) {
                    try {
                        if (data.success == 'true') {
                            /*Carregar digitais passiveis de vinculacao*/
                            $('#FILHO_ADICIONAR_VINCULACAO_DOCUMENTO').combobox('modelos/documentos/vinculacao.php', {
                                acao: 'carregar-passiveis',
                                digital: '<?php echo $contexto['digital']; ?>'
                            });
                            $('#progressbar').hide();
                            alert(data.message);
                        } else {
                            $('#progressbar').hide();
                            alert(data.error);
                        }
                    } catch (e) {
                        $('#progressbar').hide();
                        alert('Ocorreu um erro ao tentar vincular este documento!\n[' + e + ']');
                    }
                }, "json");
            } else {
                $('#progressbar').hide();
            }
        });

    });
</script>      

<div class="ui-dialog-content">
    <div class="row">
        <label>Vinculacao:</label>
        <div class="conteudo">
            <select id="TIPO_VINCULACAO_DOCUMENTO" class="FUNDOCAIXA1">
                <option value="2">Apensação</option>
                <option value="1">Anexação</option>                <option value="3">Associação</option>
            </select>
        </div>
    </div>
    <div class="row">
        <label>Digital:</label>
        <div class="conteudo">
            <select id="FILHO_ADICIONAR_VINCULACAO_DOCUMENTO" class="FUNDOCAIXA1"></select>
        </div>
    </div>
    <button id="botao-salvar-vinculacao-documento">Salvar</button>
</div>