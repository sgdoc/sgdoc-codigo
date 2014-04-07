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
if ($_POST) {
    $auth = Zend_Auth::getInstance()->getStorage()->read();
    $cpf = $_POST['cpf_usuario'];
    try {
        
        $stmt = Controlador::getInstance()->getConnection()->connection->prepare("UPDATE TB_USUARIOS SET CPF = ? WHERE ID = ?");
        $stmt->bindParam(1, $cpf);
        $stmt->bindParam(2, $auth->ID);
        $stmt->execute();
        $out = array('success' => 'true', msg => 'CPF atualizado com sucesso, clique no botao OK para voltar ao menu principal.');
    } catch (PDOException $e) {
        $out = array('success' => 'false', msg => $e->getMessage());
    }

    header('Content-type: application/json');
    print(json_encode($out));
}