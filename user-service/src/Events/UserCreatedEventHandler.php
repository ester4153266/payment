<?php

namespace App\Events;

use App\Services\NotificationService;

/**
 * UserCreatedEventHandler class handles the User Created event.
 */
class UserCreatedEventHandler {
    protected $notificationService;

    /**
     * Constructor for UserCreatedEventHandler.
     *
     * @param NotificationService $notificationService Service for sending notifications.
     */
    public function __construct(NotificationService $notificationService) {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the User Created event.
     *
     * @param array $eventData Data related to the User Created event.
     */
    public function handle(array $eventData) {
        $username = $eventData['username'];
        $email = $eventData['email'];

        // Send welcome email to the new user
        $this->notificationService->sendWelcomeEmail($username, $email);
    }
}
