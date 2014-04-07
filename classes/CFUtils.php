<?php

/*
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
 * */

/**
 * @author Michael F. Rodrigues <cerberosnash@gmail.com>
 */
class CFUtils {

    /**
     * @return string
     * @param string $date1
     * @param string $date2
     * @param string $return Options ('years','months','days','hours','minuts','seconds')
     */
    public static function diffDates($date1, $date2, $return = 'seconds') {

        if (!in_array($return, array('years', 'months', 'days', 'hours', 'minuts', 'seconds'))) {
            throw new \Exception('Tipo de retorno inválido!');
        }

        # subtracao das datas...
        $diff = abs(strtotime($date2) - strtotime($date1));

        # diferenca em anos...
        $years = floor($diff / (365 * 60 * 60 * 24));
        # diferenca em anos...
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        # diferenca em anos...
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        # diferenca em anos...
        $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        # diferenca em anos...
        $minuts = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        # diferenca em anos...
        $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minuts * 60));

        return (string) ${$return};
    }

    /**
     * @return array
     * @param string $filename
     */
    public static function parseIniFile($filename) {
        $array = parse_ini_file($filename, true);
        $returnArray = array();
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $x = explode(':', $key);
                if (!empty($x[1])) {
                    $x = array_reverse($x, true);
                    foreach ($x as $k => $v) {
                        $i = trim($x[0]);
                        $v = trim($v);
                        if (empty($returnArray[$i])) {
                            $returnArray[$i] = array();
                        }
                        if (isset($array[$v])) {
                            $returnArray[$i] = array_merge($returnArray[$i], $array[$v]);
                        }
                        if ($k === 0) {
                            $returnArray[$i] = array_merge($returnArray[$i], $array[$key]);
                        }
                    }
                } else {
                    $returnArray[$key] = $array[$key];
                }
            }
        } else {
            return false;
        }

        return $returnArray;
    }

    /**
     * @return string
     */
    public static function random() {
        return md5(microtime() . date("m") . rand(0, 999));
    }

    /**
     * Converte data no padra dd/mm/aaaa em aaaa-mm-dd ou vice-versa
     * @return string
     * @param date $date
     */
    public static function formatDate($date/* dd/mm/aaaa */) {
        if (strstr($date, "/")) {//verifica se tem a barra /
            $d = explode("/", $date); //tira a barra
            $rstData = "$d[2]-$d[1]-$d[0]"; //separa as datas $d[2] = ano $d[1] = mes etc...
            return $rstData;
        } else if (strstr($date, "-")) {
            $d = explode("-", $date);
            $rstData = $d[2] . "/" . $d[1] . "/" . $d[0];
            return $rstData;
        } else {
            return false; //Para Debugger usar 2009-01-01
        }
    }

    /**
     * @return void
     * @param mixed $obj
     * @param boolean $exit
     * @param boolean $outputRaw
     */
    public static function dump($obj, $exit = TRUE, $outputRaw = TRUE) {

        $trace = array();
        $backtrace = debug_backtrace();

        $totalCall = sizeof($backtrace);
        for ($i = 0; $i < $totalCall; $i++) {
            if (!$i) {
                $trace[$i] = ' +';
            } elseif ($i + 1 == $totalCall) {
                $trace[$i] = ' \\';
            } else {
                $trace[$i] = ' |';
            }
            $trace[$i] .= str_repeat('-', $totalCall - $i);
            $trace[$i] .= "> {$backtrace[$i]['file']}::{$backtrace[$i]['line']}\n";
        }

        $eol = "\n";
        if (TRUE == $outputRaw) {
            header('Content-Type: text/plain; charset=UTF-8');
        } else {
            header('Content-Type: text/html; charset=UTF-8');
            echo "<pre>";
            $eol = '<br />';
        }
        echo $eol, '[CALL STACK]', $eol;
        foreach ($trace as $indice => $value) {
            echo $value;
        }

        echo $eol, '[VALUE]', $eol;
        print_r($obj);
        echo $eol;

        if ($exit) {
            die;
        }
    }

}