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
<script type="text/javascript">

    $(document).ready(function() {

        /*Listeners*/
        $('#DIGITAL_REGISTRAR_TRAMITE_RAPIDO').keyup(function() {
            if ($(this).val().length == 7) {
                if (!jquery_registrar_tramite_rapido_validar_digital($(this).val())) {
                    alert('Digital inválido!');
                }
            }
        });

        $('#div-form-registrar-tramite-rapido').dialog({
            title: 'Registrar Trâmite Rápido',
            autoOpen: false,
            resizable: false,
            modal: true,
            width: 650,
            height: 160,
            buttons: {
                Salvar: function() {
                    $("#progressbar").show();
                    if ($('#DIGITAL_REGISTRAR_TRAMITE_RAPIDO').val().length == 7 && $('#OPERACAO_REGISTRAR_TRAMITE_RAPIDO').val() != 0) {
                        if (jquery_registrar_tramite_rapido_validar_digital($('#DIGITAL_REGISTRAR_TRAMITE_RAPIDO').val())) {
                            jquery_registrar_tramite_rapido();
                        } else {
                            alert('Digital inválida!');
                        }
                    } else {
                        alert('Campo(s) obrigatório(s) em branco ou preenchidos de forma inválida!');
                    }
                    $("#progressbar").hide();
                },
                Cancelar: function() {
                    $('#DIGITAL_REGISTRAR_TRAMITE_RAPIDO').val('');
                    $('#OPERACAO_REGISTRAR_TRAMITE_RAPIDO').val('0');
                    $(this).dialog('close');
                }
            }
        });

        /*Abrir form novo documento*/
        $('#botao-registrar-tramite-rapido').click(function() {
            $('#div-form-registrar-tramite-rapido').dialog('open');
        });

    });

    /*Validar Campos Cadastro Documento*/
    function jquery_registrar_tramite_rapido() {
        $.ajaxSetup({async: false});

        $.post("modelos/tramite/tramite_rapido.php", {
            digital: $('#DIGITAL_REGISTRAR_TRAMITE_RAPIDO').val(),
            operacao: $('#OPERACAO_REGISTRAR_TRAMITE_RAPIDO').val()
        },
        function(data) {
            if (data.status == 'success') {
                $('#DIGITAL_REGISTRAR_TRAMITE_RAPIDO').val('');
                $('#OPERACAO_REGISTRAR_TRAMITE_RAPIDO').val('0');
            }
            alert(data.message);
        }, "json");

    }

    /*Validar digital*/
    function jquery_registrar_tramite_rapido_validar_digital(digital) {

        var r = $.ajax({
            type: 'POST',
            url: 'modelos/tramite/validar_digital.php',
            data: 'digital=' + digital,
            async: false,
            success: function() {
            },
            failure: function() {
            }
        }).responseText;

        r = eval('(' + r + ')');

        return r.response;

    }

</script>

<!--Formulario-->
<div class="div-form-dialog" id="div-form-registrar-tramite-rapido">

    <div class="row">
        <label class="label">*DIGITAL:</label>
        <span class="conteudo">
            <input type="text" id="DIGITAL_REGISTRAR_TRAMITE_RAPIDO" maxlength="7" onKeyPress="DigitaNumero(this);">
        </span>
    </div>

    <div class="row">
        <label class="label">*OPERACAO:</label>
        <span class="conteudo">
            <select class='FUNDOCAIXA1' id='OPERACAO_REGISTRAR_TRAMITE_RAPIDO'>
                <option value="0">----- selecione uma op&ccedil;&atilde;o -----</option>
                <option value="1">Encaminhar ---&gt; Digitaliza&ccedil;&atilde;o</option>
                <option value="2">Encaminhar ---&gt; Triagem</option>
                <option value="3">Encaminhar ---&gt; Cadastro</option>
                <option value="4">Encaminhar ---&gt; Distribui&ccedil;&atilde;o</option>
                <option value="5">Encaminhar ---&gt; Expedi&ccedil;&atilde;o </option>
                <option value="6">Encaminhar ---&gt; Arquivo-Central</option>
                <option value="7">Encaminhar ---&gt; Biblioteca</option>
                <option value="8">Encaminhar ---&gt; SGI (Setor de Gerencia da Informacao)</option>
            </select>
        </span>
    </div>

</div>