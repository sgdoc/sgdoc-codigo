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
 */
abstract class CFBootstrapAbstract {

    /**
     * 
     */
    protected $_request = NULL;
    protected $_action = 'index';
    protected $_controller = 'index';
    protected $_cfg = NULL;

    /**
     * @return void
     */
    public function __construct() {
        /**
         * Request
         */
        $requestUri = explode('/', $_SERVER['REQUEST_URI']);
        $this->_request = $_REQUEST;


        /**
         * Capturar Controller
         */
        if (isset($requestUri[1])) {
            $this->_controller = ($requestUri[1]) ? $requestUri[1] : 'index';
        }

        /**
         * Capturar Action
         */
        if (isset($requestUri[2])) {
            $this->_action = ($requestUri[2]) ? $requestUri[2] : 'index';
        }

        foreach ($requestUri as $key => $value) {
            if ($key > 2 && $key % 2 != 0) {
                $_REQUEST[$requestUri[$key]] = isset($requestUri[$key + 1]) ? $requestUri[$key + 1] : NULL;
            }
        }

        /**
         * Config
         */
        $this->_cfg = CFConfig::factory()->buildAppConfig()->buildConstants();
    }

    /**
     * @return boolean
     */
    public function isAjax() {
        return strstr($_SERVER['REQUEST_URI'], 'ajax/true') ? true : false;
    }

    /**
     * 
     */
    abstract public static function factory();

    /**
     * 
     */
    abstract protected static function singleton();

    /**
     * @return array
     */
    abstract public function getPDOExtraParams();

    /**
     * 
     */
    public function getRequest() {
        return $this->_request;
    }

    /**
     * @return Config
     */
    public function getConfig() {
        return $this->_cfg;
    }

    /**
     * @return string
     */
    public function getAction() {
        return $this->_action;
    }

    /**
     * @return string
     */
    public function getController() {
        return $this->_controller;
    }

    /**
     * @return void
     */
    public function dispatch() {
        $this->_initialize();
        $this->_router();
    }

    /**
     * 
     */
    abstract protected function _initialize();

    /**
     * 
     */
    private function _router() {
        try {
            if (isset($this->_controller)) {
                $nameController = ucfirst($this->_controller);
                $nameAction = $this->_action;
                if (file_exists(CF_APP_CONTROLLER_PATH . "/Controller{$nameController}.php")) {
                    $controller = "Controller{$nameController}";
                    if (isset($nameAction)) {
                        $action = "{$nameAction}Action";
                    } else {
                        $action = 'indexAction';
                    }
                } else {
                    throw new \Exception("Controller Inválido...({$nameController})");
                }
            } else {
                $controller = 'ControllerIndex';
                $action = 'indexAction';
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $nameSpace = "br\com\cf\app\controller\\" . $controller;

        $controller = new $nameSpace();
        $controller->before();
        $controller->$action();
        $controller->after();
    }

}