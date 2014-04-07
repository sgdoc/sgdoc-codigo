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

$numero_processo = $_GET['numero_processo'];
$controller = Controlador::getInstance();
$auth = $controller->usuario;

$objeto = DaoProcesso::getProcesso($numero_processo);
$controller->setContexto($objeto);
$controller->recurso->abas = null;
$controller->botoes = Util::getMenus($auth, $controller->recurso, $controller->acl);
?>

<html>
    <head>
        <title>Anexos/Apensos</title>
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
            #container-vinculacao .legenda-vinculacao-processos{
                margin-bottom: 2px;
            }
            #container-vinculacao .legenda-vinculacao-processos img{
                float: right;
                font-family: tahoma;
                font-size: 10px;
                height: 16px;
                width: 16px;
                padding-left: 1px;
                color: #000000;
                margin-bottom: 5px;
            }
            #container-vinculacao .legenda-vinculacao-processos span{
                color: #000000;
                font-family: tahoma;
                font-size: 10px;
                vertical-align: middle;
                float: right;
            }
            #link-anexos{
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
            #link-pecas{
                background-image: url("imagens/fam/page_white_text.png");
                background-repeat: no-repeat;
                padding-left: 20px;
                background-position: 2px 2px;
            }
            #link-adicionar-peca{
                background-image: url("imagens/fam/add.png");
                background-repeat: no-repeat;
                padding-left: 20px;
                background-position: 2px 2px;
            }
            #link-remover-peca{
                background-image: url("imagens/fam/delete.png");
                background-repeat: no-repeat;
                padding-left: 20px;
                background-position: 2px 2px;
            }
            #link-remover-anexo{
                background-image: url("imagens/fam/delete.png");
                background-repeat: no-repeat;
                padding-left: 20px;
                background-position: 2px 2px;
            }
            #IFRAME_ANEXOS_PROCESSO,#IFRAME_APENSOS_PROCESSO,#IFRAME_PECAS_PROCESSO{
                height: 80%;
                width: 100%;
            }

        </style>
        <script type="text/javascript">
            
            $(document).ready(function() {
                /*Abas*/
                $("#tabs").tabs();

                /*Carregar Iframe de anexos*/
                $('#IFRAME_ANEXOS_PROCESSO').attr('src',"arvore_anexos_processos.php?numero_processo=<?php echo $numero_processo; ?>")

                /*Carregar Iframe de anexos*/
                $('#link-anexos').click(function(){                   
                    $('#IFRAME_ANEXOS_PROCESSO').attr('src',"arvore_anexos_processos.php?numero_processo=<?php echo $numero_processo; ?>")
                });

                /*Carregar Iframe de apensos*/
                $('#link-apensos').click(function(){
                    $('#IFRAME_APENSOS_PROCESSO').attr('src',"arvore_apensos_processos.php?numero_processo=<?php echo $numero_processo; ?>")
                });

                /*Carregar Iframe de pecas*/
                $('#link-pecas').click(function(){
                    $('#IFRAME_PECAS_PROCESSO').attr('src',"arvore_pecas_processos.php?numero_processo=<?php echo $numero_processo; ?>")
                });

                /*Botoes*/
                $('button').button();

            });
        </script>
    </head>
    <body>
        <div id="tabs">
            <?php Util::mostraAbas($controller->recurso->abas); ?>
        </div>
    </body>
</html>
