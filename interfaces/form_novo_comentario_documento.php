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
        $('#botao-novo-comentario-documento').click(function() {
            $('#form-novo-comentario-documento').dialog('open');
        });

        /*Dialog - Novo Comentario*/
        $('#form-novo-comentario-documento').dialog({
            title: 'Novo Comentário',
            autoOpen: false,
            resizable: false,
            modal: false,
            width: 400,
            autoHeight: true,
            buttons: {
                Salvar: function() {
                    if ($('#TEXTO_COMENTARIO_DOCUMENTO').val()) {
                        inserirComentarioDocumento();
                    }
                }
            }
        });

    });

    /*Functions*/
    function inserirComentarioDocumento() {
        $('#progressbar').show();
        if (confirm('Você tem certeza que inserir este novo comentário?')) {
            $.post("modelos/documentos/documentos.php", {
                acao: 'adicionar-comentario',
                digital: '<?php echo $_GET['digital']; ?>',
                texto: $('#TEXTO_COMENTARIO_DOCUMENTO').val()
            },
            function(data) {
                try {
                    if (data.success == 'true') {
                        $('#form-novo-comentario-documento').dialog('close');
                        $('#TEXTO_COMENTARIO_DOCUMENTO').val('');
                        oTabelaHistoricos.fnDraw(false);
                        $('#progressbar').hide();
                        alert('Comentário inserido com sucesso!');
                    } else {
                        $('#progressbar').hide();
                        alert(data.error);
                    }
                } catch (e) {
                    $('#progressbar').hide();
                    alert('Ocorreu um erro ao inserir o novo comentário do documento!\n[' + e + ']');
                }
            }, "json");
        } else {
            $('#progressbar').hide();
        }
    }
</script>      

<!--Cadastrar-->
<div id="form-novo-comentario-documento" class="div-form-dialog">
    <div class="row">
        <label class="label">*COMENTARIO:</label>
        <span class="conteudo">
            <textarea cols="45" rows="6" class="FUNDOCAIXA1" onkeyup="DigitaLetraSeguro(this)" id='TEXTO_COMENTARIO_DOCUMENTO'></textarea>
        </span>
    </div>
</div>