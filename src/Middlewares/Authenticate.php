<?php

namespace App\Middlewares;

use App\Auth\Repositories\AccessTokenRepository;
use Exception;
use GuzzleHttp\Psr7\Response as Psr7Response;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Authenticate
{
    public function __invoke(Request $request, $handler): Response
    {
        $publicKey = new CryptKey(__DIR__ . '/../../public.key');
        $resourceServer = new ResourceServer(
            new AccessTokenRepository(),
            $publicKey,
        );

        try {
            $request = $resourceServer->validateAuthenticatedRequest($request);

            return $handler->handle($request);
        } catch (Exception $exception) {
            $response = new Psr7Response();
            $response->getBody()->write(json_encode([
                'error' => 'Unauthorized',
                'message' => $exception->getMessage(),
            ]));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }
    }
}
