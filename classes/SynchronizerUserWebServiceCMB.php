<?php

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
class SynchronizerUserWebServiceCMB implements SynchronizableUser {

    /**
     * @return User
     * @param string $identifier
     */
    public function loadUser($identifier) {

        include( 'nusoap/lib/nusoap.php');

        $client = new nusoap_client(Config::factory()->getParam('cmb.cpa.webservice.user'));
        $response = $client->call('loadUser', array($identifier));

        if ($client->getError()) {
            throw new Exception($client->getError());
        }

        $userExternal = json_decode($response);

        $userLocal = new stdClass();

        foreach ($userExternal as $attribute => $value) {
            $userLocal->{$attribute} = $value;
        }

        return $userLocal;
    }

}