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

    $tramite = new Tramite();

    $defaultList = array();
    $query = isset($_GET['query']) ? $_GET['query'] : '';
    $default = $tramite->getListTramites(null, $query);
    
    /**
     * Indexar
     */
    if (count($default) > 0) {
        foreach ($default as $value) {
            $defaultList[$value['ID']] = $value;
        }
    }
    
//    print_r($defaultList); die;
    
    /**
     * Printar
     */
    if ($_POST['tipo'] == 'json') {
        $novo[] = array('' => '');
        foreach ($defaultList as $key => $value) {
            $novo[] = array($value['ID'] => Util::fixErrorString($value['NOME']) . " - {$value['SIGLA']}");
        }
        print(json_encode($novo));
        exit;
    }
//    print_r($defaultList); die;

    foreach ($defaultList as $key => $value) {
        print("{$value['NOME']} - {$value['SIGLA']}|{$value['ID']}\n");
    }
} catch (PDOException $e) {
    print(array('success' => 'false', 'error' => $e->getMessage()));
}