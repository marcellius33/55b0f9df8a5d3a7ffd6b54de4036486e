<?php

use App\Worker;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Load .env file
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

if ($argc < 2) {
    echo "Usage: php run_worker.php <queue_name>\n";
    exit(1);
}

$queueName = $argv[1];

$worker = new Worker($queueName);
$worker->processMessages();
