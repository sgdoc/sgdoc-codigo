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

// this script can run forever
set_time_limit(0);

// tell the client the request has finished processing
//header('Location: index.php');  // redirect (optional)
header('Status: 200');          // status code
header('Connection: close');    // disconnect
// clear ob stack
@ob_end_clean();

// continue processing once client disconnects
ignore_user_abort(false);

ob_start();
/* ------------------------------------------ */
/* this is where regular request code goes.. */

/* end where regular request code runs..     */
/* ------------------------------------------ */
$iSize = ob_get_length();
header("Content-Length: $iSize");

// if the session needs to be closed, persist it
// before closing the connection to avoid race
// conditions in the case of a redirect above
session_write_close();

// send the response payload to the client
@ob_end_flush();
flush();

/* ------------------------------------------ */
/* code here runs after the client diconnect */
Imagens::factory()
        ->isExistsDirCache()
        ->generateCacheForDigital($_REQUEST['digital'], false, $_REQUEST['between'])
;