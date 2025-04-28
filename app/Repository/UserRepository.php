<?php

namespace App\Repository;

use App\Domain\User;
use BcMath\Number;
use App\Core\Database;
use PDOException;

class UserRepository
{
    private \PDO $pdo;// Assuming you have a Database class that handles the connection
    protected string $table;

    public function __construct()
    {
        try {
            $this->pdo = Database::connect();

            $this->table = 'users';
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS " . $this->table . " (
                                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                                            name TEXT NOT NULL,
                                            email TEXT NOT NULL UNIQUE,
                                            password TEXT NOT NULL,
                                            created_at INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
                                            updated_at INTEGER NOT NULL DEFAULT (strftime('%s', 'now'))
                                        )");
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }



    public function save(User $user): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user->name, $user->email, $user->password, $user->created_at, $user->updated_at]);
        return $this->pdo->lastInsertId();

    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $record = $stmt->fetch(\PDO::FETCH_ASSOC);


        if ($record) {
            $user = new User();
            $user->id = $record['id'];
            $user->name = $record['name'];
            $user->email = $record['email'];
            $user->password = $record['password'];
            $user->created_at = $record['created_at'];
            $user->updated_at = $record['updated_at'];
            return $user;
        }

        return null;
    }
    public function findById(string $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $record = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($record) {
            $user = new User();
            $user->id = $record['id'];
            $user->name = $record['name'];
            $user->email = $record['email'];
            $user->password = $record['password'];
            $user->created_at = $record['created_at'];
            $user->updated_at = $record['updated_at'];
            return $user;
        }

        return null;
    }

    public function update(User $user): bool
    {
        $stmt = $this->pdo->prepare("UPDATE users SET name = ?, password = ?, updated_at = ? WHERE id = ?");
        $stmt->execute([$user->name, $user->password, $user->updated_at, $user->id]);
        return $stmt->rowCount() > 0;
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

}