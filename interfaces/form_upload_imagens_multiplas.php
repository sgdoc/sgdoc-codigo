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

<html>
    <head>
        <style type="text/css">
            #FG_PUBLICO_UPLOAD_IMAGEM_DOCUMENTO{
                position: absolute;
                height: 32px;
                width: 200px;
                top: 20px;
                right: 280px;
                font-family: tahoma;
                font-size: 18px;
                color: #008000;
            }
            #FG_OPERACAO_IMAGEM_DOCUMENTO{
                position: absolute;
                height: 32px;
                width: 200px;
                top: 20px;
                right: 75px;
                font-family: tahoma;
                font-size: 18px;
                color: #008000;
            }

            #btnCancelUpload{
                position: absolute;
                top: 5px;
                left: 75px;
                height: 32px;
                width: 32px;
                background-image: url('imagens/cancelar_upload.png');
                float: right;
            }

            #btnStartUpload{
                position: absolute;
                top: 5px;
                left: 45px;
                height: 32px;
                width: 32px;
                background-image: url('imagens/iniciar_upload.png');
                float: right;
            }

            .display-upload-documentos{
                overflow-y: scroll;
                overflow-x: none;
                height: 400px;
                width: 755px;
                padding: 5px;
                background-color: #ffffff;
                border-radius: 5px;
                margin: 10px;
                margin-top: 5px;
                margin-left: 0px;
            }

            #div-form-upload-multiplo-documento{
                display: none;
            }
        </style>
        <script type="text/javascript">

            $(document).ready(function(){

                $('#div-form-upload-multiplo-documento').dialog({
                    title: 'Enviar imagens',
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    width: 800,
                    autoHeight:true,
                    beforeClose: function(){
                        $('.display-upload-documentos').attr('src', '');
                    }
                });

                /*Listeners*/
                /*Botao upload multiplo*/
                $('#botao-upload-imagens-multiplas-documentos').click(function(){                    
                    if (! jquery_setar_file_session() ){
                        return false;
                    }

                    $('.display-upload-documentos').attr('src', 'novo_jquery_file_uploader.php');
                
                    /*Abrir caixa de dialogo para upload de documentos*/
                    $('#div-form-upload-multiplo-documento').dialog('open');
                });

                /*Re-Setar informacoes do post quando ouver mudanca da flag publico*/
                $('#FG_PUBLICO_UPLOAD_IMAGEM_DOCUMENTO').change(function(){
                    jquery_setar_file_session();
                });
                /*Re-Setar informacoes do post quando ouver mudanca da flag operacao*/
                $('#FG_OPERACAO_IMAGEM_DOCUMENTO').change(function(){
                    jquery_setar_file_session();
                });

            });

            /*Setas as variaveis necessarias para identificar o upload*/
            function jquery_setar_file_session(){
                var nu_digital = $('#DIGITAL_DETALHAR_DOCUMENTO').val();
                var fg_publico = $('#FG_PUBLICO_UPLOAD_IMAGEM_DOCUMENTO').val();
                var fg_operacao = $('#FG_OPERACAO_IMAGEM_DOCUMENTO').val();
                
                if(!nu_digital || !fg_publico || !fg_operacao){
                    alert('Ocorreu um erro ao tentar preparar o módulo de upload de imagens.\nFeche o sistema e tente novamente!');
                    return false;
                }
            
                var id_session = '<?php echo session_id(); ?>';
                var r = $.ajax({
                    type: 'POST',
                    url: 'modelos/documentos/imagens.php',
                    data:   'acao=upload'
                            +'&nu_digital='+nu_digital
                            +'&fg_publico='+fg_publico
                            +'&fg_operacao='+fg_operacao
                            +'&PHPSESSID='+id_session,
                    async: false
                }).responseText;

                r = eval('('+r+')');

                return r.success;
            }
        </script>
    </head>

    <body>
        
<?php
$manager = AclFactory::checaPermissao(
                Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(998));

$allow = AclFactory::checaPermissao(
                Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999));

?>
        
        <div id="div-form-upload-multiplo-documento">

            <select id="FG_PUBLICO_UPLOAD_IMAGEM_DOCUMENTO" class="FUNDOCAIXA1">
                <option selected value="1">IMAGEM PUBLICA</option>
                <option value="0">IMAGEM RESERVADA</option>
            </select>

<?php
    if($manager || $allow):
?>
            <select id="FG_OPERACAO_IMAGEM_DOCUMENTO" class="FUNDOCAIXA1">
                <option selected value="add">Unir à anterior</option>
                <option value="replace">Substituir anterior</option>
            </select>
<?php
    else:
?>
            <input type="hidden" id="FG_OPERACAO_IMAGEM_DOCUMENTO" value="add" />
<?php
    endif;
?>        

            <iframe class="display-upload-documentos" src="novo_jquery_file_uploader.php"></iframe>
        </div>
        
    </body>
</html>