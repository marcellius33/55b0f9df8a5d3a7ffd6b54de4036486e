<?php

namespace App\Auth\Repositories;

use App\Auth\Entities\AccessTokenEntity;
use App\Database;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use PDO;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    protected $pdo;
    protected $table = 'access_tokens';

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getPdo();
    }

    public function getNewToken(
        ClientEntityInterface $clientEntity,
        array $scopes,
        string|null $userIdentifier = null
    ): AccessTokenEntityInterface {
        $accessToken = new AccessTokenEntity();

        $accessToken->setClient($clientEntity);

        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }

        if ($userIdentifier !== null) {
            $accessToken->setUserIdentifier((string) $userIdentifier);
        }

        return $accessToken;
    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        $sql = "INSERT INTO {$this->table} (id, client_id, scope, expires_at) VALUES (:id, :client_id, :scope, :expires_at)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $accessTokenEntity->getIdentifier(),
            'client_id' => $accessTokenEntity->getClient()->getIdentifier(),
            'scope' => implode(' ', $accessTokenEntity->getScopes()),
            'expires_at' => $accessTokenEntity->getExpiryDateTime()->format('Y-m-d H:i:s'),
        ]);
    }

    public function revokeAccessToken(string $tokenId): void
    {
        $sql = "DELETE FROM access_tokens WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $tokenId,
        ]);
    }

    public function isAccessTokenRevoked(string $tokenId): bool
    {
        $sql = "SELECT * FROM access_tokens WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $tokenId,
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data === null;
    }
}
