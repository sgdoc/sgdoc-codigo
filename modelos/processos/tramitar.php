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

    switch ($_POST['tipo']) {
        case 'I':
            $tramite = new Tramite();
            $out = $tramite->tramitarProcesso($_POST['processos'], $_POST['unidade'], 'I')->toArray();
            break;

        case 'E':
            $tramite = new Tramite();
            $out = $tramite->tramitarProcesso($_POST['processos'], $_POST['destinatario'], 'E', $_POST['local'], $_POST['endereco'], $_POST['cep'], $_POST['prioridade'], $_POST['telefone'])->toArray();
            break;

        default:
            throw new Exception('O Tipo do tramite nao foi definido!');
            break;
    }

    print(json_encode($out));
} catch (Exception $e) {
    LogError::sendReport($e);
    $out = array('success' => 'false', 'error' => $e->getMessage());
    echo json_encode($out);
}