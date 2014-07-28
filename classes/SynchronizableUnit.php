<?php

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
interface SynchronizableUnit {

    /**
     * @return Unit
     */
    public function load();
}