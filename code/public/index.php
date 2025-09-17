<?php


if (!session_id()) {
    session_start();
}


require "../vendor/autoload.php";

$templates = new League\Plates\Engine("../app/views");

use Illuminate\Support\Arr;

use function Tamtamchik\SimpleFlash\flash;
// var_dump($templates);

// echo $templates->render("homepage", ["name" => "John"]);
// echo $templates->render("contacts");
// echo $templates->render("about");



$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/home', ["App\controllers\HomeController", "index"]); // Название контроллера, названеи метода
    $r->addRoute('GET', '/about/{amount:\d+}', ["App\controllers\HomeController", "about"]); // Название контроллера, названеи метода
    // $r->addRoute('GET', '/user/{id:\d+}', ["App\controllers\HomeController", "about"]);
    // $r->addRoute('GET', '/user/{id:\d+}/store/classes/{number:\d+}', ["App\controllers\HomeController", "about"]);
    // $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
    $r->addRoute("GET", "/posts", ["App\controllers\PostsController", "getAllPosts"]);
    // $r->addRoute("GET", "/", "");
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



//// Exceptions - Исключения (обработка ошибок)

// function withdraw($amount = 1)
// {
//     static $total = 10;

//     if ($amount > $total) {
//         // ... Exception
//         throw new Exception("Недостаточно средств", 111);
//     }

//     $total -= $amount;

//     return "Вы вывели сумму $amount EUR. Доступно для списания $total" . "<br>";
// }

// try {
//     echo withdraw();
//     echo withdraw(3);
//     echo withdraw(5);
//     echo withdraw(3.5);
// } catch (Exception $e) {
//     flash()->error($e->getMessage() . " code: " . $e->getCode());
//     echo flash()->display("error");
// }



//// Использование Illuminate/Support - позволяет использовать разные методы для работы с разными типами данных на основе Laravel
// $array = [
//     ["mentor" => ["course" => "HTML"]],
//     ["mentor" => ["course" => "JavaScript"]],
//     ["mentor" => ["course" => "PHP"]],
// ];
// // var_dump($array);
// $course = Arr::pluck($array, "mentor.course"); 
// var_dump($course); // Получит массив из вложенных элементов ["HTML", "JavaScript", "PHP"]

// echo Arr::query($array); // Вернёт строку запроса преобразованную через htmlspecialchars состоящую из элементов массива (любой вложенности) - 0%5Bmentor%5D%5Bcourse%5D=HTML&1%5Bmentor%5D%5Bcourse%5D=JavaScript&2%5Bmentor%5D%5Bcourse%5D=PHP


//// Использование фичи компонента "Kint" - деббагинг
// d($_SESSION);


//// Вывод Flash сообщений на экран пользователю
// flash()->message("Hot!");
// flash()->success("Success!");
// flash()->warning("Warning!!!");
// flash()->error("Error message!");
// echo flash()->display("info"); 
// echo flash()->display();


//// Старый пример простейшего роутинга
// if($_SERVER["REQUEST_URI"] == "/home") {
//     require "../app/controllers/homepage.php";
// }

// exit();
