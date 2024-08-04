<?php

namespace App\Controllers;

use App\Auth\OAuth2Server;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    public function getAccessToken(Request $request, Response $response)
    {
        $oauth2server = new OAuth2Server();
        $server = $oauth2server->getServer();

        try {
            return $server->respondToAccessTokenRequest($request, $response);
        } catch (Exception $exception) {
            $response->getBody()->write(json_encode([
                'error' => 'An unexpected error occurred',
                'message' => $exception->getMessage(),
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}
