<?php


namespace App\Services;

use PDO;
use App\Models\Order;

/**
 * OrderService class manages order-related operations.
 */
class OrderService {
    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Create a new order.
     *
     * @param array $data Data for creating a new order.
     * @return Order The created order.
     */
    public function createOrder($data) {
        $stmt = $this->db->prepare("INSERT INTO orders (user_id, details) VALUES (:user_id, :details)");
        $stmt->execute([
            'user_id' => $data['user_id'],
            'details' => json_encode($data['details']),
        ]);
        $id = $this->db->lastInsertId();
        return new Order($id, $data['user_id'], $data['details']);
    }

    /**
     * Update an existing order.
     *
     * @param int $id Order ID.
     * @param array $data Data for updating the order.
     * @return Order|null The updated order if found, null otherwise.
     */
    public function updateOrder($id, $data) {
        $stmt = $this->db->prepare("UPDATE orders SET details = :details WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'details' => json_encode($data['details']),
        ]);
        if ($stmt->rowCount() > 0) {
            return new Order($id, $data['user_id'], $data['details']);
        }
        return null;
    }

    /**
     * Cancel an existing order.
     *
     * @param int $id Order ID.
     * @return Order|null The cancelled order if found, null otherwise.
     */
    public function cancelOrder($id) {
        $stmt = $this->db->prepare("UPDATE orders SET status = 'cancelled' WHERE id = :id");
        $stmt->execute(['id' => $id]);
        if ($stmt->rowCount() > 0) {
            $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $order = $stmt->fetch();
            return new Order($order['id'], $order['user_id'], json_decode($order['details'], true));
        }
        return null;
    }

    /**
     * Get the handler for Order Placed event.
     *
     * @return OrderPlacedEventHandler The event handler.
     */
    public function getOrderPlacedEventHandler() {
        return new \App\Events\OrderPlacedEventHandler();
    }

    /**
     * Get the handler for Order Cancelled event.
     *
     * @return OrderCancelledEventHandler The event handler.
     */
    public function getOrderCancelledEventHandler() {
        return new \App\Events\OrderCancelledEventHandler();
    }
}
