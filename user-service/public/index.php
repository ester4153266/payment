<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

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

$container->set('tokenService', function () {
    return new App\Services\TokenService();
});

$container->set('userService', function ($c) {
    return new App\Services\UserService($c->get('db'));
});

$container->set('notificationService', function () {
    return new App\Services\NotificationService();
});

$container->set('userCreatedEventHandler', function () {
    return new App\Events\UserCreatedEventHandler(new App\Services\NotificationService());
});

(require __DIR__ . '/../config/routes.php')($app);

$app->run();
