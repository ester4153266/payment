<?php
// TokenService.php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


/**
 * Token Service
 *
 * Generates and validates JWT tokens.
 */
class TokenService {

    private $secretKey;

    public function __construct() {
        $this->secretKey = getenv('SECRET_KEY');
    }

    /**
     * Generate JWT token
     *
     * @param int $userId User id
     * @return string JWT token
     */
    public function createToken($userId) {
        $payload = [
            'iss' => "yourdomain.com",
            'aud' => "yourdomain.com",
            'iat' => time(),
            'exp' => time() + 3600,
            'userId' => $userId
        ];

        return JWT::encode($payload, $this->secretKey);
    }

    /**
     * Validate JWT token
     *
     * @param string $token JWT token
     * @return mixed Decoded token or false if invalid
     */
    public function validateToken($token) {
        try {
            return JWT::decode($token, new Key($this->secretKey, 'HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }
}
?>
