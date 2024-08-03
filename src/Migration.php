<?php

namespace App;

use PDO;

class Migration
{
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getPdo();
    }

    public function run($migrationFile)
    {
        if (file_exists($migrationFile)) {
            $sql = file_get_contents($migrationFile);
            $this->pdo->exec($sql);
            echo "Migration executed: " . $migrationFile . "\n";
        } else {
            echo "Migration file not found: " . $migrationFile . "\n";
        }
    }
}
