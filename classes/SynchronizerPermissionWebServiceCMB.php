<?php

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
class SynchronizerPermissionWebServiceCMB implements SynchronizablePermission {

    /**
     * @return array
     * @param string $identifier
     */
    public function findPermissionExternal($identifier) {

        include( 'nusoap/lib/nusoap.php');

        $client = new nusoap_client(Config::factory()->getParam('cmb.cpa.webservice.permission'));

        $response = $client->call('retornaPermissoesSGDOC', array($identifier));

        if ($client->getError()) {
            throw new Exception($client->getError());
        }

        return json_decode($response);
    }

}