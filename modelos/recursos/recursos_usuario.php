<?php

/**
 * Campos utilizados declarados no inicio:
 * 
 * $_POST['acao']
 * $_POST['permissao']
 * $_POST['id_recurso']
 * $_POST['id_usuario']
 */
if(strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0) {
    try {
        $acao      = $_POST['acao'];
        $idRecurso = (integer) $_POST['id_recurso'];
        $idUsuario = (integer) $_POST['id_usuario'];
        $permissao = (integer) $_POST['permissao'];
        //
        $privilegio = array();
        if($idRecurso>0) {
            $privilegio['ID_RECURSO'] = $idRecurso;
        }
        if($idUsuario>0) {
            $privilegio['ID_USUARIO'] = $idUsuario;
        }
        $getPrivilegioUsuario = DaoPrivilegioUsuario::getPrivilegiosUsuario($privilegio);

        $out = array();
        switch ($acao) {
            case 'salvar-privilegio-usuario':
                try {
                    //
                    $manterPermissao = array();
                    $manterPermissao = $privilegio;
                    $manterPermissao['PERMISSAO'] = (($permissao == true) ? 1 : 0);
                    //
                    if ($getPrivilegioUsuario->result == true) {
                        $manterPermissao['ID'] = $getPrivilegioUsuario->result[0]['ID'];
                    }
                    $rs = DaoPrivilegioUsuario::salvar($manterPermissao);
                    if ($rs->success == false) {
                        throw new Exception($rs->error);
                    }
                    Controlador::getInstance()->cache->clean('matchingAnyTag', 
                            array('acl_usuario_'.$idUsuario));
                    $out['message'] = 'SGDOC - Permissão alterada com sucesso.';
                    $out['success'] = 'true';
                } catch (Exception $e) {
                    $out = array('success' => 'false', 'error' => $e->getMessage());
                }
                break;
            case 'excluir-privilegio-usuario':
                try {
                    //
                    $manterPermissao = array();
                    $manterPermissao = $privilegio;
                    //
                    if ($getPrivilegioUsuario->result == true) {
                        $manterPermissao['ID'] = $getPrivilegioUsuario->result[0]['ID'];
                    }
                    $rs = DaoPrivilegioUsuario::deletePrivilegioUsuario($manterPermissao);
                    if ($rs->success == false) {
                        throw new Exception($rs->error);
                    }
                    Controlador::getInstance()->cache->clean('matchingAnyTag', 
                            array('acl_usuario_'.$idUsuario));
                    $out['message'] = 'SGDOC - Permissão alterada com sucesso.';
                    $out['success'] = 'true';
                } catch (Exception $e) {
                    $out = array('success' => 'false', 'error' => $e->getMessage());
                }
                break;
        }
    } catch (Exception $e) {
        LogError::sendReport($e);
        $out = array('success' => 'false', 'error' => $e->getMessage());
    }
    echo json_encode($out);
}