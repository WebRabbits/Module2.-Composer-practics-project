<?php

use Aura\SqlQuery\QueryFactory;
use Connection\Connection;
use Delight\Auth\Auth;
use DI\ContainerBuilder;
use League\Plates\Engine;
use Psr\Container\ContainerInterface;

$builder = new ContainerBuilder();


$builder->addDefinitions([
    PDO::class => function () {
        return Connection::Connect();
    },
    
    Engine::class => function() {
        return new Engine("../app/views");
    },

    Auth::class => function(ContainerInterface $container) {
        return new Auth($container->get(PDO::class), null, null, false);
    },

    QueryFactory::class => function () {
        return new QueryFactory("mysql");
    }
]);

$container = $builder->build();
// d($container);
