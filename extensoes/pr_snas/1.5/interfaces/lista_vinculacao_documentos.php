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

$digital = $_GET['digital'];
$controller = Controlador::getInstance();
$auth = $controller->usuario;


$objeto = DaoDocumento::getDocumento($digital);
$controller->setContexto($objeto);
$controller->recurso->abas = null;
$controller->botoes = Util::getMenus($auth, $controller->recurso, $controller->acl);

$extensions = Config::factory()->getParam('extensions.active');
foreach($extensions as $extensao) {}
$interfaces = "/{$extensao}/interfaces/";

?>

<html>
    <head>
        <title>Vinculação</title>
        <style type="text/css">
            body {
                background-color: #101c01;
                background-image: url('imagens/<?php print(__BACKGROUND__); ?>');
                background-position: bottom right;
                background-repeat: no-repeat;
                margin: 10px;
            }

            #container-vinculacao{
                background-color: #ffffff;
                border-radius: 5px;
                padding: 5px;
            }
            #container-vinculacao .legenda-vinculacao-documentos{
                margin-bottom: 2px;
            }
            #container-vinculacao .legenda-vinculacao-documentos img{
                float: right;
                font-family: tahoma;
                font-size: 10px;
                height: 16px;
                width: 16px;
                padding-left: 1px;
                color: #000000;
                margin-bottom: 5px;
            }
            #container-vinculacao .legenda-vinculacao-documentos span{
                color: #000000;
                font-family: tahoma;
                font-size: 10px;
                vertical-align: middle;
                float: right;
            }
            #link-anexos,#link-associados{
                background-image: url("imagens/fam/page_white_copy.png");
                background-repeat: no-repeat;
                padding-left: 20px;
                background-position: 2px 2px;
            }
            #link-apensos{
                background-image: url("imagens/fam/page_copy.png");
                background-repeat: no-repeat;
                padding-left: 20px;
                background-position: 2px 2px;
            }
            #link-adicionar{
                background-image: url("imagens/fam/add.png");
                background-repeat: no-repeat;
                padding-left: 20px;
                background-position: 2px 2px;
            }
            #link-desapensar,#link-desanexar,#link-desassociar{
                background-image: url("imagens/fam/delete.png");
                background-repeat: no-repeat;
                padding-left: 20px;
                background-position: 2px 2px;
            }
            #IFRAME_ANEXOS_DOCUMENTO,#IFRAME_APENSOS_DOCUMENTO,#IFRAME_ASSOCIA_DOCUMENTO,#IFRAME_DOCUMENTOS_ASSOCIADOS{
                height: 80%;
                width: 100%;
            }
        </style>

        <script type="text/javascript">

            $(document).ready(function() {
                
                /*Abas*/
                $("#tabs").tabs();

                /*Carregar Iframe de documentos associados*/
                $('#IFRAME_DOCUMENTOS_ASSOCIADOS').attr('src', "arvore_documentos_associados.php?digital=<?php echo $digital; ?>")

                /*Carregar Iframe de anexos*/
                $('#link-associados').click(function() {
                    $('#IFRAME_DOCUMENTOS_ASSOCIADOS').attr('src', "arvore_documentos_associados.php?digital=<?php echo $digital; ?>")
                });

                /*Carregar Iframe de anexos*/
                $('#link-anexos').click(function() {
                    $('#IFRAME_ANEXOS_DOCUMENTO').attr('src', "arvore_anexos_documentos.php?digital=<?php echo $digital; ?>")
                });

                /*Carregar Iframe de apensos*/
                $('#link-apensos').click(function() {
                    $('#IFRAME_APENSOS_DOCUMENTO').attr('src', "arvore_apensos_documentos.php?digital=<?php echo $digital; ?>")
                });

                /*Botoes*/
                $('button').button();
            });

            /*Funcoes*/
            function jquery_desvincular_documento(pai, filho, vinculacao) {
                try {
                    $('#progressbar').show();

                    /*Definir o label da operacao*/
                    switch (vinculacao) {
                        case 1:
                            var operacao = 'desanexar';
                            break;
                        case 2:
                            var operacao = 'desapensar';
                            break;
                        case 3:
                            var operacao = 'desassociar';
                            break;
                        default:
                            alert('Tipo de vinculacao invalida!');
                            return false;
                            break;
                    }

                    if (filho && pai && vinculacao) {

                        if (confirm('Você tem certeza que deseja ' + operacao + ' este documento agora?')) {
                            $.post("modelos/documentos/vinculacao.php", {
                                acao: 'desvincular',
                                pai: pai,
                                filho: filho,
                                vinculacao: vinculacao
                            },
                            function(data) {
                                try {
                                    if (data.success == 'true') {
                                        $('#FILHO_' + operacao.toUpperCase() + '_DOCUMENTO').combobox('modelos/documentos/vinculacao.php', {
                                            acao: 'carregar-vinculados',
                                            pai: pai,
                                            vinculacao: vinculacao
                                        });
                                        $('#progressbar').hide();
                                        alert(data.message);
                                    } else {
                                        $('#progressbar').hide();
                                        alert(data.error);
                                    }
                                } catch (e) {
                                    $('#progressbar').hide();
                                    alert('Ocorreu um erro ao tentar ' + operacao + ' este documento!\n[' + e + ']');
                                }
                            }, "json");
                        } else {
                            $('#progressbar').hide();
                        }
                    } else {
                        $('#progressbar').hide();
                        alert('Ocorreu um erro ao tentar efetuar a operacao desejada.');
                    }
                } catch (e) {
                    alert('Ocorreu um erro ao tentar ' + operacao + ' este documento!\n[' + e + ']');
                    $('#progressbar').hide();
                }
            }
        </script>
    </head>
    <body>
        <div id="tabs">
            <?php Util::mostraAbas($controller->recurso->abas); ?>
        </div>
    </body>
</html>
