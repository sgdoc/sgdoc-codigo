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

try {

    new Base();

    /* Permissao de Administrador */
    $varJs1 = 'var GLOBAL_DIR_APP = ';
    $varPhp1 = "'" . __URLSERVERAPP__ . "/';";
    $vars1 = $varJs1 . $varPhp1;

    $varJs2 = 'var GLOBAL_DIR_DIG = ';
    $varPhp2 = "'" . __URLSERVERFILES__ . "/documento_virtual/';";
    $vars2 = $varJs2 . $varPhp2;

    $varJs4 = 'var INTERVAL_NTF_PRAZO = ';
    $varPhp4 = "'" . __INTERVAL_NTF_PRAZO__ . "';";
    $vars4 = $varJs4 . $varPhp4;

    header('Content-type: application/javascript');
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-cache");
    header("Pragma: no-cache");

    print($vars1);
    print($vars2);
    print($vars4);
} catch (Exception $e) {
    
}