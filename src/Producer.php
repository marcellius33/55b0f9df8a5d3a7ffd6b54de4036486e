<?php

namespace App;

use Predis\Client;

class Producer
{
    private $redis;
    private $queueName;

    public function __construct(string $queueName, string $host = 'queue', int $port = 6379)
    {
        $this->queueName = $queueName;
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host' => $host,
            'port' => $port,
        ]);
    }

    public function pushEmailJob(array $data): void
    {
        $message = json_encode($data);
        $this->redis->rpush($this->queueName, $message);

        // echo "[Producer] Sent email job: $message\n";
    }
}
