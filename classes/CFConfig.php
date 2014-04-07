<?php

/*
 * Copyright 2008 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 * */

/**
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 * @version 0.0.0
 */
class CFConfig {

    /**
     * @var Config
     */
    protected static $instance;
    protected $_params = array();

    /**
     * @return void
     */
    protected function __construct() {
        $configuration = CFUtils::parseIniFile(CF_APP_BASE_PATH . '/cfg/configuration.ini');
        $this->_params = $configuration[CF_APP_ENVIRONMENT];
    }

    /**
     * @return Config
     */
    protected static function singleton() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return Config
     */
    public static function factory() {
        return self::singleton();
    }

    /**
     * @return mixed
     */
    public function getParam($name) {
        return $this->_params["{$name}"];
    }

    /**
     * @return Config
     */
    public function buildConstants() {
        /**
         * 
         */
        if (defined('CF_APP_DEFINED')) {
            return $this;
        }

        /**
         * Constants
         */
        define('CF_APP_DEFINED', 'TRUE');
        define('CF_APP_VERSION', $this->_params['config.version']);
        define('CF_APP_NAME', $this->_params['config.name']);
        define('CF_APP_URL', $this->_params['config.url']);
        define('CF_APP_HASH', $this->_params['config.hash']);
        define('CF_APP_TMP_PATH', constant('CF_APP_BASE_PATH') . '/br/com/cf/tmp');
        define('CF_APP_LOG_PATH', $this->_params['config.log']);
        define('CF_APP_LOG_FILE', constant('CF_APP_BASE_PATH') . '/br/com/cf/' . constant('CF_APP_LOG_PATH') . '/default.log');
        define('CF_APP_UPLOADER_PATH', constant('CF_APP_TMP_PATH') . '/uploader');
        define('CF_APP_CACHE_PATH', constant('CF_APP_BASE_PATH') . '/br/com/cf/public/cache');
        define('CF_APP_LIBRARY_PATH', constant('CF_APP_BASE_PATH') . '/br/com/cf/library');
        define('CF_APP_PUBLIC_PATH', constant('CF_APP_BASE_PATH') . '/br/com/cf/public');
        define('CF_APP_MODEL_PATH', constant('CF_APP_BASE_PATH') . '/br/com/cf/app/model');
        define('CF_APP_BUSINESS_PATH', constant('CF_APP_BASE_PATH') . '/br/com/cf/app/business');
        define('CF_APP_CONTROLLER_PATH', constant('CF_APP_BASE_PATH') . '/br/com/cf/app/controller');
        define('CF_APP_VIEW_PATH', constant('CF_APP_BASE_PATH') . '/br/com/cf/app/view');
        define('CF_APP_PARTIALS_PATH', constant('CF_APP_VIEW_PATH') . '/partials');
        define('CF_APP_VIEW_TYPE_DEFAULT', $this->_params['config.defaultTypeView']);

        return $this;
    }

    /**
     * @return Config
     */
    public function buildAppConfig() {
        /**
         * TimeZone
         */
        date_default_timezone_set('America/Sao_Paulo');

        /**
         * Default CharSet
         */
        ini_set('default_charset', 'UTF-8');

        /**
         * Degugger
         */
        if (CF_APP_ENVIRONMENT == 'dsv') {
            error_reporting(E_ALL & ~E_STRICT);
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
        }

        return $this;
    }

}