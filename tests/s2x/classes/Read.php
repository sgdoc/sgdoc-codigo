<?php

/**
 * @author Michael Fernandes <cerberosnash@gmail.com>
 */
class Read {

    /**
     * @return Read
     */
    public static function factory() {
        return new self();
    }

    /**
     * 
     */
    private function __construct() {
        
    }

    /**
     * @param string $directory
     * @param boolean $recursive
     * @return array
     */
    public function directoryToArray($directory, $recursive) {
        $array_items = array();
        if ($handle = opendir($directory)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (is_dir($directory . "/" . $file)) {
                        if ($recursive) {
                            $array_items = array_merge($array_items, $this->directoryToArray($directory . "/" . $file, $recursive));
                        }
                        $file = $directory . "/" . $file;
                        $array_items[] = preg_replace("/\/\//si", "/", $file);
                    } else {
                        $file = $directory . "/" . $file;
                        $array_items[] = preg_replace("/\/\//si", "/", $file);
                    }
                }
            }
            closedir($handle);
        }
        return $array_items;
    }

}
