<?php 

namespace Connection;
use PDO;
use Config\Config;

class Connection {

    public static function Connect(){
        return new PDO("mysql:host=" . Config::getDataConfig("mysql_connection.host") . ";dbname=" . Config::getDataConfig("mysql_connection.database"), Config::getDataConfig("mysql_connection.username"), Config::getDataConfig("mysql_connection.password"));
    }
}

?>