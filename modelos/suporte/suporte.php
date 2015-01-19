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

    switch ($_REQUEST['acao']) {
        case 'abrir-demanda':
            $out = Suporte::abrirChamado($_REQUEST['assunto'], $_REQUEST['descricao']);
            break;

        case 'encaminhar-demanda':
            $out = Suporte::encaminharDemanda($_REQUEST['demanda'], $_REQUEST['comentario'], $_REQUEST['atendente']);
            break;

        case 'encaminhar-demandas':
            $demandas = explode('|', $_REQUEST['demandas']);
            foreach ($demandas as $key => $demanda) {
                if ($demanda) {
                    $out = Suporte::encaminharDemanda($demanda, $_REQUEST['comentario'], $_REQUEST['atendente']);
                }
            }
            break;

        case 'finalizar-demanda':
            $out = Suporte::finalizarDemanda($_REQUEST['demanda'], $_REQUEST['comentario']);
            break;

        case 'devolver-demanda':
            $out = Suporte::devolverDemanda($_REQUEST['demanda'], $_REQUEST['comentario']);
            break;

        case 'qualificar-demanda':
            $out = Suporte::qualificarDemanda($_REQUEST['demanda'], $_REQUEST['nota']);
            break;

        case 'detalhar-demanda':
            try {
                $out = array('success' => 'true', 'demanda' => DaoSuporte::getDemanda($_REQUEST['demanda']));
            } catch (Exception $e) {
                $out = array('success' => 'false', 'error' => $e->getMessage());
            }
            break;

        default:
            $out = array('success' => 'false', 'error' => 'Opção inválida!');
            break;
    }

    print(json_encode($out));
} catch (Exception $e) {
    
}