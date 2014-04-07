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

include_once(__BASE_PATH__ . '/extensoes/pr_snas/1.0/classes/AutoCompleteDemanda.php');

if ($_GET) {

    $array = array();

    switch ($_GET['action']) {

        case 'processos-assuntos':
            $array = AutoComplete::filterAssuntosProcessosFullText($_GET['query']);
            break;

        case 'documentos-assuntos':
            $list = AutoComplete::filterAssuntosDocumentosFullText($_GET['query']);

            if (is_null($list)) {
                $list = array();
            }

            foreach ($list as $key => $value) {
                $array[$key] = array('id' => $value['id'], 'value' => $value['value']);
            }

            break;

        case 'documentos-recebido-por':

            $auth = Zend_Auth::getInstance()->getStorage()->read();
            $list = AutoComplete::filterRecebidoPorFullText($_GET['query'], $auth->ID_UNIDADE);

            if (is_null($list)) {
                $list = array();
            }

            foreach ($list as $key => $value) {
                $array[$key] = array('id' => $value['id'], 'value' => $value['value']);
            }

            break;

        case 'caixas-digital':
            $list = AutoComplete::filterDocumentosDigital($_GET['query'], $_GET['caixa']);

            if (is_null($list)) {
                $list = array();
            }

            if (count($list) == 0) {
                $array[] = array('id' => '0', 'value' => ('Nenhum documento com o filtro utilizado'));
            } else {
                $array[] = array('id' => '0', 'value' => ('Selecione o documento'));
            }

            foreach ($list as $key => $value) {
                $array[] = array('id' => ($value['ID']), 'value' => ($value['DIGITAL']));
            }
            break;

        case 'documentos-origens':

            switch ($_GET['type']) {
                case 'IN':
                    $array = AutoComplete::filterUnidadesInternasFullText($_GET['query'], true);
                    break;

                case 'PR':
                    $array = AutoComplete::filterUnidadesGenericasFullText($_GET['query'], $_GET['type'], true);
                    break;

                case 'PF':
                    $array = AutoComplete::filterPessoaFisicaFullText($_GET['query'], true);
                    break;

                case 'PJ':
                    $array = AutoComplete::filterPessoaJuridicaFullText($_GET['query'], true);
                    break;

                case 'OF':
                    $array = AutoComplete::filterUnidadesGenericasFullText($_GET['query'], $_GET['type'], true);
                    break;
            }

            break;

        case 'processos-interessados':
            $array = AutoComplete::filterInteressadosProcessosFullText($_GET['query']);
            break;

        case 'processos-origens':

            switch ($_GET['type']) {
                case 'IN':
                    $array = AutoComplete::filterUnidadesInternasFullText($_GET['query']);
                    break;

                case 'EX':
                    $array = AutoComplete::filterProcessosOrigensExternasFullText($_GET['query']);
                    break;
            }
            break;

        case 'unidades-internas':

            switch ($_GET['type']) {
                case 'IN':
                    $array = AutoComplete::filterUnidadesInternasFullText($_GET['query']);
                    break;
                case 'DIR':
                    $array = AutoComplete::filterUnidadesByTipoFullText($_GET['query'], 4);
                    break;
                case 'CR':
                    $array = AutoComplete::filterUnidadesByTipoFullText($_GET['query'], 6);
                    break;
                case 'UAAF':
                    $array = AutoComplete::filterUnidadesByTipoFullText($_GET['query'], 7);
                    break;
            }
            break;

        case 'tramite-sic':

            $array = AutoComplete::filterUnidadesTramiteSIC($_GET['query']);
            break;
        case 'prioridades-demanda' :
            $array = AutoCompleteDemanda::filterPrioridadesDemandaFullText($_GET['query']);
            break;
        case 'participante' :
            $array = AutoCompleteDemanda::filterParticipanteFullText($_GET['query']);
            break;
        default:
            break;
    }

    print(Util::convertArrayToComboSplitByCylinder($array));
}