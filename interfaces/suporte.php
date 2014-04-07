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
        <script type="text/javascript">
            $(document).ready(function(){

                $('#div-form-contato').dialog({
                    title: 'Atendimento online',
                    autoOpen: true,
                    resizable: false,
                    modal: false,
                    width: 720,
                    autoHeight: true,
                    close: function() {location.href = 'sistemas.php'},
                    buttons: {
                        Enviar: function() {
                            if(($('#ASSUNTO').val() == "" || $('#DESCRICAO').val() == "" )){
                                alert('Preencha todos os campos!');
                            }else{
                                $('#progressbar').show();
                                $.post('modelos/suporte/suporte.php',{
                                    acao:  'abrir-demanda',
                                    assunto:  $('#ASSUNTO').val(),
                                    descricao:  $('#DESCRICAO').val()
                                },
                                function(data){
                                    try{
                                        if(data.success == 'true'){
                                            alert(data.message);
                                            $('#ASSUNTO').val('');
                                            $('#DESCRICAO').val('');
                                            $('#progressbar').hide();
                                        }else{
                                            alert('Ocorreu um erro ao tentar enviar a solicitacao ao suporte!\n['+data.error+']');
                                            $('#progressbar').hide();
                                        }
                                    }catch(e){
                                        alert('Ocorreu um erro ao tentar enviar a solicitacao ao suporte!\n['+e+']');
                                        $('#progressbar').hide();    
                                    }
                                },"json");
                            }
                        }
                    }
                });
            });

        </script>
    </head>
    <body>
        <div class="div-form-dialog">
            <div class="div-form-dialog" id="div-form-contato">

                <table border="0" align="center" cellpadding="0" cellspacing="0" >

                    <tr>
                        <td width="64" height="80" nowrap ></td>
                        <td height="80" colspan="3" ><div align="center"><span class="label-titulo-div">Atendimento Online</span></div></td>
                        <td width="60" height="80" nowrap ></td>
                    </tr>
                    <tr>
                        <td width="64" align="center" valign="middle" nowrap ></td>
                        <td height="15"  align="right" valign="middle"  class="style17"> Assunto: </td>
                        <td height="15"  align="left" valign="bottom" >
                            <input onkeyup="DigitaLetraSeguro(this);" class="FUNDOCAIXA1" type=text id="ASSUNTO" name="ASSUNTO" size=60></td>
                        <td height="15"  align="left" valign="bottom" ></td>
                        <td width="60" nowrap ></td>
                    </tr>

                    <tr align="left" valign="bottom">
                        <td width="64" align="center" valign="middle" nowrap ></td>
                        <td height="15" align="right" valign="top" class="style17">Descri&ccedil;&atilde;o:</td>
                        <td height="15"  align="left" valign="bottom" >
                            <textarea onkeyup="DigitaLetraSeguro(this);" name="DESCRICAO" cols="40" class="FUNDOCAIXA1" id="DESCRICAO"></textarea>
                        </td>
                    </tr>
                    <tr align="left" valign="bottom">
                        <td width="64" align="center" valign="middle" nowrap ></td>
                        <td height="41" colspan="3" align="center" valign="bottom"  class="style3">&nbsp;</td>
                        <td width="60" nowrap ></td>
                    </tr>

                </table>

            </div>
        </div>
    </body>
</html>
