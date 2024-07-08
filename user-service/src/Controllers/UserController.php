<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\UserService;

/**
 * UserController class handles the user-related actions.
 */
class UserController {
    protected $userService;

    /**
     * Constructor for UserController.
     *
     * @param UserService $userService Service for managing users.
     */
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * Create a new user.
     *
     * @param Request $request HTTP request object.
     * @param Response $response HTTP response object.
     * @param array $args Arguments from the request.
     * @return Response JSON response with the created user.
     */
    public function createUser(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();

        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            return $response->withJson(['error' => 'Username, email, and password are required'], 400);
        }

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        $user = $this->userService->createUser($data);

        $eventHandler = $this->userService->getUserCreatedEventHandler();
        $eventHandler->handle(['id' => $user->id, 'username' => $user->username, 'email' => $user->email]);

        return $response->withJson($user, 201);
    }


    /**
     * Get a user by ID.
     *
     * @param Request $request HTTP request object.
     * @param Response $response HTTP response object.
     * @param array $args Arguments from the request.
     * @return Response JSON response with the user details.
     */
    public function getUserById(Request $request, Response $response, array $args): Response {
        $id = (int) $args['id'];
        if ($id <= 0) {
            return $response->withJson(['error' => 'Invalid user ID'], 400);
        }

        $user = $this->userService->getUserById($id);

        if ($user) {
            return $response->withJson($user);
        }

        return $response->withJson(['error' => 'User not found'], 404);
    }
}
