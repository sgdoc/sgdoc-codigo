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

$operacoes = array(
    '1' => array('Encaminhado, Aguardando a Digitalização', 'Cadastro', 'Digitalização'),
    '2' => array('Encaminhado, Aguardando a Triagem e Tramitação', 'Digitalização', 'Triagem e Trâmites'),
    '3' => array('Encaminhado para o setor de Cadastro', 'XXXXX', 'XXXXX'),
    '4' => array('Encaminhado para o setor de Distribuição', 'XXXXX', 'XXXXX'),
    '5' => array('Encaminhado para o setor de Expedição', 'XXXXX', 'XXXXX'),
    '6' => array('Encaminhado para o Arquivo-Central do ICMBio', 'XXXXX', 'XXXXX'),
    '7' => array('Encaminhado para a Biblioteca do ICMBio', 'XXXXX', 'XXXXX'),
    '8' => array('Encaminhado para o Setor de Gerencia da Informacao do ICMBio', 'XXXXX', 'XXXXX')
);


try {
    Tramite::registrarHistoricoDeTramiteDocumentos($_POST['digital'], $operacoes[$_POST['operacao']][0], $operacoes[$_POST['operacao']][1], $operacoes[$_POST['operacao']][2]);

    $response = array('status' => 'success', 'message' => 'Tramite registrado com sucesso!');
} catch (Exception $e) {
    $response = array('status' => 'error', 'message' => 'Ocorreu um erro ao tentar registrar o tramite deste documento!');
}

print json_encode($response);