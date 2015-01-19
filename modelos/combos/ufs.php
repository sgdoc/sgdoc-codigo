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

    new Base();

    
    

    $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT * FROM TB_UF ORDER BY NOME_UF");
    $stmt->execute();

    $out = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out = array_change_key_case(($out), CASE_LOWER);

    $novo[] = array('' => '');

    foreach ($out as $key => $value) {
        $novo[] = array($value['ID'] => Util::fixErrorString($value['NOME_UF']) . " - {$value['SIGLA_UF']}");
    }
    print(json_encode($novo));
} catch (PDOException $e) {
    echo $e->getMessage();
}