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
        // var_dump($todo->deadline);
        $stmt = $this->pdo->prepare("INSERT INTO todos (title, description, deadline, user_id, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $todo->title,
            $todo->description,
            date('Y-m-d H:i:s', $todo->deadline),
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
            $todo = new Todo();
            $todo->id = $record['id'];
            $todo->title = $record['title'];
            $todo->description = $record['description'];
            $todo->user_id = $record['user_id'];
            $todo->status = $record['status'];
            $todo->deadline = strtotime($record['deadline']);
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

        // $stmt = $this->pdo->prepare("SELECT * FROM todos LEFT JOIN todo_tags ON todos.id = todo_tags.todo_id WHERE user_id = ?");
        // $stmt->execute([
        //     SessionService::$user_id
        // ]);


        // Step 1: Get all todos
        $stmt = $this->pdo->prepare("SELECT * FROM todos WHERE user_id = ?");
        $stmt->execute([SessionService::$user_id]);
        $todos = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Step 2: Get all tags for those todos
        $todo_ids = array_column($todos, 'id');

        // echo "<pre>";
        // var_dump($todos);
        // echo "</pre>";
        if (empty($todo_ids)) {
            // No todos, so no tags either — just return empty result
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($todo_ids), '?'));

        $stmt = $this->pdo->prepare("
            SELECT tags.*, todo_tags.todo_id 
            FROM tags
            JOIN todo_tags ON tags.id = todo_tags.tag_id
            WHERE todo_tags.todo_id IN ($placeholders)
        ");
        $stmt->execute($todo_ids);
        $tag_rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Step 3: Group tags by todo_id
        $tags_by_todo_id = [];
        foreach ($tag_rows as $row) {
            $tags_by_todo_id[$row['todo_id']][] = $row;
        }

        // Step 4: Attach tags to todos
        foreach ($todos as &$todo) {
            $todo['tags'] = $tags_by_todo_id[$todo['id']] ?? [];
            $todo['deadline'] = strtotime($todo['deadline']);
        }


        // $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // $todos = [];
        // echo "<pre>";
        // var_dump($tags_by_todo_id);
        // echo "</pre>";
        // foreach ($records as $record) {
        //     $todo = new Todo();
        //     $todo->id = $record['id'];
        //     $todo->title = $record['title'];
        //     $todo->description = $record['description'];
        //     $todo->tags = $record['tags'] ?? []; // Assuming tags are fetched in the same query
        //     $todo->user_id = $record['user_id'];
        //     $todo->status = $record['status'];
        //     $todo->deadline = $record['deadline'];
        //     $todos[] = $todo;
        // }

        return $todos;
    }


    /**
     * @return Todo[]
     */
    public function findAllWithFilter(array $tags, array $status): array
    {
        $user_id = SessionService::$user_id;

        // 1. Find todo IDs that match all required tags
        if (!empty($tags)) {
            $tag_placeholders = implode(',', array_fill(0, count($tags), '?'));
            $query = "
            SELECT tt.todo_id
            FROM todo_tags tt
            JOIN todos t ON t.id = tt.todo_id
            WHERE t.user_id = ?
              AND tt.tag_id IN ($tag_placeholders)
        ";
            $params = array_merge([$user_id], $tags);

            if (!empty($status)) {
                $status_placeholders = implode(',', array_fill(0, count($status), '?'));
                $query .= " AND t.status IN ($status_placeholders)";
                $params = array_merge($params, $status);
            }

            $query .= "
            GROUP BY tt.todo_id
            HAVING COUNT(DISTINCT tt.tag_id) = " . count($tags);

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $todo_ids = array_column($stmt->fetchAll(\PDO::FETCH_ASSOC), 'todo_id');

            if (empty($todo_ids)) {
                return [];
            }
        } else {
            // No tag filter: just fetch todos based on user and status
            $query = "SELECT id FROM todos WHERE user_id = ?";
            $params = [$user_id];

            if (!empty($status)) {
                $status_placeholders = implode(',', array_fill(0, count($status), '?'));
                $query .= " AND status IN ($status_placeholders)";
                $params = array_merge($params, $status);
            }

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $todo_ids = array_column($stmt->fetchAll(\PDO::FETCH_ASSOC), 'id');

            if (empty($todo_ids)) {
                return [];
            }
        }

        // 2. Get full todo data and all their tags
        $todo_placeholders = implode(',', array_fill(0, count($todo_ids), '?'));
        $query = "
        SELECT 
            t.id AS todo_id,
            t.title AS todo_title,
            t.description AS todo_description,
            t.deadline,
            t.user_id,
            t.status,
            t.created_at AS todo_created_at,
            t.updated_at AS todo_updated_at,
            tg.id AS tag_id,
            tg.title AS tag_title,
            tg.description AS tag_description,
            tg.created_at AS tag_created_at,
            tg.updated_at AS tag_updated_at
        FROM todos t
        LEFT JOIN todo_tags tt ON tt.todo_id = t.id
        LEFT JOIN tags tg ON tg.id = tt.tag_id
        WHERE t.id IN ($todo_placeholders)
        ORDER BY t.id DESC
    ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($todo_ids);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // 3. Group by todo_id
        $todos = [];
        foreach ($rows as $row) {
            $id = $row['todo_id'];
            if (!isset($todos[$id])) {
                $todos[$id] = [
                    'id' => $row['todo_id'],
                    'title' => $row['todo_title'],
                    'description' => $row['todo_description'],
                    'deadline' => strtotime($row['deadline']),
                    'user_id' => $row['user_id'],
                    'status' => $row['status'],
                    'created_at' => $row['todo_created_at'],
                    'updated_at' => $row['todo_updated_at'],
                    'tags' => [],
                ];
            }

            if ($row['tag_id']) {
                $todos[$id]['tags'][] = [
                    'id' => $row['tag_id'],
                    'title' => $row['tag_title'],
                    'description' => $row['tag_description'],
                    'created_at' => $row['tag_created_at'],
                    'updated_at' => $row['tag_updated_at'],
                ];
            }
        }

        return array_values($todos);
    }

    public function update(Todo $todo): bool
    {
        $stmt = $this->pdo->prepare("UPDATE todos SET title = ?, description = ?, status = ?, deadline = ? WHERE id = ?");
        return $stmt->execute([
            $todo->title,
            $todo->description,
            $todo->status,
            date('Y-m-d H:i:s', $todo->deadline),
            $todo->id,
        ]);
    }

    public function delete($id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM todos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
