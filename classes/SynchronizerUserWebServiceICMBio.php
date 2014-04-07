<?php

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
class SynchronizerUserWebServiceICMBio implements SynchronizableUser {

    /**
     * @return User
     * @param string $identifier
     */
    public function loadUser($identifier) {
        return current(CFModelUsuario::factory()->findByParam(array('USUARIO' => $identifier)));
    }

}