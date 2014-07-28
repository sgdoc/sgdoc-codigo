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

include("bibliotecas/fpdf/fpdf.php");
include("bibliotecas/pdfbookmark/PDF_Bookmark.php");

$allow = AclFactory::checaPermissao(
                Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999));

header("Content-type:application/pdf");

header("Content-Disposition:attachment;filename=PDF-DOC-{$_REQUEST['digital']}.pdf");

$hashs = explode(',', $_REQUEST['hash']);

$md5 = array();

foreach ($hashs as $hash) {
    $hash = explode('|', $hash);
    $md5[] = $hash[0];
}

Imagens::factory()
        ->generatePDFForDigitalByMD5($_REQUEST['digital'], false, 'png', $md5, $allow)
;