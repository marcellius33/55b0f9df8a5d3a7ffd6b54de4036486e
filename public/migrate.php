<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Migration;

// Load .env file
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$migration = new Migration();

$migrationFiles = [
    'src/Migrations/2024_08_03_create_sent_emails_table.sql',
];

foreach ($migrationFiles as $file) {
    $migration->run($file);
}
