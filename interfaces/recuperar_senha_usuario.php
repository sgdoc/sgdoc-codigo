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

include("function/auto_load_statics.php");
?>
<html>
    <style type="text/css">
        body {
            background-color: #101c01;
            background-image: url('imagens/<?php print(__BACKGROUND__); ?>');
            background-position: bottom right;
            background-repeat: no-repeat;

        }
        label {
            font-size: 14px;
        }
        label, input {
            display:block;
        }

        input.text {
            margin-bottom:12px;
            width:95%;
            padding: .4em;
        }

        .validateTips {
            font-size: 12px;
            border: 1px solid transparent;
            padding: 0.3em;
            text-justify: auto;
        }
    </style>
    <body leftmargin="0" marginheight="0" marginwidth="0" scroll="no" topmargin="0">
        <div id="painel_trocar_senha"  title="Trocar senha de acesso">
            <p class="validateTips">
                Por favor, insira uma nova senha abaixo e confirme a nova senha 
                no campo correspondente para realizar a troca.
            </p>
            <label class="text-input">Nova senha</label>
            <input type="password" name="senhan" id="senhan" maxlength="16" class="text ui-widget-content ui-corner-all"/>
            <label class="text-input">Confirma nova senha</label>
            <input type="password" name="senhac" id="senhac" maxlength="16" class="text ui-widget-content ui-corner-all"/>
        </div>
    </body>
    <script language="javascript">
        $(document).ready(function() {

<?php
if (isset($_GET['key'])) {
    print("var hash='{$_GET['key']}';");
} else {
    print("var hash = '';");
}

if (isset($_GET['session'])) {
    print("var session = true;");
} else {
    print("var session = false;");
}

if (isset($_GET['acesso'])) {
    print("var acesso = true;");
} else {
    print("var acesso = false;");
}
?>

            var senhan = $("#senhan"),
                    senhac = $("#senhac"),
                    allFields = $([]).add(senhan).add(senhac),
                    tips = $(".validateTips");

            if (acesso === true) {
                tips.html('Atenção: Esse e seu primeiro acesso, você deve alterar sua senha de acesso.');
            }

            /*Funcoes*/
            function checkFields() {
                var bValid = true;
                allFields.removeClass("ui-state-error");

                bValid = bValid && eguals(senhan, senhac, 'Atencao! senhas digitadas não conferem, digite novamente.');

                bValid = bValid && checkLength(senhan, "nova senha", 8, 16);
                bValid = bValid && checkLength(senhac, "confirma senha", 8, 16);

                bValid = bValid && checkRegexp(senhan, /^([0-9a-zA-Z])+$/, "Campo <b>nova senha</b>, só permitem caracteres : a-z 0-9");
                bValid = bValid && checkRegexp(senhac, /^([0-9a-zA-Z])+$/, "Campo confirmar senha, só permitem caracteres : a-z 0-9");

                return bValid;
            }

            function updateTips(t) {
                tips.text(t).addClass("ui-state-highlight");
                tips.fadeOut(5000, function() {
                    tips.removeClass("ui-state-highlight");
                })
            }

            function eguals(o, n, t) {
                if (o.val() != n.val()) {
                    updateTips(t);
                    o.addClass("ui-state-error")
                    n.addClass("ui-state-error")
                    return false
                }
                return true;
            }

            function checkLength(o, n, min, max) {
                if (o.val().length > max || o.val().length < min) {
                    o.addClass("ui-state-error");
                    updateTips("O tamanho do campo " + n + " deve estar entre " +
                            min + " e " + max + " caracteres.");
                    return false;
                } else {
                    return true;
                }
            }
            function checkRegexp(o, regexp, n) {
                if (!(regexp.test(o.val()))) {
                    o.addClass("ui-state-error");
                    updateTips(n);
                    return false;
                } else {
                    return true;
                }
            }

            $.extend($.ui.dialog.prototype, {
                'addbutton': function(buttonName, func) {
                    var buttons = this.element.dialog('option', 'buttons');
                    buttons[buttonName] = func;
                    this.element.dialog('option', 'buttons', buttons);
                }
            });

            $.extend($.ui.dialog.prototype, {
                'removebutton': function(buttonName) {
                    var buttons = this.element.dialog('option', 'buttons');
                    delete buttons[buttonName];
                    this.element.dialog('option', 'buttons', buttons);
                }
            });

            $("#painel_trocar_senha").dialog({
                closeOnEscape: false,
                open: function() {
                    $(".ui-dialog-titlebar-close").hide();
                    if (acesso === true) {
                        $('#painel_trocar_senha').dialog('removebutton', 'Cancelar');
                    } else {
                        $('.ui-dialog-buttonpane').find('button:contains("Cancelar")').button({
                            icons: {
                                primary: 'ui-icon-circle-close'
                            }
                        });
                    }
                    $('.ui-dialog-buttonpane').find('button:contains("Confirmar")').button({
                        icons: {
                            primary: 'ui-icon-circle-check'
                        }
                    });


                },
                autoOpen: true,
                resizable: false,
                modal: false,
                buttons: {
                    Confirmar: function() {
                        if (checkFields()) {
                            $.ajax({
                                type: "POST",
                                url: "modelos/usuarios/recuperar_senha_usuario.php",
                                data: "senha=" + senhan.val() + "&action=2&hash=" + hash + "&acesso=" + acesso,
                                dataType: 'json',
                                success: function(data) {
                                    $(".validateTips").html(data.msg);
                                    $(".validateTips").fadeIn(500, function() {
                                        $('#painel_trocar_senha').dialog('removebutton', 'Confirmar');
                                        $('#painel_trocar_senha').dialog('removebutton', 'Cancelar');
                                        $('#painel_trocar_senha').dialog('addbutton', 'OK', function() {
                                            $("#painel_trocar_senha").dialog("close");
                                            location.href = "<?php print(__URLSERVERAPP__); ?>";
                                        });
                                        $("#senhan").hide();
                                        $("#senhac").hide();
                                        $(".text-input").html("");
                                    });
                                }
                            })
                        }
                    },
                    Cancelar: function() {
                        if (session === true) {
                            location.href = "sistemas.php";
                        } else {
                            location.href = "<?php print(__URLSERVERAPP__); ?>";
                        }
                    }
                }
            });

        });

    </script>
