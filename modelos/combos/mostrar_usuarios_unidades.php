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

/**
 * @todo Encapsular...
 */
//desenvolvimento
isset($_REQUEST['diretoria']) ? $DIRETORIA = $_REQUEST['diretoria'] : $DIRETORIA = NULL;
isset($_REQUEST['request']) ? $REQUEST = $_REQUEST['request'] : $REQUEST = NULL;

switch ($_REQUEST['request']) {

    case 'unidades':
        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT ID, NOME,SIGLA FROM TB_UNIDADES ORDER BY NOME");
        $stmt->execute();

        $resul = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($resul)) {
            $out = array('success' => 'false');
        } else {
            foreach ($resul as $value) {
                $uni = array();
                $uni['id'] = $value['ID'];
                $uni['sigla'] = ($value['SIGLA']);
                $uni['nome'] = ($value['NOME']);
                $array[] = $uni;
            };

            $out = array('success' => 'true', 'unidades' => $array);
        }
        break;


    case 'usuarios':
        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("
            SELECT US.ID, US.USUARIO AS USUARIO, US.NOME AS NOME 
            FROM TB_USUARIOS AS US
                INNER JOIN TB_USUARIOS_UNIDADES UU ON UU.ID_USUARIO = US.ID
                INNER JOIN TB_UNIDADES AS UN ON UN.ID = UU.ID_UNIDADE
            WHERE 
                UN.ID = ? 
                AND US.STATUS = 1
            ORDER BY US.NOME
        ");
        $stmt->bindParam(1, $DIRETORIA, PDO::PARAM_INT);
        $stmt->execute();

        $resul = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($resul)) {
            $out = array('success' => 'false');
        } else {
            foreach ($resul as $value) {
                $usu = array();
                $usu['id'] = $value['ID'];
                $usu['usuario'] = ($value['USUARIO']);
                $usu['nome'] = ($value['NOME']);
                $array[] = $usu;
            };

            $out = array('success' => 'true', 'usuarios' => $array);
        }
        break;

    case 'getuser':

        $stmt = Controlador::getInstance()->getConnection()->connection
                ->prepare("SELECT U.ID, U.nome FROM SGDOC.tb_usuarios_unidades UXU
                                INNER JOIN SGDOC.tb_usuarios U ON UXU.id_usuario = U.id
                            WHERE UXU.id_unidade = ? AND STATUS = 1 AND U.STATUS = 1 ORDER BY U.NOME;");
        $stmt->bindParam(1, $_REQUEST['unidade']);
        $stmt->execute();
        $resul = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($resul)) {
            $out = array('success' => 'false');
        } else {
            foreach ($resul as $value) {
                $value['NOME'] = ($value['NOME']);
                $array [] = $value;
            };

            $out = array('success' => 'true', 'data' => $array);
        }
        break;
    default:
        break;
}

print json_encode($out);