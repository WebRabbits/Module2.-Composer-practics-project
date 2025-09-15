<?php 

namespace App\controllers;

use App\QueryBuilder;
use Connection\Connection;
require(__DIR__ . "/../../config/GlobalsConfig.php");


class PostsController{
    public function getAllPosts() {
        $db = new QueryBuilder(Connection::Connect());
        
        $result = $db->getAll("posts");
        // var_dump($result);
        foreach($result->result() as $post) {
             var_dump($post->title);
        }
    }
}

?>