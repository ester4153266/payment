<?php

namespace App\Services;

use App\Models\User;

/**
 * UserService class manages user-related operations.
 */
class UserService {
    protected $users = [];

    /**
     * Create a new user.
     *
     * @param array $data Data for creating a new user.
     * @return User The created user.
     */
    public function createUser($data) {
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->db->prepare($sql);

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->execute();

        $data['id'] = $this->db->lastInsertId();
        return new User($data['id'], $data['username'], $data['email']);
    }


    /**
     * Get a user by ID.
     *
     * @param int $id User ID.
     * @return User|null The user if found, null otherwise.
     */
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch();

        if ($row) {
            return new User($row['id'], $row['username'], $row['email']);
        }
        return null;
    }

    /**
     * Validate user credentials.
     *
     * @param string $username Username.
     * @param string $password Password.
     * @return User|null The user if credentials are valid, null otherwise.
     */
    public function validateUser($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch();

        if ($row && password_verify($password, $row['password'])) {
            return new User($row['id'], $row['username'], $row['email']);
        }
        return null;
    }

    /**
     * Get the handler for User Created event.
     *
     * @return UserCreatedEventHandler The event handler.
     */
    public function getUserCreatedEventHandler() {
        return new \App\Events\UserCreatedEventHandler(new \App\Services\NotificationService());
    }
}
