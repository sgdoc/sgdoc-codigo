<?php

/**
 * 
 * Copyright 2008 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuíção e/ou modifição dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuíção na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 */

/**
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
class Config extends CFConfig {

    /**
     * Db
     */
    public $connection;
    public $database;
    public $user;
    public $password;
    public $host;
    public $envs = array(   
        'prd' => '',                    //Produção Icmbio
        'prd-presidencia' => '',        //Produção Presidência
        'hmg' => ' - homologação',      //Homologação
        'dsv' => ' - desenvolvimento',  //Desenvolvimento
        'trn' => ' - treinamento',       //Treinamento
    	'tes' => ' - teste',             //Teste
        'dev-presidencia' => ' - Dev Presidência'
    );
    public $xml;

    /**
     * @return Config
     */
    public static function factory() {
        return new self();
    }

    /**
     * @return Config
     */
    public function buildDBConfig() 
    {
        $this->host     = (string) $this->_params['database.default.host'];
        $this->database = (string) $this->_params['database.default.database'];
        $this->user     = (string) $this->_params['database.default.user'];
        $this->password = (string) $this->_params['database.default.password'];
        $this->driver   = (string) $this->_params['database.default.driver'];
        $this->schema   = (string) $this->_params['database.default.schema'];

        return $this;
    }

    /**
     * @return Config
     */
    public function buildAppConstants() 
    {
        /**
         * Defines
         */
        define('__ENCODE__', $this->_params['config.encode']);
        define('__MAXIMO_MINUTOS_SESSAO__', $this->_params['config.sessiontime']);
        define('__INTERVAL_NTF_PRAZO__', $this->_params['config.notificationtime']);
        define('__MAXIMO_DIAS_NAO_LOGADO__', $this->_params['config.offlinetime']);
        define('__SERVER_ENCODE__', ini_get('default_charset'));
        define('__APPBANCO__', $this->_params['database.default.database']);
        define('__APPVERSAO__', "{$this->_params['config.version']}{$this->envs[CF_APP_ENVIRONMENT]}");
        define('__URLSERVERAPP__', $this->_params['config.url']);
        define('__URLSERVERFILES__', $this->_params['config.url']);
        define('__DIR_IMAGENS__', $this->_params['config.dirimages']);
        define('__RODAPE__', "2008/" . date("Y") . " - {$this->_params['config.namedeveloper']} - V.{$this->_params['config.version']}");
        define("__CAM_IMAGENS__", __URLSERVERFILES__ . "/" . __DIR_IMAGENS__ . "/");
        define("__APPMODELS__", __URLSERVERAPP__ . '/modelos/');
        define("__CAM_UPLOAD__", $this->_params['config.appfiles']);
        define("__BASE_PATH__", $this->_params['config.basepath']);
        define("__EMAILLOGS__", $this->_params['config.emaildeveloper']);
        define("__ETIQUETA__", $this->_params['config.textoetiqueta']);
        define("__RASTREAMENTO__", $this->_params['config.rastreamento']);
        define("__CACHEIMAGENSDIAS__", $this->_params['config.timecacheimages']);
        define("__EMAILSNOTIFICATIONFATALERROR__", $this->_params['config.emailsfatalerror']);
        define("__ENVIRONMENT__", CF_APP_ENVIRONMENT);
        define("__VERSAO__", $this->_params['config.version']);
        define("__LOGO_JPG__", $this->_params['config.logo']);
        define("__BACKGROUND__", $this->_params['config.background']);
        define("__CABECALHO_ORGAO__", $this->_params['config.cabecalho']);
        define("__ADAPTER_AUTENTICACAO__", $this->_params['adapter.authentication.class']);
        define("__ADAPTER_SINCRONIZACAO_USUARIO__", $this->_params['adapter.synchronizer.user.class']);
        define("__ADAPTER_SINCRONIZACAO_PERMISSAO__", $this->_params['adapter.synchronizer.permission.class']);
        define("__ADAPTER_SINCRONIZACAO_UNIDADE__", $this->_params['adapter.synchronizer.unit.class']);

        if (isset($this->_params['config.icpbrasil.certificado.caminho'])){
            define("__CONFIG_ICPBRASIL_CERTIFICADO_CAMINHO__", $this->_params['config.icpbrasil.certificado.caminho']);
        }else{
            define("__CONFIG_ICPBRASIL_CERTIFICADO_CAMINHO__", '');
        }

        return $this;
    }

    /**
     * @return Config
     */
    public function buildAppDefines() 
    {
        date_default_timezone_set('America/Sao_Paulo');
        ini_set('default_charset', __ENCODE__);

        return $this;
    }

    /**
     * @return Config
     */
    public function buildEnvironment() 
    {
        /**
         * Include Path
         */
        set_include_path(
            implode(PATH_SEPARATOR, array("{$this->_params['config.basepath']}/classes", 
                "{$this->_params['config.basepath']}/library",
                "{$this->_params['config.basepath']}/bibliotecas/phpmailer",
                "{$this->_params['config.basepath']}/bibliotecas",
                get_include_path())
            )
        );

        /**
         * Zend_Loader_Autoloader
         */
        require_once 'Zend/Loader/Autoloader.php';

        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->setFallbackAutoloader(true);
        $loader->suppressNotFoundWarnings(false);

        /**
         * SHOW ERRORS
         */
        if (CF_APP_ENVIRONMENT == 'dsv' && $this->_params['config.safemode'] == 'false') {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
            ini_set('display_errors', 'On');
        } else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
            ini_set('display_errors', 'Off');
        }

        ini_set('error_log', "cache/logs/erros.log");

        return $this;
    }

    /**
     * Connection Generic
     * @deprecated
     * @todo Remover todas as conexoes genericas...
     */
    public function getConnection() 
    {
        $this->connection = mysql_connect($this->_params['database.default.host'], $this->_params['database.default.user'], $this->_params['database.default.password']) or die('ERRO  AO CONECTAR!');
        mysql_select_db($this->_params['database.default.database'], $this->connection);
        return $this->connection;
    }

    /**
     * @deprecated
     * @todo Remover...
     * @return Zend_Db_Table
     */
    public function getZendDbTable() 
    {
        $params = array();

        $params['host']     = (string) $this->_params['database.default.host'];
        $params['dbname']   = (string) $this->_params['database.default.database'];
        $params['password'] = (string) $this->_params['database.default.password'];
        $params['username'] = (string) $this->_params['database.default.user'];
        $params['options']  = array(
            Zend_Db::CASE_FOLDING => Zend_Db::CASE_UPPER,
            Zend_Db::AUTO_QUOTE_IDENTIFIERS => false
        );

        $db = Zend_Db::factory("pdo_{$this->_params['database.default.driver']}", $params);
        $db->query("set search_path to {$this->_params['database.default.schema']};", 'execute');

        Zend_Db_Table::setDefaultAdapter($db);

        return $db;
    }

}
