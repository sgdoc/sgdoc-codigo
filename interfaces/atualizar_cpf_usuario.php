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
    <head>
        <title></title>
        <style type="text/css" >
            body{
                font-size: 14px;
                font-family: "Trebuchet MS", "Helvetica", "Arial",  "Verdana", "sans-serif";
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
            }

        </style>
        <script type="text/javascript">
            $(document).ready(function(){
                var name = $( "#name" ),
                allFields = $( [] ).add( name ),
                tips = $( ".validateTips" );
                
                function updateTips( t ) {
                    tips.text( t ).addClass( "ui-state-highlight" );
                    setTimeout(function() {
                        tips.removeClass( "ui-state-highlight");
                        tips.html("");
                    }, 900 );
                }
                
                function checkCPF(field, msn) {
                    if(!field.validateCPF()) {
                        updateTips(msn);
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
                
                $( "#dialog-form" ).dialog({
                    closeOnEscape: false,
                    open: function(){
                        $(".ui-dialog-titlebar-close").hide();

                        $('.ui-dialog-buttonpane').find('button:contains("Atualizar")').button({
                            icons: {
                                primary: 'ui-icon-circle-check'
                            }
                        });
                    },
                    autoOpen: true,
                    resizable: false,
                    modal: false,
                    buttons: {
                        "Atualizar": function() {
                            var bValid = true;
                            allFields.removeClass( "ui-state-error" );

                            bValid = checkCPF(name, "CPF digitado e invalido!");

                            if (bValid) {
                                $.ajax({
                                    type: "POST",
                                    url: "modelos/usuarios/atualizar_cpf_usuario.php",
                                    data: "cpf_usuario=" + name.clearCPF(),
                                    success: function(data){
                                        $(".validateTips").fadeIn(500, function(){
                                            if(data.success == 'true') {
                                                $('#dialog-form').dialog('removebutton', 'Atualizar');
                                                $('#dialog-form').dialog('addbutton', 'OK', function(){
                                                    location.href = "logoff.php?session=destroy";
                                                });
                                                $("#name").hide();
                                                $("#label-cpf").html("");
                                            }
                                        }).text(data.msg);
                                    }
                                });
                            }
                        }
                    }
                });

                $("#name").keypress(function(){
                    v = $("#name").val();
                    var max = 13;
                    v = v.toString().substring (0,max);
                    v=v.toString().replace(/\D/g,"");
                    v=v.toString().replace(/(\d{3})(\d)/,"$1.$2");
                    v=v.toString().replace(/(\d{3})(\d)/,"$1.$2");
                    v=v.toString().replace(/(\d{3})(\d{1,2})$/,"$1-$2");
                    $("#name").val(v);
                    return true;
                });


            });

        </script>
    </head>
    <body>
        <div id="dialog-form" title="Atualize seu cadastro">
            <p class="validateTips">Informe seu CPF, para atualização na base de dados.</p>
            <label for="name" id="label-cpf">CPF</label>
            <input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" />
        </div>
    </body>
</html>
