<?php

namespace App\Auth;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use App\Auth\Repositories\AccessTokenRepository;
use App\Auth\Repositories\ClientRepository;
use App\Auth\Repositories\ScopeRepository;
use DateInterval;

class OAuth2Server
{
    private $server;

    public function __construct()
    {
        $clientRepository = new ClientRepository();
        $accessTokenRepository = new AccessTokenRepository();
        $scopeRepository = new ScopeRepository();

        $privateKeyPath = __DIR__ . '/../../private.key';
        $encryptionKey = $_ENV['ENCRYPTION_KEY'];

        $this->server = new AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            new CryptKey($privateKeyPath, null, false),
            $encryptionKey
        );

        // Enable the client credentials grant
        $this->server->enableGrantType(
            new ClientCredentialsGrant(),
            new DateInterval('PT1H') // Access tokens will expire after 1 hour
        );
    }

    public function getServer()
    {
        return $this->server;
    }
}
