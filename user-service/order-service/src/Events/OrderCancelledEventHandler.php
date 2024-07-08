<?php

namespace App\Events;

/**
 * OrderCancelledEventHandler class handles the Order Cancelled event.
 */
class OrderCancelledEventHandler {
    /**
     * Handle the Order Cancelled event.
     *
     * @param array $eventData Data related to the Order Cancelled event.
     */
    public function handle(array $eventData) {
        $orderId = $eventData['id'];
        $userId = $eventData['user_id'];

        // Perform actions based on the event, such as restocking items or notifying the user
    }
}
