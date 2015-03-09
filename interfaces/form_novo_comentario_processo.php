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
        /*Botao novo comentario*/
        $('#botao-novo-comentario-processo').click(function() {
            $('#form-novo-comentario-processo').dialog('open');
        });

        /*Dialog - Novo Comentario*/
        $('#form-novo-comentario-processo').dialog({
            title: 'Novo Comentário',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 400,
            autoHeight: true,
            buttons: {
                Salvar: function() {
                    if ($('#TEXTO_COMENTARIO_PROCESSO').val()) {
                        inserirComentarioProcesso();
                    }
                }
            }
        });


    });

    /*Funcoes*/
    function inserirComentarioProcesso() {
        $('#progressbar').show();
        if (confirm('Você tem certeza que inserir este novo comentário?')) {
            $.post("modelos/processos/processos.php", {
                acao: 'adicionar-comentario',
                numero_processo: '<?php echo $_GET['numero_processo']; ?>',
                texto: $('#TEXTO_COMENTARIO_PROCESSO').val()
            },
            function(data) {
                try {
                    if (data.success == 'true') {
                        $('#form-novo-comentario-processo').dialog('close');
                        $('#TEXTO_COMENTARIO_PROCESSO').val('');
                        oTabelaHistoricos.fnDraw(false);
                        $('#progressbar').hide();
                        alert('Comentário inserido com sucesso!');
                    } else {
                        $('#progressbar').hide();
                        alert(data.error);
                    }
                } catch (e) {
                    $('#progressbar').hide();
                    alert('Ocorreu um erro ao inserir o novo comentário do processo!\n[' + e + ']');
                }
            }, "json");
        } else {
            $('#progressbar').hide();
        }
    }
</script>      

<!--Cadastrar-->
<div id="form-novo-comentario-processo" class="div-form-dialog">
    <div class="row">
        <label class="label">*COMENTARIO:</label>
        <span class="conteudo">
            <textarea cols="45" rows="6" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='TEXTO_COMENTARIO_PROCESSO'></textarea>
        </span>
    </div>
</div>