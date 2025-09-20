<?php

namespace App\controllers;

use App\QueryBuilder;
use Connection\Connection;
use League\Plates\Engine;

require(__DIR__ . "/../../config/GlobalsConfig.php");


class PostsController
{
    private $db = null;
    private $templates = null;

    public function __construct()
    {
        $this->db = new QueryBuilder(Connection::Connect());
        $this->templates = new Engine("../app/views");
    }
    public function getAllPosts()
    {
        $posts = $this->db->getAll("posts")->result();

        echo $this->templates->render("posts", ["postsInView" => $posts]);
    }
}
