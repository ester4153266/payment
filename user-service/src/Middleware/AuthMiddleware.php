<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Services\TokenService;

/**
 * AuthMiddleware class handles authentication for protected routes.
 */
class AuthMiddleware {
    protected $tokenService;

    /**
     * Constructor for AuthMiddleware.
     *
     * @param TokenService $tokenService Service for handling authentication tokens.
     */
    public function __construct(TokenService $tokenService) {
        $this->tokenService = $tokenService;
    }

    /**
     * Middleware handler to verify authentication token.
     *
     * @param Request $request HTTP request object.
     * @param RequestHandler $handler Request handler.
     * @return Response HTTP response object.
     */
    public function __invoke(Request $request, RequestHandler $handler): Response {
        $token = $request->getHeaderLine('Authorization');

        if (!$token || !$this->tokenService->validateToken($token)) {
            return new Response(401); // Unauthorized
        }

        return $handler->handle($request);
    }
}
