<?php
if (!session_id()) {
    session_start();
}


require "../vendor/autoload.php";

$templates = new League\Plates\Engine("../app/views");

use function Tamtamchik\SimpleFlash\flash;
// var_dump($templates);

echo $templates->render("homepage", ["name" => "John"]);
// echo $templates->render("contacts");
// echo $templates->render("about");



$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/users', ["App\controllers\HomeController", "index"]); // Название контроллера, названеи метода
    $r->addRoute('GET', '/user/{id:\d+}', ["App\controllers\HomeController", "about"]);
    $r->addRoute('GET', '/user/{id:\d+}/store/classes/{number:\d+}', ["App\controllers\HomeController", "about"]);
    $r->addRoute("GET", "/posts", ["App\controllers\PostsController", "getAllPosts"]);
    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo 404;
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo "Метод не разрешён";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // d($handler);
        // die;
        // d($vars);

        $controller = new $handler[0];
        // d($controller);
        // var_dump($controller);

        call_user_func([$controller, $handler[1]], $vars);

        break;
}

// function get_all_users_handler() {
//     echo "страница всех юзеров 1";
// }

// function get_user_handler($vars){
//     ["id" => $id] = $vars;
//     echo "страница одного из пользователей $id";
// }

// function get_article_handler() {
//     echo "артикль с айди и тайтлом 3";
// }









//// Использование фичи компонента "Kint" - деббагинг
// d($_SESSION);


//// Вывод Flash сообщений на экран пользователю
// flash()->message("Hot!");
// flash()->success("Success!");
// flash()->warning("Warning!!!");
// flash()->error("Error message!");
// echo flash()->display("info");
// echo flash()->display();



// if($_SERVER["REQUEST_URI"] == "/home") {
//     require "../app/controllers/homepage.php";
// }

// exit();
