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

$response = array();

$session = new Zend_Session_Namespace('notifications');

$now = (integer) substr(microtime(), 11, 10);

$hasIdentity = Zend_Auth::getInstance()->hasIdentity();
//Se não tem identidade, Timeout alcançado
$response['timeout'] = ( $hasIdentity )? 'N' : 'S';

if ($now <= $session->next) {
    $response['prazos']['notificado'] = 'N';
} else if( $hasIdentity ) {

    $auth = Zend_Auth::getInstance()->getStorage()->read();

    /* meus vencidos */
    $prazosVencidosUsuario = TPPrazo::factory()->retrivePrazosVencidosUsuarioByIdUnidade($auth->ID, $auth->ID_UNIDADE);

    /* vencidos setor */
    $prazosVencidosUnidade = TPPrazo::factory()->retrivePrazosVencidosUnidadeById($auth->ID_UNIDADE);

    /* meus pendentes */
    $prazosPendentesUsuario = TPPrazo::factory()->retrivePrazosPendentesByIdUsuario($auth->ID, $auth->ID_UNIDADE);

    /* pendentes setor */
    $prazosPendentesUnidade = TPPrazo::factory()->retrivePrazosPendentesByIdUnidade($auth->ID_UNIDADE);

    /* meus vencidos */
    if (is_array($prazosVencidosUsuario)) {
        foreach ($prazosVencidosUsuario as $key => $value) {
            $response['prazos']['vencidos']['usuario'][] = array(
                'id' => $value['ID'],
                'dias' => $value['DIAS_RESTANTES'],
                'prazo' => $value['DT_PRAZO']
            );
        }
    } else {
        $response['prazos']['vencidos']['usuario'] = null;
    }

    /* vencidos setor */
    if (is_array($prazosVencidosUnidade)) {
        foreach ($prazosVencidosUnidade as $key => $value) {
            $response['prazos']['vencidos']['setor'][] = array(
                'id' => $value['ID'],
                'dias' => $value['DIAS_RESTANTES'],
                'prazo' => $value['DT_PRAZO']
            );
        }
    } else {
        $response['prazos']['vencidos']['setor'] = null;
    }

    /* meus pendentes */
    if (is_array($prazosPendentesUsuario)) {
        foreach ($prazosPendentesUsuario as $key => $value) {
            $response['prazos']['pendentes']['usuario'][] = array(
                'id' => $value['ID'],
                'dias' => $value['DIAS_RESTANTES'],
                'prazo' => $value['DT_PRAZO']
            );
        }
    } else {
        $response['prazos']['pendentes']['usuario'] = null;
    }

    /* pendentes setor */
    if (is_array($prazosPendentesUnidade)) {
        foreach ($prazosPendentesUnidade as $key => $value) {
            $response['prazos']['pendentes']['setor'][] = array(
                'id' => $value['ID'],
                'dias' => $value['DIAS_RESTANTES'],
                'prazo' => $value['DT_PRAZO']
            );
        }
    } else {
        $response['prazos']['pendentes']['setor'] = null;
    }

    /* total */
    $total_vencidos_usuario = (is_array($prazosVencidosUsuario)) ? count($prazosVencidosUsuario) : 0;
    $total_pendentes_usuario = (is_array($prazosPendentesUsuario)) ? count($prazosPendentesUsuario) : 0;
    $total_vencidos_setor = (is_array($prazosVencidosUnidade)) ? count($prazosVencidosUnidade) : 0;
    $total_pendentes_setor = (is_array($prazosPendentesUnidade)) ? count($prazosPendentesUnidade) : 0;

    $response['prazos']['totais'][] = array(
        'meus_vencidos' => $total_vencidos_usuario,
        'meus_pendentes' => $total_pendentes_usuario,
        'setor_vencidos' => $total_vencidos_setor,
        'setor_pendentes' => $total_pendentes_setor
    );

    if (($total_vencidos_usuario + $total_pendentes_usuario + $total_vencidos_setor + $total_pendentes_setor) > 0) {
        $response['prazos']['notificado'] = 'S';
    } else {
        $response['prazos']['notificado'] = 'N';
    }
}

print(json_encode($response));