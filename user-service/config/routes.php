<?php
// routes.php

/**
 * API Routes Configuration
 *
 * Define RESTful API endpoints here.
 */

use Slim\App;
use App\Controllers\UserController;
use App\Controllers\AuthController;
use App\Middleware\AuthMiddleware;
return function (App $app) {
    $app->post('/users', UserController::class . ':createUser');// Create a new user
    $app->get('/users/{id}', UserController::class . ':getUserById')->add(new AuthMiddleware($app->getContainer()->get('tokenService'))); // Get user details
    $app->post('/auth/login', AuthController::class . ':login'); // User login
};
?>
