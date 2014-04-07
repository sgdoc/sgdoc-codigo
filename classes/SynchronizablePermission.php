<?php

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
interface SynchronizablePermission {

    /**
     * @return array
     * @param string $identifier
     */
    public function findPermissionExternal($identifier);
}