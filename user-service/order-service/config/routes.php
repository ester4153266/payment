<?php
// routes.php

/**
 * API Routes Configuration
 *
 * Define RESTful API endpoints here.
 */

use Slim\App;
use App\Controllers\OrderController;

return function (App $app) {
    $app->post('/orders', OrderController::class . ':createOrder');// Create a new order
    $app->put('/orders/{id}', OrderController::class . ':updateOrder');// Update an order
    $app->delete('/orders/{id}', OrderController::class . ':cancelOrder');// Cancel an order
};
?>
