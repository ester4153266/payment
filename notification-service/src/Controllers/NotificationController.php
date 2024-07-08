<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\NotificationService;

/**
 * NotificationController class handles the notification-related actions.
 */
class NotificationController {
    protected $notificationService;

    /**
     * Constructor for NotificationController.
     *
     * @param NotificationService $notificationService Service for sending notifications.
     */
    public function __construct(NotificationService $notificationService) {
        $this->notificationService = $notificationService;
    }

    /**
     * Send a notification.
     *
     * @param Request $request HTTP request object.
     * @param Response $response HTTP response object.
     * @param array $args Arguments from the request.
     * @return Response JSON response with the notification status.
     */
    public function sendNotification(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();
        $result = $this->notificationService.send($data['message'], $data['recipient']);

        return $response->withJson(['status' => $result ? 'Notification sent' : 'Failed to send notification']);
    }
}
