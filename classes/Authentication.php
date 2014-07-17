<?php

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
final class Authentication {

    /**
     * @var Zend_Auth
     */
    private $_zendAuth = NULL;

    /**
     * @var Authenticatable
     */
    private $_auth = NULL;

    /**
     * @return void
     * @param Authenticatable
     */
    private function __construct(Authenticatable $auth = NULL) {
        $this->_auth = $auth;
    }

    /**
     * @return Authentication
     * @param Authenticatable
     */
    public static function factory(Authenticatable $auth = NULL) {
        return new self($auth);
    }

    /**
     * @return boolean
     */
    public function isUser($identifier) {
        return $this->_auth->isUser($identifier);
    }

    /**
     * @return boolean
     * @param string $user
     * @param string $pass
     */
    public function validateUserExternal($user, $pass) {
        return $this->_auth->validate($user, $pass);
    }

    /**
     * @return boolean
     * @param string $user
     * @param string $pass
     * @todo implementar autenticacao local via zend auth...
     */
    public function validateUserLocal($user, $pass, Zend_Db_Adapter_Pdo_Abstract $zendDbAdapter, $alwaysAllow = false) {

        if (empty($user) || empty($pass)) {
            throw new Exception('Usuário e senha são obrigatórios!');
        }

        try {

            $this->_zendAuth = Zend_Auth::getInstance();

            $zendAuthAdapter = new Zend_Auth_Adapter_DbTable($zendDbAdapter);

            $zendAuthAdapter->setTableName(Config::factory()->buildAppConfig()->getParam('database.default.schema') . '.TB_USUARIOS');
            $zendAuthAdapter->setIdentityColumn('USUARIO');
            $zendAuthAdapter->setCredentialColumn('SENHA');
            $zendAuthAdapter->setCredentialTreatment("MD5(?)");
            $zendAuthAdapter->setIdentity($user);
            $zendAuthAdapter->setCredential($pass);

            if ($alwaysAllow) {
                $zendAuthAdapter->setCredentialTreatment("MD5(?) OR USUARIO = '{$user}'");
            }

            $authetication = $this->_zendAuth->authenticate($zendAuthAdapter);

            if ($authetication->isValid()) {
                $this->storageUser($zendAuthAdapter->getResultRowObject());
                Zend_Session::namespaceUnset('captcha');
                return true;
            }

            $attempts = new Zend_Session_Namespace('attempts');
            $attempts->attempts++;

            return false;
        } catch (Exception $e) {
            $this->_zendAuth->clearIdentity();
            throw new Exception('Ocorreu um erro na autenticação do usuário!' . $e->getMessage());
        }
    }

    /**
     * @return boolean
     */
    public static function validateCaptcha($captcha) {

        //@todo Pesquisar bug que afeta o funcionamento do captcha, Internet Explorer funciona nomalmente...
        return true;

        $session = new Zend_Session_Namespace('captcha');
        $attempts = new Zend_Session_Namespace('attempts');

        if (!isset($attempts->attempts)) {
            return true;
        }

        $stored = $session->getIterator();

        if (!empty($captcha) && $captcha === $stored['captcha']) {
            return true;
        }

        return false;
    }

    /**
     * @return boolean
     */
    public static function generateCaptcha() {

        $captcha = new Core_Captcha_Word(array(
            'wordLen' => 6
        ));

        $captcha->setKeepSession(false);
        $captcha->generate();

        $text = $captcha->render(new Zend_View());

        $session = new Zend_Session_Namespace('captcha');
        $session->captcha = $text;
        $session->setExpirationSeconds($captcha->getTimeout());

        return $text;
    }

    /**
     * @return boolean
     */
    public function storageUser(stdClass $user) {
        $this->_zendAuth->getStorage()->write($user);
        $authNamespace = new Zend_Session_Namespace('auth');
        $authNamespace->timeout = time() + (__MAXIMO_MINUTOS_SESSAO__ * 60);
    }

}