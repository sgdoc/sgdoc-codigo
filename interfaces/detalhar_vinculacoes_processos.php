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
        <style type="text/css">
            #lista-modelos-termos-temp{
                display: none;
            }
            body {
                background-color: #101c01;
                background-image: url('imagens/<?php print(__BACKGROUND__); ?>');
                background-position: bottom right;
                background-repeat: no-repeat;
                margin: 10px;
            }
            h3 {
                margin: 2px 0 10px 0;
            }
            .error-input-value {
                border: 1px solid #FF0000 !important;
            }
        </style>
        <script type="text/javascript">
            var numero_processo = '<?php echo $numero_processo; ?>';

            $(document).ready(function() {
                /*Abas*/
                $("#tabs").tabs();

            });

        </script>
    </head>
    <body>
        <div id="tabs">
            <?php Util::mostraAbas($controller->recurso->abas); ?>
        </div>

        <div id="lista-modelos-termos-temp">
            <div id="div-modelo-termo-aux">
                <div id="box-filtro-processo-desentrenhar" >
                    <p><strong>Diretoria:</strong><input type="text" id="filtro-diretoria-desetrenhar" onKeyUp="DigitaLetraSeguro(this)" class="FUNDOCAIXA1" /></p>
                </div>
                <div id="box-operacao-sucesso">
                    <center><strong>Operaçao realizada com sucesso, clique no botão imprimir para impressão do termo.</strong></center>
                </div>
                <div id="ajuda">Use "<b>-</b>" para representar um intervalo entre pecas, Ex: 8-17. Use "<b>,</b>" para representar folhas unicas, Ex: 8,10,11.<br>Ex: 8-17,21.</div>
            </div>
        </div>

    </body>
</html>