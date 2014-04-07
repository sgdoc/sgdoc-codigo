<?php

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
class AuthenticationWebServiceCMB implements Authenticatable {

    /**
     * @return boolean
     * @param string $identifier
     */
    public function isUser($identifier) {

        include( 'nusoap/lib/nusoap.php');

        $client = new nusoap_client(Config::factory()->getParam('cmb.cpa.webservice.authentication'));

        $response = $client->call('isUser', array($identifier));

        if ($client->getError()) {
            throw new Exception($client->getError());
        }

        return json_decode($response)->exist;
    }

    /**
     * @return boolean
     * @param string $user
     * @param string $pass
     */
    public function validate($user, $pass) {

        include( 'nusoap/lib/nusoap.php');

        $client = new nusoap_client(Config::factory()->getParam('cmb.cpa.webservice.authentication'));

        $response = $client->call('validate', array($user, $pass));

        if ($client->getError()) {
            throw new Exception($client->getError());
        }

        return json_decode($response)->valid;
    }

}