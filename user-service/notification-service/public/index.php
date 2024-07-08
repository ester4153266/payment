<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();


$container->set('notificationService', function () {
    return new App\Services\NotificationService();
});

(require __DIR__ . '/../config/routes.php')($app);

$app->run();
