
<!--/*
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
 * */-->
<?php
$auth = Zend_Auth::getInstance()->getStorage()->read();
?>
<script type="text/javascript">

    $(document).ready(function() {
        /*Comentario de volumes de processos*/
        $("#botao-comentar-volume-processo").click(function() {
            $("#comentar-volume-dialog").dialog('open');
        });

        /*AutoComplete*/
        $("#filtro-diretoria-comentar").autocompleteonline({
            idComboBox: 'diretoria_comentar',
            url: 'modelos/combos/autocomplete.php',
            extraParams: {
                action: 'unidades-internas',
                type: 'IN'
            }
        });

        /*Listeners*/
        /*Combos*/
        $("#diretoria_comentar").click(function() {
            comboUnidUser();
        });

        /*Dialogs*/
        /*Comentar Volume*/
        $(".comentar-volume").dialog({
            resizable: false,
            autoOpen: false,
            width: 500,
            autoHeight: 230,
            modal: true,
            title: "Inserir comentário",
            open: function() {
                $('.ui-dialog-buttonpane').find('button:contains("Salvar")').button({
                    icons: {
                        primary: 'ui-icon-circle-check'
                    }
                });
            },
            buttons: {
                Salvar: function() {
                    /*Validar se usuario e unidade forao informados*/
                    if (!$("#diretoria_comentar").val() || !$("#usuario_comentar").val()) {
                        alert('Informe a unidade e o usuário responsável por este volume!');
                        return false;
                    }

                    $.post("modelos/processos/volume.php", {
                        processo: $('#NUMERO_DETALHAR_PROCESSO').val(),
                        idvolume: $('#ID_VOLUME_PROCESSO').val(),
                        volume: $('#NUMERO_VOLUME_PROCESSO').val(),
                        setor: $("#diretoria_comentar").val(),
                        usuario: $("#usuario_comentar").val(),
                        action: 'comment'
                    }, function(response) {
                        try {
                            if (response.success == 'true') {
                                alert("Comentário salvo com sucesso!");
                                $(".comentar-volume").dialog('close');
                                $('#comentar-volume-dialog').dialog('close');
                            } else {
                                alert("Atenção! Não foi possível concluir sua solicitação, tente novamente mais tarde ou contate o administrador.");
                            }
                        } catch (e) {
                            alert('Atencao!, Ocorreu um erro inesperado em sua solicitação, tente novamente mais tarde ou contate o administrador.');
                        }
                    }, "json");
                }
            }
        });

        /*Lista Comentarios Volumes*/
        $("#comentar-volume-dialog").dialog({
            autoOpen: false,
            modal: true,
            title: "Comentar volumes do processo",
            width: 370,
            height: 400,
            resizable: false,
            open: function() {

                $('.ui-dialog-buttonpane').find('button:contains("Cancelar")').button({
                    icons: {
                        primary: 'ui-icon-circle-close'
                    }
                });

                $.post("modelos/processos/volume.php", {
                    action: "listar",
                    processo: $('#NUMERO_DETALHAR_PROCESSO').val()
                }, function(response) {
                    if (response.success == 'true') {
                        $(".listar-volume").html('');
                        $.each(response.data, function(index, data) {
                            $(".listar-volume")
                                    .append("<div id='volume-" + data.id + "' class='volume-lista' onclick = clickComentar('" + data.id + "','" + data.volume + "');><b>" + data.volume + "&#176; Volume - " + data.quant + " folhas</b>"
                                    + "<br><label id='label-volume'><b>Aberto em:</b> " + data.abertura + "</label><br><label id='label-volume'><b>Encerrado em:</b> " + data.encerramento + "</label></div>");
                        });

                        $(".volume-lista").mouseout(function() {
                            $(this).removeClass('volume-lista-hover');
                        });

                        $(".volume-lista").mouseover(function() {
                            $(this).addClass('volume-lista-hover');
                        });

                        $(".volume-lista").click(function() {
                            $(".volume-lista").removeClass('volume-lista-hover');
                            $(this).addClass('volume-lista-hover');
                        });

                    } else {
                        $(".listar-volume").html("<center>Não há volumes passíveis de comentário neste processo!</center>");
                    }
                }, "json");
            },
            close: function() {
                $('.listar-volume').html('');
            }
        });
    });

    /*Comentar o volume de processo*/
    function clickComentar(id, volume) {
        $('#ID_VOLUME_PROCESSO').val(id);
        $('#NUMERO_VOLUME_PROCESSO').val(volume);
        $(".comentar-volume").dialog("open");
    }

    /*Combo automatico de usuarios x unidade*/
    function comboUnidUser() {
        $("#usuario_comentar").empty();
        $.post('modelos/combos/mostrar_usuarios_unidades.php', {
            unidade: $("#diretoria_comentar").val(),
            request: 'getuser'
        }, function(response) {
            if (response.success == 'true') {
                $("#usuario_comentar").removeAttr('disabled');
                $.each(response.data, function(index, data) {
                    $("#usuario_comentar").append("<option value='" + data.ID + "'>" + data.NOME + "</option>");
                });
            } else {
                $("#usuario_comentar").attr('disabled', 'disabled');
                $("#usuario_comentar").empty();
            }
        }, "json");
    }

</script>

<!-- Comentarios de processos-->
<div class="comentar-volume div-form-dialog">
    <div class="row">
        <label class="label">*VOLUME:</label>
        <span class="conteudo">
            <input disabled type="hidden" id="ID_VOLUME_PROCESSO">
            <input disabled type="text" id="NUMERO_VOLUME_PROCESSO" class="FUNDOCAIXA1">
        </span>
    </div>

    <div class="row">
        <label class="label">*UNIDADE:</label>
        <span class="conteudo">
            <select id="diretoria_comentar" class="FUNDOCAIXA1"></select>
        </span>
    </div>

    <div class="row">
        <label class="label">*FILTRO:</label>
        <span class="conteudo">
            <input type="text" id="filtro-diretoria-comentar" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1">
        </span>
    </div>

    <div class="row">
        <label class="label">*USUÁRIO:</label>
        <span class="conteudo">
            <select disabled id="usuario_comentar" class="FUNDOCAIXA1"></select>
        </span>
    </div>

</div>

<div id="comentar-volume-dialog">
    <div class="listar-volume"></div>
</div>