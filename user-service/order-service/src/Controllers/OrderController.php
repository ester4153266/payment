<?php


namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\OrderService;

/**
 * OrderController class handles order-related actions.
 */
class OrderController {
    protected $orderService;

    /**
     * Constructor for OrderController.
     *
     * @param OrderService $orderService Service for managing orders.
     */
    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    /**
     * Create a new order.
     *
     * @param Request $request HTTP request object.
     * @param Response $response HTTP response object.
     * @param array $args Arguments from the request.
     * @return Response JSON response with the created order.
     */
    public function createOrder(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();
        $order = $this->orderService->createOrder($data);

        // Trigger Order Placed event
        $eventHandler = $this->orderService->getOrderPlacedEventHandler();
        $eventHandler->handle(['id' => $order->id, 'user_id' => $order->user_id, 'details' => $order->details]);

        return $response->withJson($order, 201);
    }

    /**
     * Update an existing order.
     *
     * @param Request $request HTTP request object.
     * @param Response $response HTTP response object.
     * @param array $args Arguments from the request.
     * @return Response JSON response with the updated order.
     */
    public function updateOrder(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();
        $order = $this->orderService->updateOrder($args['id'], $data);

        return $response->withJson($order);
    }

    /**
     * Cancel an existing order.
     *
     * @param Request $request HTTP request object.
     * @param Response $response HTTP response object.
     * @param array $args Arguments from the request.
     * @return Response JSON response with the cancellation status.
     */
    public function cancelOrder(Request $request, Response $response, array $args): Response {
        $order = $this->orderService->cancelOrder($args['id']);

        // Trigger Order Cancelled event
        $eventHandler = $this->orderService->getOrderCancelledEventHandler();
        $eventHandler->handle(['id' => $order->id, 'user_id' => $order->user_id]);

        return $response->withJson(['status' => 'Order cancelled']);
    }
}
