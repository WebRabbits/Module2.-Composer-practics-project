<?php

namespace App\controllers;

use JasonGrimes\Paginator;

// Компонент пагинатор (обёртка над компонентом JasonGrimes\Paginator). Принимает в статичный метод только общее кол-во элементов из БД по конкретному переданному значений из таблицы для вывода пагинации. Значение страницы пагинации берётся из значения QUeryParams из GET запроса с ?page=N
class PaginationController
{
    public static function pagination(
        $totalItems,
        $itemsPerPage = 3,
        $urlPattern = "?page=(:num)"
        ) {

        $currentPage = $_GET["page"] ?? 1;

        return new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
    }
}
