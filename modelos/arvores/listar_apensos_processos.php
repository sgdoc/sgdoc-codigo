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

$arvore = new Arvore();

switch ($_GET['action']) {
    case "getElementList": {
            if (isset($_GET['ownerEl']) == true && $_GET['ownerEl'] != NULL) {
                $ownerEl = $arvore->checkVariable($_GET['ownerEl']);
            } else {
                $ownerEl = 0;
            }
            $out = $arvore->getVinculacaoProcesso($ownerEl,  'modelos/arvores/listar_apensos_processos.php', 2/* Apensos */);
        }
        break;
    default:
        $out = 'Ocorreu um ao tentar representar a arvore de apensos do documento!';
        break;
}

/* Estrutura HTML do Tree */
print $out;