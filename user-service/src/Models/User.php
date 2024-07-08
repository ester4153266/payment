<?php
// User.php

namespace App\Models;

/**
 * User Model
 *
 * Represents a user entity with its properties.
 */
class User {

    public $id;

    public $username;
    public $email;

    /**
     * Constructor
     *
     * @param int $id User ID
     * @param string $username Username
     * @param string $email Email address
     */
    public function __construct($id, $username, $email) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
    }
}
?>
