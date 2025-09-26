<?php 

namespace Config;

use Exception;

class Config {
    public static function getDataConfig($path = null) {
        if($path) {
            $config = &$GLOBALS["config"];

            $path = explode(".", $path);

            foreach($path as $key) {
                if(isset($config[$key])) {
                    $config = &$config[$key];
                }
            }
            try {
                if(is_array($config)) {
                    throw new Exception("Returned an array, but expected a string");
                }
                return $config;
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        return false;
    }

    public static function setDataConfig($path = null, $assignedValue = "") {
        if($path) {
            $config = &$GLOBALS["config"];

            $path = explode(".", $path);

            foreach($path as $key) {
                if(isset($config[$key])) {
                    $config = &$config[$key];
                }
            }
            
            $config = $assignedValue;
            return $config;
        }
    }
}


?>