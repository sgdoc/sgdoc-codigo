<?php

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
class LDAP {

    /**
     * @return ResourceBundle
     * @param string $server
     * @param integer $port
     * @param integer $version
     */
    function connect($server, $port = 389, $version = 3) {

        $connection = ldap_connect($server, $port);

        ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, $version);

        return $connection;
    }

    /**
     * @return boolean
     * @param ResourceBundle $connection
     * @param strin $basedn
     * @param string $basepass
     */
    function bind($connection, $basedn, $basepass) {
        return ldap_bind($connection, $basedn, $basepass);
    }

    /**
     * @return array 
     * @param type $connection
     * @param string $searchdn
     * @param string $filter
     * @param array $attributes
     */
    public function search($connection, $searchdn, $filter, $attributes = array()) {
        $sr = ldap_search($connection, $searchdn, $filter, $attributes);
        if ($sr) {
            return ldap_get_entries($connection, $sr);
        } else {
            throw new Exception('A pesquisa na base LDAP falhou!');
        }
    }

    /**
     * @return boolean
     * @param ResourceBundle $connection
     * @param string $adddn
     * @param array $record
     */
    public function addRecord($connection, $adddn, $record) {
        return ldap_add($connection, $adddn, $record);
    }

    /**
     * @return ResourceBundle
     * @param string $modifydn
     * @param array $record
     */
    public function modifyRecord($connection, $modifydn, $record) {
        return ldap_modify($connection, $modifydn, $record);
    }

    /**
     * @return boolean
     * @param ResourceBundle $connection
     * @param string $connection
     * @param boolean $recursive
     */
    public function deleteRecord($connection, $dn, $recursive = false) {

        if ($recursive == false) {
            return ldap_delete($connection, $dn);
        } else {
            $sr = ldap_list($connection, $dn, "ObjectClass=*", array(""));
            $info = ldap_get_entries($connection, $sr);

            for ($i = 0; $i < $info['count']; $i++) {
                $result = myldap_delete($connection, $info[$i]['dn'], $recursive);
                if (!$result) {
                    return $result;
                }
            }

            return ldap_delete($connection, $dn);
        }
    }

    /**
     * @return boolean
     * @param ResourceBundle $connection
     */
    function close($connection) {
        return ldap_close($connection);
    }

}