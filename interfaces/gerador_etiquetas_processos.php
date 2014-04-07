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

$class_dir = 'bibliotecas/barcode';

$_GET['code'] = "code128";
$_GET['t'] = "31";
$_GET['r'] = 0;
$_GET['rot'] = "0";
$_GET['text'] = $_GET['numero_processo'];
$_GET['ext'] = $_GET['ext'];
$_GET['f1'] = "Arial.ttf";
$_GET['f2'] = "11";
$_GET['o'] = "1";
$_GET['dpi'] = "300";
$_GET['a1'] = "";
$_GET['a2'] = "";

if (isset($_GET['code'], $_GET['t'], $_GET['r'], $_GET['rot'], $_GET['text'], $_GET['f1'], $_GET['f2'], $_GET['o'], $_GET['dpi'], $_GET['a1'], $_GET['a2'])) {

    include $class_dir . '/BCGColor.php';
    include $class_dir . '/BCGBarcode.php';
    include $class_dir . '/BCGDrawing.php';
    include $class_dir . '/BCGFont.php';
    if (include($class_dir . '/BCG' . $_GET['code'] . '.barcode.php')) {
        if ($_GET['f1'] !== '0' && $_GET['f1'] !== '-1' && intval($_GET['f2']) >= 1) {
            $font = new BCGFont($class_dir . '/font/' . $_GET['f1'], intval($_GET['f2']));
        } else {
            $font = 0;
        }
        $color_black = new BCGColor(0, 0, 0);
        $color_white = new BCGColor(255, 255, 255);
        $codebar = 'BCG' . $_GET['code'];
        $code_generated = new $codebar();
        if (isset($_GET['a1']) && intval($_GET['a1']) === 1) {
            $code_generated->setChecksum(true);
        }
        if (isset($_GET['a2']) && !empty($_GET['a2'])) {
            $code_generated->setStart($_GET['a2']);
        }
        if (isset($_GET['a3']) && !empty($_GET['a3'])) {
            $code_generated->setLabel($_GET['a3']);
        }
        $code_generated->setThickness($_GET['t']);
        $code_generated->setScale($_GET['r']);
        $code_generated->setBackgroundColor($color_white);
        $code_generated->setForegroundColor($color_black);
        $code_generated->setFont($font);
        $code_generated->parse($_GET['text']);
        $drawing = new BCGDrawing('', $color_white);
        $drawing->setBarcode($code_generated);
        $drawing->setRotationAngle($_GET['rot']);
        $drawing->setDPI($_GET['dpi'] == 'null' ? null : (int) $_GET['dpi']);
        $drawing->draw();
        if (intval($_GET['o']) === 1) {
            header('Content-Type: image/png');
        } elseif (intval($_GET['o']) === 2) {
            header('Content-Type: image/jpeg');
        } elseif (intval($_GET['o']) === 3) {
            header('Content-Type: image/gif');
        }

        $drawing->finish(intval($_GET['o']));
    } else {
        header('Content-Type: image/png');
        readfile('error.png');
    }
} else {
    header('Content-Type: image/png');
    readfile('error.png');
}