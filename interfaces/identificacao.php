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
 *
 */

include("function/auto_load_statics.php");

/**
 * Instancia o Zend_Session para pegar a sessão do captcha no caso do usuário errar uma vez seu login.
 * O Autoload é responsável por
 * include 'Zend/Session/Namespace.php';
 */
$attempts = new Zend_Session_Namespace('attempts');
?>

<style type="text/css">
    body {
        background-color: #0E1800;
        background-image: url('imagens/<?php print(__BACKGROUND__); ?>');
        background-position: bottom right;
        background-repeat: no-repeat;
    }
    .login-logo{
        background-image: url('imagens/logomarca-120.png');
    }
    label {
        font-size: 14px;
    }
    label, input {
        display:block;
    }
    input .text {
        margin-bottom:12px;
        width:95%;
        padding: .4em;
    }
    .validateTips {
        font-size: 12px;
        border: 1px solid transparent;
        padding: 0.3em;
    }
    .tabela_login {
        width: 500px;
        height: 100px;
        top: 50%;
        left: 50%;
        margin-top: -200px;
        margin-left: -250px;
        position: absolute;
    }
    .botao33 {
        width: 40px;
        height: 40px;
    }
    .fieldError {
        border: 1px solid red;
    }
</style>
<p align='left' class="style13">Sistema Gerenciador de Documentos (v. <?php print(__APPVERSAO__); ?>)</p>
<div id="div-form-autenticacao">
    <?php if (__CONFIG_ICPBRASIL_CERTIFICADO_CAMINHO__ != ''): ?>
    <div class="certificado-info">
        Caso seu navegador apresente algum problema relativo à certificação digital: <a href="<?php echo __CONFIG_ICPBRASIL_CERTIFICADO_CAMINHO__ ?>" target="_blank">clique aqui.</a>
    </div>
    <?php endif; ?>
    <div class="login-logo"></div>
    <div class="login-campos">
        <form action="" method="post" id="IDENTIFICACAO">
            <label for="USUARIO">Usuário:</label>
            <input class="FUNDOCAIXA1" name="USUARIO" value="<?php echo $_POST['USUARIO']; ?>" autocomplete="off" type=text id="USUARIO" tabindex=1 maxlength=100>
            <label for="SENHA">Senha:</label>
            <input class="FUNDOCAIXA1" type="password" value="" name="SENHA" id="SENHA" autocomplete="off" maxlength=20 tabindex=2>
            <?php if (isset($attempts->attempts) && $attempts->attempts > 0): ?>
                <label for="CAPTCHA">Código:</label>
                <input class="FUNDOCAIXA1" type="text" name="CAPTCHA" id="CAPTCHA" tabindex="3" autocomplete="off" maxlength=6>
            <?php endif; ?>
        </form>
    </div>
    <?php if (isset($attempts->attempts) && $attempts->attempts > 0): ?>
        <div class="login-captcha">
            <img alt="Codigo" src="captcha.php?<?php echo microtime(); ?>" />
        </div>
    <?php endif; ?>
    <div class="login-botoes">
         
        <?php if (__ADAPTER_AUTENTICACAO__ == ''): ?>
            <a href="#" id="botaoSenha">
                <img alt="Esqueceu sua senha?" title="Esqueceu sua senha?" class="botao33" src="imagens/login.png" />
            </a>
        <?php endif; ?>
        <a href="#" id="botaoEntrar">
            <img alt="Entrar" title="Entrar" tabindex="4" class="botao48" src="imagens/logar.png" />
        </a>
    </div>

<!--    <span>
    </span>-->
