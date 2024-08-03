<?php

namespace App\Models;

use App\Database;

class Email
{
    protected $pdo;
    protected $table = 'sent_emails';

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getPdo();
    }

    public function save($data)
    {
        $sql = "INSERT INTO {$this->table} (\"to\", subject, body) VALUES (:to, :subject, :body)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'to' => $data['to'],
            'subject' => $data['subject'],
            'body' => $data['body'],
        ]);
    }
}
