<?php
// AuthController.php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Services\UserService;
use App\Services\TokenService;

/**
 * Auth Controller
 *
 * Handle authentication-related HTTP requests.
 */
class AuthController {

    protected $userService;
    protected $tokenService;

    public function __construct(UserService $userService, TokenService $tokenService) {
        $this->userService = $userService;
        $this->tokenService = $tokenService;
    }

    /**
     * User login
     *
     * @param Request $request HTTP request object
     * @param Response $response HTTP response object
     * @return Response JSON response with JWT token
     */
    public function login(Request $request, Response $response) {
        $data = $request->getParsedBody();

        // Input validation
        if (empty($data['username']) || empty($data['password'])) {
            return $response->withJson(['error' => 'Username and password are required'], 400);
        }

        // Validate user credentials
        $user = $this->userService->validateUser($data['username'], $data['password']);

        if ($user) {
            // Generate JWT token
            $token = $this->tokenService->createToken($user->id);
            return $response->withJson(['token' => $token]);
        } else {
            return $response->withJson(['error' => 'Invalid credentials'], 401);
        }
    }
}
?>
