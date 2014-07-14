<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class Bootstrap {

    protected static $_config = NULL;
    protected static $_instance = NULL;

    /**
     * @return Bootstrap
     */
    public static function factory() {

        if (!is_null(self::$_instance)) {
            return self::$_instance;
        }

        return self::$_instance = new self();
    }

    /**
     * @return void
     */
    private function __construct() {
        define('PATH_APP', dirname(__FILE__) . '\..');
        self::$_config = parse_ini_file("config/configuration.ini", false);
    }

    /**
     * @param string $key
     * @return string
     */
    public function config($key) {
        return self::$_config["{$key}"];
    }

}
