<?php

namespace App\Services;

/**
 * NotificationService class manages notification-related operations.
 */
class NotificationService {
    /**
     * Send a notification.
     *
     * @param string $message Message content.
     * @param string $recipient Recipient of the message.
     * @return bool True if the notification is sent, false otherwise.
     */
    public function send($message, $recipient) {
        // Implement logic to send notification (e.g., email, SMS)
        // For the sake of this example, we'll assume the notification is always sent successfully
        return true;
    }

    /**
     * Send a welcome email to the user.
     *
     * @param string $username Username of the new user.
     * @param string $email Email of the new user.
     */
    public function sendWelcomeEmail($username, $email) {
        $message = "Welcome, $username!";
        $this->send($message, $email);
    }
}
