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

    if (DaoDocumento::checaDocumentoExisteDigital((string) $_POST['digital'])) {

        if (Tramite::registrarHistoricoDeTramiteDocumentos(
                        $_POST['digital'], 'Código de Rastreamento', $origem = 'XXXXX', sprintf('<span>%s - </span><a href="%s%s"><blink><strong>%s</strong></blink></a>', $_POST['servico'], __RASTREAMENTO__, $_POST['codigo'], $_POST['codigo'])
        )) {
            $response = array('status' => 'success', 'message' => 'Rastreamento registrado com sucesso!');
        } else {
            $response = array('status' => 'error', 'message' => 'Não foi possivel efetuar o registro do rastreamento!');
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Este documento nao foi cadastrado ainda!');
    }
} catch (Exception $e) {
    $response = array('status' => 'error', 'message' => $e->getMessage());
}

print json_encode($response);