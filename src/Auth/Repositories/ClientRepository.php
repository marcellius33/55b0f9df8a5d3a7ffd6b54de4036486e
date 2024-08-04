<?php

namespace App\Auth\Repositories;

use App\Auth\Entities\ClientEntity;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;

class ClientRepository implements ClientRepositoryInterface
{
    private const CLIENT_NAME = 'email_api';
    private const REDIRECT_URI = 'http://localhost:8080';


    public function getClientEntity(string $clientIdentifier): ?ClientEntityInterface
    {
        $client = new ClientEntity();
        $client->setIdentifier($clientIdentifier);
        $client->setName(self::CLIENT_NAME);
        $client->setRedirectUri(self::REDIRECT_URI);
        $client->setConfidential();

        return $client;
    }

    public function validateClient(string $clientIdentifier, ?string $clientSecret, ?string $grantType): bool
    {
        $clients = [
            'email_api' => [
                'secret'          => 'email_api',
                'name'            => self::CLIENT_NAME,
                'redirect_uri'    => self::REDIRECT_URI,
                'is_confidential' => true,
            ],
        ];

        if (array_key_exists($clientIdentifier, $clients) === false) {
            return false;
        }

        if ($clients[$clientIdentifier]['secret'] !== $clientSecret) {
            return false;
        }

        return true;
    }
}
