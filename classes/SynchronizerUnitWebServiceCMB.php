<?php

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
class SynchronizerUnitWebServiceCMB implements SynchronizableUnit {

    /**
     * @return array
     */
    public function load() {

        include( 'nusoap/lib/nusoap.php');

        $client = new nusoap_client(Config::factory()->getParam('cmb.cpa.webservice.unit'));

        $response = $client->call('ListaUnidades');

        if ($client->getError()) {
            throw new Exception($client->getError());
        }

        return json_decode($response);
    }

}