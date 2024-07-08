<?php
// Order.php

namespace App\Models;

/**
 * Order Model
 *
 * Represents an order entity with its properties.
 */
class Order {

    public $id;
    public $user_id;
    public $details;

    // Add more properties as needed

    /**
     * Constructor
     *
     * @param int $id Order ID
     * @param int $user_id User ID who placed the order
     * @param array $details List of items in the order
     */
    public function __construct($id, $user_id, $details) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->details = $details;
    }
}
?>
