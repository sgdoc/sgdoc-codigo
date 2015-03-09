<?php

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
final class SynchronizerPermission {

    /**
     * @var SynchronizablePermission
     */
    private $_sync = NULL;

    /**
     * @return void
     * @param SynchronizablePermission
     */
    private function __construct(SynchronizablePermission $sync) {
        $this->_sync = $sync;
    }

    /**
     * @return SynchronizerPermission
     * @param SynchronizablePermission
     */
    public static function factory(SynchronizablePermission $sync) {
        return new self($sync);
    }

    /**
     * @return array
     * @param string $identifier
     */
    public function findPermissionLocal($identifier) {

        try {

            $stmt = Controlador::getInstance()->getConnection()->connection->prepare("SELECT PXU.ID_USUARIO,PXU.ID_RECURSO,PXU.PERMISSAO 
                                    FROM tb_privilegios_usuarios PXU
                                        INNER JOIN TB_USUARIOS U ON U.ID = PXU.ID_USUARIO
                                    WHERE U.USUARIO = ?");

            $stmt->bindParam(1, $identifier, PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_NUM);
        } catch (Exception $e) {
            throw new Exception('Ocorreu um erro ao tentar carregar as permissoes de acesso do usuario!');
        }
    }

    /**
     * @return boolean
     * @param string $identifier
     */
    public function clearDB($identifier) {

        try {

            $conn = Controlador::getInstance()->getConnection()->connection;

            $conn->beginTransaction();

            $stmt = $conn->prepare('DELETE FROM SGDOC.TB_PRIVILEGIOS_USUARIOS WHERE ID_USUARIO = ?');

            $stmt->bindParam(1, $identifier, PDO::PARAM_INT);

            $stmt->execute();

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }

    /**
     * @return boolean
     * @param string $identifier
     */
    public function clearCache($identifier) {

        Controlador::getInstance()->cache->remove("acl_{$identifier}");
        Controlador::getInstance()->cache->remove("acl_usuario_{$identifier}");

        Controlador::getInstance()->cache->clean('matchingAnyTag', array("acl_usuario_{$id}", "acl_{$id}"));
    }

    /**
     * @return boolean
     * @param array $permissions ARRAY(0=>ID_USUARIO 1=>ID_RECURSO 2=>IN_PERMISSAO)
     */
    public function updateDB($permissions) {

        $values = '';
        $query = 'INSERT INTO SGDOC.TB_PRIVILEGIOS_USUARIOS (ID_USUARIO,ID_RECURSO,PERMISSAO) VALUES %s';

        foreach ($permissions as $index => $permission) {
            $values .= "({$permission[0]},{$permission[1]},{$permission[2]}),";
        }

        try {

            Controlador::getInstance()->getConnection()->connection->prepare(sprintf($query, substr($values, 0, -1)))->execute();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return boolean
     * @param string $identifier
     */
    public function reload($identifier) {

        try {

            $idUsuario = current(CFModelUsuario::factory()->findByParam(array('USUARIO' => $identifier)))->ID;

            $external = $this->_sync->findPermissionExternal($identifier);
            $local = $this->findPermissionLocal($identifier);

            if ($external != $local) {
                $this->clearDB($idUsuario);
                $this->clearCache($idUsuario);
                $this->updateDB($external);
            }
        } catch (Exception $e) {
            throw new Exception('Ocorreu um erro ao tentar carregar as permissões de acesso do usuário!');
        }

        return true;
    }

}