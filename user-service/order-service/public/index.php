<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

$container->set('db', function () {
    $settings = require __DIR__ . '/../config/database.php';
    $dsn = "mysql:host={$settings['host']};dbname={$settings['db']};charset={$settings['charset']}";
    return new PDO($dsn, $settings['user'], $settings['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
});

$container->set('orderService', function ($container) {
    return new App\Services\OrderService($container->get('db'));
});

$container->set('orderPlacedEventHandler', function () {
    return new App\Events\OrderPlacedEventHandler();
});

$container->set('orderCancelledEventHandler', function () {
    return new App\Events\OrderCancelledEventHandler();
});

(require __DIR__ . '/../config/routes.php')($app);

$app->run();

