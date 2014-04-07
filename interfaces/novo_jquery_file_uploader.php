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

$allowForever = AclFactory::checaPermissao(
                Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(301206));

$session = unserialize($_SESSION['sgdoc']['_upload']);

if (Documento::validarDocumentoPecaProcesso($session['digital']) && !$allowForever) {
    die('<br /><br /><br /><span class="red"><strong>ESTE DOCUMENTO NÃO PODE RECEBER NOVAS IMAGENS ENQUANTO FOR PEÇA DE UM PROCESSO!</strong></span>');
}

if (!Documento::validarDocumentoAreaDeTrabalho($session['digital']) && !$allowForever) {
    die('<br /><br /><br /><span class="red"><strong>ESTE DOCUMENTO NÃO ESTÁ NA SUA ÁREA DE TRABALHO!</strong></span>');
}

if (Documento::validarDocumentoVinculadoDocumentoPrincipal($session['digital']) && !$allowForever) {
    die('<br /><br /><br /><span class="red"><strong>ESTE DOCUMENTO NÃO PODE RECEBER NOVAS IMAGENS ENQUANTO ESTIVER VINCULADO À OUTRO DOCUMENTO!</strong></span>');
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Upload de Imagens</title>

        <?php
        $allowScriptsMinifierCss = array(
            "plugins/jquery-file-upload/css/bootstrap.min.css",
            "plugins/jquery-file-upload/css/style.css",
            "plugins/jquery-file-upload/css/jquery.fileupload-ui.css"
        );

        if (Util::renovateCacheContentStatics($allowScriptsMinifierCss, 'cache/novojqueryfileuploader.css')) {
            Util::generateCacheContentsCssStatics($allowScriptsMinifierCss, 'cache/novojqueryfileuploader.css');
        }

        print(Util::autoLoadCss(array('cache/novojqueryfileuploader.css')));
        ?>


        <style type="text/css">

            .row, form, .container{
                width: 700px;
            }
            .container{
                height: 380px;
            }

            .fileupload-buttonbar, .row{
                margin: 0px;
                padding: 0px;
                margin-left: -5px;
            }
            .row{
                margin: 0px;
            }

            html{
                width: 700px;
                margin: 0px;
            }
            body{
                margin: 0px;
                padding: 5px;
                width: 700px;
            }
            .div-scroller{
                width:700px;
                height:270px;
                overflow: auto;
                border: 1px dashed #ccc;
                padding-top: 0px;
                margin-bottom: 15px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- The file upload form used as target for the file upload widget -->
            <form id="fileupload" action="novo_upload_imagens_multiplas.php" method="POST" enctype="multipart/form-data">
                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                <div class="row fileupload-buttonbar">
                    <div class="">
                        <!-- The fileinput-button span is used to style the file input field as button -->
                        <span class="btn btn-success fileinput-button">
                            <span><i class="icon-plus icon-white"></i> Selecionar...</span>
                            <input type="file" name="files[]" multiple>
                        </span>
                        <button type="submit" class="btn btn-primary start">
                            <i class="icon-upload icon-white"></i> Enviar
                        </button>
                        <button type="reset" class="btn btn-warning cancel">
                            <i class="icon-ban-circle icon-white"></i> Cancelar
                        </button>

                    </div>
                </div>
                <!-- The loading indicator is shown during image processing -->
                <div class="fileupload-loading"></div>
                <br>
                <div class="div-scroller">
                    <!-- The table listing the files available for upload/download -->
                    <table class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
                </div>
                <div class="row fileupload-buttonbar">

                    <!-- The global progress bar -->
                    <div class="progress progress-success progress-striped active fade">
                        <div class="bar" style="width:0%;"></div>
                    </div>

                </div>
            </form>

        </div>
        <!-- modal-gallery is the modal dialog used for the image gallery -->
        <!-- The template to display files available for upload -->
        <script id="template-upload" type="text/x-tmpl">
            {% for (var i=0, file; file=o.files[i]; i++) { %}
            <tr class="template-upload fade">
            <td class="preview"><span class="fade"></span></td>
            <td class="name">{%=file.name%}</td>
            <td class="size">{%=o.formatFileSize(file.size)%}</td>
            {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
            {% } else if (o.files.valid && !i) { %}
            <td>
            <div class="progress progress-success progress-striped active"><div class="bar" style="width:0%;"></div></div>
            </td>
            <td class="start">{% if (!o.options.autoUpload) { %}
            <button class="btn btn-primary">
            <i class="icon-upload icon-white"></i> {%=locale.fileupload.start%}
            </button>
            {% } %}</td>
            {% } else { %}
            <td colspan="2"></td>
            {% } %}
            <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning">
            <i class="icon-ban-circle icon-white"></i> {%=locale.fileupload.cancel%}
            </button>
            {% } %}</td>
            </tr>
            {% } %}
        </script>
        <!-- The template to display files available for download -->
        <script id="template-download" type="text/x-tmpl">
            {% for (var i=0, file; file=o.files[i]; i++) { %}
            <tr class="template-download fade">
            {% if (file.error) { %}
            <td></td>
            <td class="name">{%=file.name%}</td>
            <td class="size">{%=o.formatFileSize(file.size)%}</td>
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
            {% } else { %}
            <td class="preview">{% if (file.thumbnail_url) { %}
            <a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
            {% } %}</td>
            <td class="name">
            <a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
            </td>
            <td class="size">{%=o.formatFileSize(file.size)%}</td>
            <td colspan="2"></td>
            {% } %}
            </tr>
            {% } %}
        </script>


        <?php
        $allowScriptsMinifierJs = array(
            "plugins/jquery-file-upload/js/jquery.min.js",
            "plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js",
            "plugins/jquery-file-upload/js/tmpl.min.js",
            "plugins/jquery-file-upload/js/load-image.min.js",
            "plugins/jquery-file-upload/js/canvas-to-blob.min.js",
            "plugins/jquery-file-upload/js/bootstrap.min.js",
            "plugins/jquery-file-upload/js/bootstrap-image-gallery.min.js",
            "plugins/jquery-file-upload/js/jquery.iframe-transport.js",
            "plugins/jquery-file-upload/js/jquery.fileupload.js",
            "plugins/jquery-file-upload/js/jquery.fileupload-ip.js",
            "plugins/jquery-file-upload/js/jquery.fileupload-ui.js",
            "plugins/jquery-file-upload/js/locale.js",
            "plugins/jquery-file-upload/js/main.js"
        );

        if (Util::renovateCacheContentStatics($allowScriptsMinifierJs, 'cache/novojqueryfileuploader.js')) {
            Util::generateCacheContentsJsStatics($allowScriptsMinifierJs, 'cache/novojqueryfileuploader.js');
        }

        print(Util::autoLoadJavascripts(array('cache/novojqueryfileuploader.js')));
        ?>

    </body> 
</html>