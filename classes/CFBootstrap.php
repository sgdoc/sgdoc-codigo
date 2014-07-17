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
class CFBootstrap extends CFBootstrapAbstract {

    /**
     * @var CFBootstrap
     */
    private static $instance;

    /**
     * @return CFBootstrap
     */
    protected static function singleton() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return CFBootstrap
     */
    public static function factory() {
        return self::singleton();
    }

    /**
     * @return void
     */
    protected function _initialize() {
    }

    /**
     * @return array;
     */
    public function getPDOExtraParams() {
        return array(
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_CASE => \PDO::CASE_UPPER,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        );
    }

    /**
     * @return void
     */
    public function dispatch() {
        $this->_initialize();
    }

}