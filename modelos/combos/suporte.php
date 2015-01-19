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

    
    

    switch ($_POST['tipo']) {
        case 'atendentes':

            /**
             * Default
             */
            $default = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT U.ID, U.NOME 
                FROM TB_USUARIOS U
                        INNER JOIN TB_USUARIOS_UNIDADES UU ON UU.ID_USUARIO = U.ID
                        INNER JOIN TB_UNIDADES UN ON UN.ID = UU.ID_UNIDADE
                        INNER JOIN TB_PRIVILEGIOS P ON P.ID_UNIDADE = UN.ID
                        INNER JOIN TB_RECURSOS R ON R.ID = P.ID_RECURSO
                WHERE 
                        R.ID = 1140110 
                        AND P.PERMISSAO = 1 
                        AND U.STATUS = 1
                GROUP BY U.ID
                ORDER BY U.NOME ASC
            ");
            $default->execute();
            $default = $default->fetchAll(PDO::FETCH_KEY_PAIR);

            /**
             * Permissao Extra
             */
            $allowed = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT S.ID, S.NOME 
                FROM TB_PRIVILEGIOS_USUARIOS P
                    INNER JOIN TB_RECURSOS R ON R.ID = P.ID_RECURSO
                    INNER JOIN TB_USUARIOS S ON S.ID = P.ID_USUARIO
                WHERE R.ID = 1140110 AND P.PERMISSAO = 1 AND S.STATUS = 1
                ORDER BY S.NOME ASC
            ");
            $allowed->execute();
            $allowed = $allowed->fetchAll(PDO::FETCH_KEY_PAIR);

            /**
             * Revogar Permissao
             */
            $denied = Controlador::getInstance()->getConnection()->connection->prepare("
                SELECT S.ID, S.NOME FROM TB_PRIVILEGIOS_USUARIOS P
                    INNER JOIN TB_RECURSOS R ON R.ID = P.ID_RECURSO
                    INNER JOIN TB_USUARIOS S ON S.ID = P.ID_USUARIO
                WHERE R.ID = 1140110 AND P.PERMISSAO = 0 AND S.STATUS = 1
                ORDER BY S.NOME ASC
            ");
            $denied->execute();
            $denied = $denied->fetchAll(PDO::FETCH_KEY_PAIR);

            /**
             * Adicionar
             */
            foreach ($allowed as $key => $value) {
                $default[$key] = $value;
            }

            /**
             * Remover
             */
            foreach ($denied as $key => $value) {
                unset($default[$key]);
            }

            /**
             * Remaker
             */
            foreach ($default as $key => $value) {
                $new[] = array($key => $value);
            }

            break;

        default:
            break;
    }


    print(json_encode($new));
} catch (PDOException $e) {
    echo $e->getMessage();
}