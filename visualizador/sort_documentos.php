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
<html>
    <head>
        <link type="text/css" href="../css/jquery-sortable.css" rel="stylesheet" />
        <script type="text/javascript"  src="../interfaces/variaveis.php"></script>
        <script type="text/javascript" src="../javascripts/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="../javascripts/jquery-ui-1.8.7.custom.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                var digital = '<?php print($_GET['digital']); ?>';
                var token = '';
                var item;

                $('#carregando-cache-processos').show();

                $.getJSON("../modelos/documentos/carregar_imagens_sort_documentos.php", {
                    'digital': digital
                }, function(data) {
                    var pag;
                    if (data) {
                        if (data.imagem.quantidade == 0) {
                            $('#carregando-cache-processos').fadeOut('slow');
                            alert('Este documento nao possui imagens publicas para serem ordenadas!');
                        } else {
                            $.each(data.imagem.preview, function(i) {
                                pag = new Number(new Number(i) + 1);
                                $("#sortable").append("<li id='pagina_" + pag + "'></li>");
                                $("#pagina_" + pag).attr("digital", digital);
                                $("#pagina_" + pag).attr("md5", data.imagem.md5[i])
                                $("#pagina_" + pag).attr("class", 'paginas-sort');
                                $("<img/>").attr("src", data.imagem.preview[i])
                                        .attr("title", 'Pagina ' + pag + ' de ' + data.imagem.quantidade)
                                        .appendTo("#pagina_" + pag);
                            });
                            $('#carregando-cache-processos').fadeOut('slow');
                        }
                    } else {
                        alert('json nao retornou!');
                    }
                });

                $("#sortable").sortable();
                $("#sortable").disableSelection();

                $("#sortable").bind('sortstop', function() {
                    $("#saveButton").fadeIn();
                });

                $("#saveButton").click(function() {
                    saving(true);
                    token = '';
                    $("#sortable li").each(function(i) {
                        item = $("#sortable li:eq(" + i + ")");
                        token += i + '|' + item.attr('md5') + ';';
                    });
                    $.post("../modelos/documentos/salvar_ordem_imagens.php", {'token': token, 'digital': digital}, function(data) {
                        saving(false);
                        if (data.success == 'false') {
                            alert(data.error);
                        }
                    }, 'json');
                });


                function saving(slow) {
                    if (slow) {
                        $("#salvando").fadeIn('slow');
                        $("#saveButton").fadeOut('slow');
                    } else {
                        $("#salvando").fadeOut('slow');
                    }
                }

            });
        </script>
    <body>
    </head>
    <div id="container">
        <div id="saveButton" class="saveButton" title="Salvar"></div>
        <div id="carregando-cache-processos">
            <div><span>Aguarde Carregando...</span></div>
        </div>
        <div id="salvando">
            <div><span>Aguarde Salvando...</span></div>
        </div>
        <h2>Digital - <?php echo $_GET['digital']; ?></h2>
        <ul id="sortable"></ul>
    </div>
</body>
</html>