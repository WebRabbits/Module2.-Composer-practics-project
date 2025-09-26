<?php

namespace App\controllers;

use App\QueryBuilder;
use App\controllers\PaginationController;
use League\Plates\Engine;
use Faker\Factory;


class PostsController
{
    private $db = null;
    private $templates = null;
    private $faker = null;

    public function __construct(QueryBuilder $queryBuilder, Engine $engine)
    {
        $this->db = $queryBuilder;
        $this->templates = $engine;
        $this->faker = Factory::create();
    }
    public function getAllPosts()
    {
        return $this->db->getAll("posts")->result();

        // echo $this->templates->render("posts", ["postsInView" => $posts]);
    }

    public function getPostsPagination() {
        $posts = $this->db->limitOffsetPaginationData("posts", 10, "page")->result();
        $pagination = PaginationController::pagination(count($this->getAllPosts()));
        echo $this->templates->render("posts", ["postsInView" => $posts, "pagination" => $pagination]);
        
    }

    public function addPostsToFaker() {
        // d($this->faker->words(3, true));
        // die;
        $this->db->insert("posts", ["title" => "test data fun", "date" => date("Y-m-d H:i:s")]);

        for($i = 0; $i < 30; $i++) {
            $this->db->insert("posts", [
                "title" => $this->faker->words(3, true),
                "date" => date("Y-m-d H:i:s"),
            ]);
        }

        header("Location: \posts");


    }
}