</div>
<div id="dialog-form" title="Esqueceu sua senha?">
    <p class="validateTips">Um email será encaminhado com orientação para recuperaçãoo de sua senha, digite seu CPF: </p>
    <label for="cpf" id="label-cpf">CPF</label>
    <input type="text" name="cpf" id="cpf" class="FUNDOCAIXA1"/>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        identificacao.init();

        /*Tela de Login*/
        $('#div-form-autenticacao').dialog({
            title: 'Autenticação',
            autoOpen: true,
            resizable: false,
            modal: false,
            width: 335,
            height: 280
        });
        /*Remover o botao fechar*/
        $('.ui-dialog-titlebar-close').hide();
        $("#div-form-autenticacao").bind("dialogclose", function(event, ui) {
            $(this).dialog('open');
        });
        $('#USUARIO').focus();
        $('html').fadeIn(500);
    });
    $(function() {
        var cpf = $("#cpf");
        var allFields = $([]).add(cpf);
        $.extend($.ui.dialog.prototype, {
            'removebutton': function(buttonName) {
                var buttons = this.element.dialog('option', 'buttons');
                delete buttons[buttonName];
                this.element.dialog('option', 'buttons', buttons);
            }
        });
        $("#dialog-form").dialog({
            open: function() {
                $('.ui-dialog-buttonpane').find('button:contains("Enviar")').button({
                    icons: {
                        primary: 'ui-icon-circle-check'
                    }
                });
                $('.ui-dialog-buttonpane').find('button:contains("Cancelar")').button({
                    icons: {
                        primary: 'ui-icon-circle-close'
                    }
                });
            },
            autoOpen: false,
            height: 240,
            width: 280,
            resizable: false,
            modal: true,
            buttons: {
                "Enviar": function() {
                    var bValid = true;
                    allFields.removeClass("ui-state-error");
                    bValid = identificacao.checkCPF(cpf, "CPF digitado invalido!");
                    if (bValid) {
                        $.post("/modelos/usuarios/recuperar_senha_usuario.php",
                                {cpf_usuario: cpf.clearCPF(), action: 1},
                        function(data) {
                            $(".validateTips").fadeIn(500, function() {
                                if (data.success == 'true') {
                                    $('#dialog-form').dialog('removebutton', 'Enviar');
                                    $('#dialog-form').dialog('removebutton', 'Cancelar');

                                    $('#dialog-form').dialog('addbutton', 'OK', function() {
                                        $("#dialog-form").dialog("close");
                                    });
                                    $("#cpf").hide();
                                    $("#label-cpf").html("");
                                }
                            }).text(data.msg);
                        }, "json");
                    }
                },
                Cancelar: function() {
                    $(this).dialog("close");
                }
            },
            close: function() {
                allFields.val("").removeClass("ui-state-error");
            }
        });
        $("#cpf").keypress(function() {
            var v = $("#cpf").val();
            var max = 13;
            v = v.toString().substring(0, max);
            v = v.toString().replace(/\D/g, "");
            v = v.toString().replace(/(\d{3})(\d)/, "$1.$2");
            v = v.toString().replace(/(\d{3})(\d)/, "$1.$2");
            v = v.toString().replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            $("#cpf").val(v);
            return true;
        });
        $("#botaoSenha").click(function(e) {
            e.preventDefault();
            $("#dialog-form").dialog("open");
        });
    });

    var identificacao = {
        init: function()
        {
            this.login();
        },
        limpaConsoleMozilla: function()
        {
            if ($.browser.mozilla == true) {
                if (console && console.log) {
                    //                    console.clear();
                    //                    console.log("SGDOC");
                }
            }
        },
        updateTips: function(t)
        {
            var tips = $(".validateTips");
            tips.text(t).addClass("ui-state-highlight");
            setTimeout(function() {
                tips.removeClass("ui-state-highlight");
                tips.html("");
            }, 900);
        },
        checkCPF: function(field, msn)
        {
            if (!field.validateCPF()) {
                this.updateTips(msn);
                return false;
            } else {
                return true;
            }
        },
        login: function()
        {
            $('input').bind('keypress', function(e) {
                var code = (e.keyCode ? e.keyCode : e.which);
                if (code == 13) {
                    $('#botaoEntrar').click();
                }
            });
            $('#USUARIO, #SENHA, #CAPTCHA').bind('click change blur', function() {
                $(this).removeClass('fieldError');
            });
            var self = this;
            $('#botaoEntrar').click(function(e) {
                e.preventDefault();
                if ($('#USUARIO').val() == '' || $('#SENHA').val() == '' || $('#CAPTCHA').val() == '') {
                    $('#USUARIO, #SENHA, #CAPTCHA').addClass('fieldError');
                } else {
                    self.validaLogin();
                }
            });
        },
        validaLogin: function()
        {
            $("#progressbar").show();
            $.ajax({
                url: 'modelos/login/login.php',
                data: $('#IDENTIFICACAO').serialize(),
                type: 'POST',
                dataType: 'json',
                context: this,
                success: function(data) {
                    if (data.error == undefined) {
                        $('#USUARIO, #SENHA, #CAPTCHA').removeClass('fieldError');
                        document.location.href = data.url;
                    } else {
                        $("#progressbar").hide();
                        var divError = document.createElement('div');
                        var textError = document.createTextNode(data.error);
                        divError.setAttribute('id', 'errorLogin');
                        divError.appendChild(textError);
                        $(divError).dialog({
                            height: 140,
                            modal: true,
                            buttons: {
                                Ok: function() {
                                    $(this).dialog("close");
                                    $('#IDENTIFICACAO').submit();
                                }
                            }
                        });
                    }
                }
            });
        }
    }
</script>