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
include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.2/classes/VinculacaoDemanda.php');

try {

    $out = array();
    switch ($_REQUEST['acao']) {
        
        case 'carregar-passiveis':
            $digitais = VinculacaoDemanda::getDocumentosPassiveisVinculacao($_REQUEST['digital']);
            foreach ($digitais as $key => $value) {
                $out[] = array($value['DIGITAL'] => $value['DIGITAL']);
            }
            break;

        case 'carregar-vinculados':
            $digitais = VinculacaoDemanda::getDocumentosVinculados($_REQUEST['pai'], $_REQUEST['vinculacao']);
            foreach ($digitais as $key => $value) {
                $out[] = array($value['FILHO'] => $value['FILHO']);
            }
            break;

        case 'vincular':
            $vinculacao = new VinculacaoDemanda();
            $out = $vinculacao->vincularDocumento($_REQUEST['pai'], $_REQUEST['filho'], $_REQUEST['vinculacao'])->toArray();
            break;

        case 'desvincular':
            $vinculacao = new VinculacaoDemanda();
            $out = $vinculacao->desvincularDocumento($_REQUEST['pai'], $_REQUEST['filho'], $_REQUEST['vinculacao'])->toArray();
            break;

        default:
            break;
    }

    print(json_encode($out));
} catch (PDOException $e) {
    echo $e->getMessage();
}