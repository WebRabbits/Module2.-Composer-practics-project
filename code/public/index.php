<?php 

require "../vendor/autoload.php";


if($_SERVER["REQUEST_URI"] == "/home") {
    require "../app/controllers/homepage.php";
}

exit();




// use App\QueryBuilder;

// $db = new QueryBuilder();

// var_dump($db);

// use Aura\SqlQuery\QueryFactory;

// $queryFactory = new QueryFactory("mysql");

// $select = $queryFactory->newSelect();

// $select->cols(["*"])->from("posts")->where("id IN (:id)", ["id" => [1, 7]]);


// $pdo = new PDO("mysql:host=mysql-8.2;dbname=module2_project1", "root", "root");
// $stmt = $pdo->prepare($select->getStatement());
// $stmt->execute($select->getBindValues());
// $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// var_dump($result);

// echo 123;

?>