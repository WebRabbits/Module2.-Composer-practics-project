<?php 


namespace Connection;
use PDO;

class Connection {
    public static function Connect(){
        return new PDO("mysql:host=mysql-8.2;dbname=module2_project1", "root", "root");
    }
}

?>