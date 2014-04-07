<?php

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
class AuthenticationWebServiceICMBio implements Authenticatable {

    /**
     * @var LDAP
     */
    private $_ldap = NULL;

    /**
     * @return void
     */
    public function __construct() {

        ini_set('display_errors', 'Off');

        $this->_ldap = new LDAP();
    }

    /**
     * @return void
     */
    public function __destruct() {
        register_shutdown_function('HandleFatalError');
    }

    /**
     * @return integer
     */
    public function status() {
        return $this->_status;
    }

    /**
     * @return boolean
     * @param string $identifier
     */
    public function isUser($identifier) {


        $status = $this->_validateSMB3($identifier, NULL, true);

        switch ($status) {

            //Usuario migrado para o SAMBA 4...
            case 3:
                return $this->_validateSMB4($identifier, NULL, true) === 4 ? true : false;
                break;

            //Usuario existe...
            case 4:
                return true;
                break;

            //Usuario nao existe no SMB3...
            case 5:
                return $this->_validateSMB4($identifier, NULL, true) === 4 ? true : false;
                break;

            default:
                throw new Exception('Algum erro ocorreu ao tentar verificar se o usuário existe no LDAP!');
                break;
        }
        exit;
    }

    /**
     * @return boolean
     * @param string $user
     * @param string $pass
     */
    public function validate($user, $pass) {

        $status = $this->_validateSMB3($user, $pass);

        switch ($status) {

            //Autenticacao Invalida...
            case 0:
                return false;
                break;

            //Autenticacao Valida...
            case 1:
                return true;
                break;

            //Usuario migrado para o SAMBA 4...
            case 3:
                return $this->_validateSMB4($user, $pass) === true ? true : false;
                break;
            //Se nao existir no SMB3 entao verificar no SMB4..
            case 5:
                return $this->_validateSMB4($user, $pass) === true ? true : false;
                break;

            //Operacao invalida...
            default:
                throw new Exception('Algum erro ocorreu ao tentar efetuar a autenticação no LDAP!');
                break;
        }
    }

    /**
     * @return integer
     * @param string $user
     * @param string $password
     */
    private function _validateSMB3($user, $password, $onlyCheckIsExistsUser = false) {

        //Conexao com o servidor...
        $connection = $this->_ldap->connect(
                Config::factory()->getParam('extra.ldap.samba3.host'), Config::factory()->getParam('extra.ldap.samba3.port'), Config::factory()->getParam('extra.ldap.samba3.version')
        );


        //Conectar no servidor como administrador e obter o DN...
        $response = $this->_ldap->search(
                $connection, Config::factory()->getParam('extra.ldap.samba3.dn'), Config::factory()->getParam('extra.ldap.samba3.filter') . Config::factory()->getParam('extra.ldap.samba3.user'), array('dn')
        );

        //Autenticar o Administrador...
        $this->_ldap->bind(
                $connection, $response[0]['dn'], Config::factory()->getParam('extra.ldap.samba3.password')
        );

        //Verificar o usuario informado existe...
        $user = $this->_ldap->search(
                $connection, Config::factory()->getParam('extra.ldap.samba3.dn'), Config::factory()->getParam('extra.ldap.samba3.filter') . $user, array('sambaprofilepath')
        );

        //Verificar se o usuario existe...
        if (count($user) > 1) {
            //Verificar se o perfil do usuario jah foi migrado para o samba 4
            if ($user[0]['sambaprofilepath'][0] === 'true') {
                return 3;
            }
            if ($onlyCheckIsExistsUser) {
                return 4;
            }
        } else {
            return 5;
        }

        //Autenticar o Usuario...
        $status = (integer) $this->_ldap->bind($connection, $user[0]['dn'], $password);

        //Fechar Conexao...
        $this->_ldap->close($connection);

        return $status;
    }

    /**
     * @return boolean
     * @param string $user
     * @param string $password
     */
    private function _validateSMB4($user, $password, $onlyCheckIsExistsUser = false) {

        //Conexao com o servidor...
        $connection = $this->_ldap->connect(
                Config::factory()->getParam('extra.ldap.samba4.host'), Config::factory()->getParam('extra.ldap.samba4.port'), Config::factory()->getParam('extra.ldap.samba4.version')
        );

        //Autenticar o Administrador...
        $this->_ldap->bind(
                $connection, Config::factory()->getParam('extra.ldap.samba4.user'), Config::factory()->getParam('extra.ldap.samba4.password')
        );

        //Recuperar DN do usuario para autenticacao...
        $user = $this->_ldap->search(
                $connection, Config::factory()->getParam('extra.ldap.samba4.dn'), Config::factory()->getParam('extra.ldap.samba4.filter') . $user, array('dn')
        );
        //Verificar se o usuario existe...
        if (count($user) > 1) {
            if ($onlyCheckIsExistsUser) {
                return 4;
            }
        } else {
            return 5;
        }

        //Autenticar o Usuario...
        $status = $this->_ldap->bind($connection, $user[0]['dn'], $password);

        //Fechar Conexao...
        $this->_ldap->close($connection);

        return $status;
    }

}
