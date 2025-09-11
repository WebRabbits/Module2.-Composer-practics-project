<?php

use App\QueryBuilder;
use Connection\Connection; // Подключение к БД

$db = new QueryBuilder(Connection::Connect());

//// Получение всех записей из БД
$posts = $db->getAll("posts");
var_dump($posts);

//// Получение данных из БД по условию
$post = $db->get("posts", "LIKE", ["title" => '%пост%']);
var_dump($post);

// var_dump($db->findOne());

//// Добавление массива данных в БД
// $db->insert("posts", [
//     "title" => "Any post 1223",
//     "date" => date("Y-m-d H:i:s")
// ]);

//// Получить ID последней добавленной записи в БД. ТОЛЬКО после самого добавления
// $lastInsertId = $db->getLastInsertId();
// var_dump($lastInsertId);

//// Обновить данные по 
// $db->update("posts", [
//     "title" => "Изменил пост с id=31"
// ],31);

//// Удаление записи из БД по переданному ID
// $db->delete("posts", 32);
