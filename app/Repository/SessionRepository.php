<?php

namespace App\Repository;


use App\Core\Database;
use App\Domain\Session;

class SessionRepository
{
    private \PDO $pdo;// Assuming you have a Database class that handles the connection
    protected string $table;

    public function __construct()
    {
        try {
            $this->pdo = Database::connect();

            $this->table = 'sessions';
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS " . $this->table . " (
                                            id TEXT PRIMARY KEY AUTOINCREMENT,
                                            user_id INTEGER NOT NULL,
                                            FOREIGN KEY (user_id) REFERENCES users(id)
                                        )");
        } catch (\PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }


    public function save(Session $session): Session
    {
        $stmt = $this->pdo->prepare("INSERT INTO sessions (id, user_id) VALUES (?, ?)");
        $stmt->execute([$session->id, $session->user_id]);
        // return $this->pdo->lastInsertId();
        return $session;
    }

    public function find($id): Session|null
    {
        $stmn = $this->pdo->prepare("SELECT * FROM sessions WHERE id = ?");
        $stmn->execute([$id]);
        $record = $stmn->fetch(\PDO::FETCH_ASSOC);
        if (!$record) {
            return null;
        }
        $session = new Session();
        $session->id = $record['id'];
        $session->user_id = $record['user_id'];
        return $session;
    }

    public function delete($id)
    {
        $stmn = $this->pdo->prepare("DELETE FROM sessions WHERE id = ?");
        $stmn->execute([$id]);

    }
}