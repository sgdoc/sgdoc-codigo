<?php

/**
 * Campos utilizados declarados no inicio:
 * 
 * $_POST['acao']
 * $_POST['permissao']
 * $_POST['id_recurso']
 * $_POST['id_unidade']
 */
if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0) {
    try {
        $acao = $_POST['acao'];
        $idRecurso = (integer) $_POST['id_recurso'];
        $idUnidade = (integer) $_POST['id_unidade'];
        $permissao = (isset($_POST['permissao']) ? $_POST['permissao'] : 'false');

        $privilegio = array();
        if ($idRecurso > 0) {
            $privilegio['ID_RECURSO'] = $idRecurso;
        }
        if ($idUnidade > 0) {
            $privilegio['ID_UNIDADE'] = $idUnidade;
        }
        $getPrivilegio = DaoPrivilegio::getPrivilegios($privilegio);

        $out = array();
        switch ($acao) {
            case 'salvar-privilegio':
                try {
                    //
                    $manterPrivilegio = array();
                    $manterPrivilegio = $privilegio;
                    $manterPrivilegio['PERMISSAO'] = (($permissao == 'true') ? 1 : 0);
                    //
                    if ($getPrivilegio->result == true) {
                        $manterPrivilegio['ID'] = $getPrivilegio->result[0]['ID'];
                    }
                    $rs = DaoPrivilegio::salvar($manterPrivilegio);
                    if ($rs->success == false) {
                        throw new Exception($rs->error);
                    }
                    Controlador::getInstance()->cache->clean('matchingAnyTag', array('acl_unidade_' . $idUnidade));
                    $out['message'] = 'SGDOC - Permissão alterada com sucesso.';
                    $out['success'] = 'true';
                } catch (Exception $e) {
                    $out = array('success' => 'false', 'error' => $e->getMessage());
                }
                break;
            case 'get-combo-grupos-privilegios':
                try {

                    $grupoPrivilegio = DaoGrupoPrivilegio::getGruposPrivilegios();
                    if ($grupoPrivilegio->result == true) {
                        $out = $grupoPrivilegio->result;
                    }
                } catch (Exception $e) {
                    $out = array('success' => 'false', 'error' => $e->getMessage());
                }
                break;
            case 'get-grupo-privilegio-recursos':
                try {
                    $idGrupoPrivilegio = $_POST['id_grupo_privilegio'];
                    $grupoPrivilegio = DaoGrupoPrivilegio::getGruposPrivilegiosPorRecurso(array('ID_GRUPO_PRIVILEGIO' => $idGrupoPrivilegio));
                    if ($grupoPrivilegio->result == true) {

                        $out = trataJson($grupoPrivilegio->result);

                        //$out = $grupoPrivilegio->result;
                    }
                } catch (Exception $e) {
                    $out = array('success' => 'false', 'error' => $e->getMessage());
                }
                break;
            case 'salvar-grupos-privilegios':
                try {
                    $manterGrupoPrivilegio = array();
                    $manterGrupoPrivilegio['ID_UNIDADE'] = $idUnidade;
                    $delete = DaoPrivilegio::deletePrivilegioPorUnidade($manterGrupoPrivilegio);
                    //
                    $manterGrupoPrivilegio['ID_GRUPO_PRIVILEGIO'] = $_POST['id_grupo_privilegio'];
                    $insert = DaoGrupoPrivilegio::inserirPermissoesTB_PRIVILEGIOS($manterGrupoPrivilegio);
                    if ($insert->success == false) {
                        throw new Exception($insert->error);
                    }
                    $out = array('success' => 'true', 'message' => $insert->message);
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

function trataJson($dados) {
    foreach ($dados as $key => $value) {
        $dados[$key] = $value;
    }
    return $dados;
}