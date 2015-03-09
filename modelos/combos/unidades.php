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

    

    if (isset($_POST['tipo'])) {
        switch ($_POST['tipo']) {
            case 'diretorias':
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID,SIGLA,NOME FROM TB_UNIDADES WHERE TIPO = '4' ORDER BY NOME");
                break;

            case 'id':
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID,SIGLA,NOME FROM TB_UNIDADES WHERE ID = ? ORDER BY NOME LIMIT 1");
                $stmt->bindParam(1, $_REQUEST['id'], PDO::PARAM_INT);
                break;

            case 'crs':
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID,SIGLA,NOME FROM TB_UNIDADES WHERE TIPO = '6' ORDER BY NOME");
                break;

            case 'uaafs':
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID,SIGLA,NOME FROM TB_UNIDADES WHERE TIPO = '7' ORDER BY NOME");
                break;

            case 'todas':
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID,SIGLA,NOME FROM TB_UNIDADES ORDER BY NOME");
                break;

            case 'caixas':
                $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID,SIGLA,NOME FROM TB_UNIDADES ORDER BY NOME");
                $stmt->execute();
                $out = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $out = array_change_key_case(($out), CASE_LOWER);
                $novo[] = array(null => '');
                foreach ($out as $key => $value) {
                    $novo[] = array(
                        $value['ID'] => Util::fixErrorString("{$value['NOME']} - {$value['SIGLA']}"));
                }
                print(json_encode($novo));
                return;
                break;
            default:
                break;
        }
    }


    $stmt->execute();

    $out = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out = array_change_key_case(($out), CASE_LOWER);

    $usuarioUnidade = Controlador::getInstance()->usuario->ID_UNIDADE;

    if ($_REQUEST['tipo'] != 'id') {
        $novo[] = array('' => '');
    }

    foreach ($out as $key => $value) {
        if (!$usuarioUnidade != $value['ID']) {
            $novo[] = array(
                $value['ID'] => $value['SIGLA'] . " - " . Util::fixErrorString($value['NOME']));
        }
    }
    print(json_encode($novo));
} catch (PDOException $e) {
    
}