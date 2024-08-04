<?php

namespace App;

use App\Services\EmailService;
use Predis\Client;

class Worker
{
    private $redis;
    private $queueName;
    private $emailService;

    public function __construct(string $queueName, string $host = 'queue', int $port = 6379)
    {
        $this->queueName = $queueName;
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host' => $host,
            'port' => $port,
        ]);
        $this->emailService = new EmailService();
    }

    public function processMessages(): void
    {
        echo " [Worker] Waiting for messages. To exit press CTRL+C\n";

        while (true) {
            // Block until a message is available
            $message = $this->redis->blpop($this->queueName, 0);

            if ($message) {
                // Extract the message body
                $task = json_decode($message[1], true);

                echo " [Worker] Received: ", print_r($task, true), "\n";

                // Process the email
                $this->emailService->sendEmail($task['to'], $task['subject'], $task['body']);

                sleep(1);

                echo " [Worker] Done\n";
            }
        }
    }
}
