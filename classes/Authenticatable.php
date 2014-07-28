<?php

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
interface Authenticatable {

    /**
     * 
     */
    public function validate($user, $pass);

    /**
     * 
     */
    public function isUser($identifier);
}