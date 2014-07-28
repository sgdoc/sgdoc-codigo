<?php
/**
 * Copyright 2011 ICMBio
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

$controller = Controlador::getInstance();
$auth = $controller->usuario;
?>

<html>
    <head>
        <style type="text/css" >
            #central {
                min-width: 200px;
                max-width: 850px;
                height: 180px;
                margin: 200px auto auto;
                overflow: hidden;
            }
            .dock-item {
                cursor: pointer;
            }

        </style>
    </head>

    <body>
        <p class="style13" valign="top" align="left"><?php print "Olá, {$auth->NOME}."; ?></p>
        <table id="central" width="483" height="228" border="0" align="center" cellpadding="0" cellspacing="0" background="imagens/fundo_tabela_vermelha.png">
            <tr>
                <td width="93%" height="228" align="center" valign="middle" class="style2">
                    <p align="center" class="style3">
                        <B>ESTE USUÁRIO NÃO POSSUI UNIDADES VINCULADAS!</B>
                    </p>
                    <p align="center" class="style3">
                        <a title="Sair do sistema" href="logoff.php">
                            <img src="imagens/logoff.png" />
                        </a>
                    </p>
                </td>
            </tr>
        </table>
        <span class="style13 rodape"><?php print(__RODAPE__); ?></span>
    </body>
</html>