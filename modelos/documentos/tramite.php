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

    switch ($_POST['acao']) {
        case 'tramitar':

            switch ($_POST['tipo']) {
                case 'I':
                    $tramite = new Tramite();
                    $out = $tramite->tramitarDocumento($_POST['digitais'], $_POST['unidade'], 'I')->toArray();
                    break;

                case 'E':
                    $tramite = new Tramite();
                    $out = $tramite->tramitarDocumento($_POST['digitais'], $_POST['destinatario'], 'E', $_POST['local'], $_POST['endereco'], $_POST['cep'], $_POST['prioridade'], $_POST['telefone'])->toArray();
                    break;

                default:
                    throw new Exception('O Tipo do tramite nao foi definido!');
                    break;
            }

            break;

        case 'receber':
            $tramite = new Tramite();
            $out = $tramite->receberDocumento($_POST['digitais'])->toArray();
            break;

        case 'cancelar':
            $tramite = new Tramite();
            $out = $tramite->cancelarTramiteDocumento($_POST['digitais'])->toArray();
            break;

        case 'resgatar':
            $tramite = new Tramite();
            $out = $tramite->resgatarDocumento($_POST['digitais'])->toArray();
            break;
    }
    print(json_encode($out));
} catch (PDOException $e) {
    $out = array('success' => 'false', 'error' => $e->getMessage());
    print(json_encode($out));
}