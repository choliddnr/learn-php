<?php

namespace App\Repository;

use App\Core\Database;
use App\Domain\Todo;
use App\Model\TodoCreateRequest;
use App\Model\TodoUpdateRequest;
use App\Service\SessionService;

class TodoRepository
{
    private \PDO $pdo;


    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function save(Todo $todo): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO todos (title, description, deadline, user_id, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $todo->title,
            $todo->description,
            $todo->deadline,
            $todo->user_id,
            $todo->status,
        ]);

        return $this->pdo->lastInsertId();
    }

    public function findById($id): Todo
    {
        $stmt = $this->pdo->prepare("SELECT * FROM todos WHERE id = ?");
        $stmt->execute([$id]);
        $record = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($record) {
            $todo = new TOdo();
            $todo->id = $record['id'];
            $todo->title = $record['title'];
            $todo->description = $record['description'];
            $todo->user_id = $record['user_id'];
            $todo->status = $record['status'];
            $todo->deadline = $record['deadline'];
            return $todo;
        }

        throw new \Exception("Todo not found.");
    }

    /**
     * @return Todo[]
     */
    public function findAll(): array
    {
        // $stmt = $this->pdo->prepare("SELECT * FROM todos");
        // $stmt->execute();

        $stmt = $this->pdo->prepare("SELECT * FROM todos WHERE user_id = ?");
        $stmt->execute([
            SessionService::$user_id
        ]);


        $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $todos = [];

        foreach ($records as $record) {
            $todo = new Todo();
            $todo->id = $record['id'];
            $todo->title = $record['title'];
            $todo->description = $record['description'];
            $todo->user_id = $record['user_id'];
            $todo->status = $record['status'];
            $todo->deadline = $record['deadline'];
            $todos[] = $todo;
        }

        return $todos;
    }

    public function update(TodoUpdateRequest $todo): bool
    {
        $stmt = $this->pdo->prepare("UPDATE todos SET title = ?, description = ?, status = ?, deadline = ? WHERE id = ?");
        return $stmt->execute([
            $todo->title,
            $todo->description,
            $todo->status,
            $todo->deadline,
            $todo->id,
        ]);
    }

    public function delete($id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM todos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}