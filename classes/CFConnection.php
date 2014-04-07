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
class CFConnection {

    /**
     * @var \PDO
     */
//    private static $connection = array();

    /**
     * @return void
     */
    private function __construct($entry = 'default') {
//
//        try {
//
//            $cfg = CFConfig::factory();
//
//            $database = $cfg->getParam("database.{$entry}.database");
//            $host = $cfg->getParam("database.{$entry}.host");
//            $user = $cfg->getParam("database.{$entry}.user");
//            $password = $cfg->getParam("database.{$entry}.password");
//            $driver = $cfg->getParam("database.{$entry}.driver");
//
//            self::$connection[$entry] = new \PDO(sprintf("%s:host=%s;dbname=%s", $driver, $host, $database),
//                            $user, $password, CFBootstrap::factory()->getPDOExtraParams()
//            );
//            
//            self::$connection[$entry]->setAttribute(
//                    \PDO::ATTR_CASE, \PDO::CASE_UPPER);
//
//            self::$connection[$entry]->setAttribute(
//                    \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION
//            );
//        } catch (\PDOException $e) {
//            throw new \Exception($e);
//        }
    }

    /**
     * @return \PDO
     */
    public static function factory($entry) {
        return Controlador::getInstance()->getConnection()->connection;
    }

    /**
     * @return \PDO
     */
    private static function singleton($entry) {
//        if (!isset(self::$connection[$entry])) {
//            new self($entry);
//        }
//        return self::$connection[$entry];
    }

}