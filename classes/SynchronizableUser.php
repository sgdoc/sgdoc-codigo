<?php

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
interface SynchronizableUser {

    /**
     * @return User
     * @param string $identifier
     */
    public function loadUser($identifier);
}