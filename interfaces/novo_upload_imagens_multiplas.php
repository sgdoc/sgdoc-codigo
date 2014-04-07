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

set_time_limit(0);

ini_set('output_buffering', 0);
ini_set('implicit_flush', 0);

// tell the client the request has finished processing
//header('Location: index.php');  // redirect (optional)
header('Status: 200');          // status code
header('Connection: close');    // disconnect

//// clear ob stack
@ob_end_clean();

// continue processing once client disconnects
ignore_user_abort(false);

ob_start();
/* ------------------------------------------ */
/* this is where regular request code goes.. */

$response = null;
if (isset($_FILES) ) {//&& count($_FILES)
    try {
        $fileDigitais = array(
            'name'      => $_FILES['files']['name'][0],
            'type'      => $_FILES['files']['type'][0],
            'tmp_name'  => $_FILES['files']['tmp_name'][0],
            'error'     => $_FILES['files']['error'][0],
            'size'      => $_FILES['files']['size'][0]
        );

        /**
         * Utilizada criação de objeto concreto por ser permitido somente o
         * upload de arquivos PDF. Caso seja liberado para outros tipos, 
         * utilizar fábrica para criação de objetos.
         */
        $session = \Session::get('_upload');
        $digital = $session['digital'];
        
        //Crio documento Imagem Anterior para qualquer tipo de conversão de passivo necessária
        $documentoImagemAnterior = \Documento\Imagem\DocumentoImagemFactory::factory( $digital );
        $documentoImagemNovo = new \Documento\Imagem\DocumentoImagemPDF();
        $documentoImagemNovo->upload($fileDigitais);

        // Make Result
        $response = array(
            'name' => $_FILES['files']['name'][0],
            'size' => $_FILES['files']['size'][0],
            'type' => $_FILES['files']['type'][0],
            'status' => 'success'
        );
    } catch (Exception $e) {
        // Make Result
        $response = array(
            'name' => $_FILES['files']['name'][0],
            'size' => $_FILES['files']['size'][0],
            'type' => $_FILES['files']['type'][0],
            'error' => $e->getMessage()
        );
    }
    
}else{
    $response = array(
        'name' => 'Não existe',
        'size' => '0 KB',
        'type' => 'Não existe',
        'error' => "Não foi possível efetuar o Upload"
    );
}

// Print Result
print('[' . json_encode($response) . ']');

/* end where regular request code runs..     */
/* ------------------------------------------ */
//$iSize = ob_get_length();
header("Content-Length: $iSize");

// if the session needs to be closed, persist it
// before closing the connection to avoid race
// conditions in the case of a redirect above
session_write_close();

$conteudo = ob_get_contents();
@ob_end_clean();

print $conteudo;

// send the response payload to the client
//@ob_end_flush();
//flush();

/* ------------------------------------------ */
/* code here runs after the client diconnect */
/* Objetivo de formar o Cache de Thumbs de forma  */
//$documentoImagem2 = null;
//$documentoImagem2 = \Documento\Imagem\DocumentoImagemFactory::factory( $digital );

