<?php

namespace App\Models;

require_once __DIR__ . "/../core/BaseModel.php";
// require_once __DIR__ . "/../../database/db_test_class.php";


use DateTime;
use Database;
use PDO;
use PDOException;
use App\Models\BaseModel;

class TodoModel extends BaseModel
{
    protected $todos;
    protected $db;

    private string $table;
    public function __construct()
    {
        parent::__construct(); // initialize parent

        $this->table = "todos";
        $this->todos = [
            ['id' => 1, "title" => "Todo A", 'description' => "Description todo A", "deadline" => new DateTime()->getTimestamp(), 'status' => "ongoing"],
            ['id' => 2, "title" => "Todo B", 'description' => "Description todo B", "deadline" => new DateTime()->getTimestamp(), 'status' => "delayed"],
            ['id' => 3, "title" => "Todo C", 'description' => "Description todo C", "deadline" => new DateTime()->getTimestamp(), 'status' => "ongoing"],
            ['id' => 4, "title" => "Todo D", 'description' => "Description todo D", "deadline" => new DateTime()->getTimestamp(), 'status' => "done"],
            ['id' => 5, "title" => "Todo E", 'description' => "Description todo E", "deadline" => new DateTime()->getTimestamp(), 'status' => "delayed"],
            ['id' => 6, "title" => "Todo F", 'description' => "Description todo F", "deadline" => new DateTime()->getTimestamp(), 'status' => "ongoing"],
            ['id' => 7, "title" => "Todo G", 'description' => "Description todo G", "deadline" => new DateTime()->getTimestamp(), 'status' => "done"],
            ['id' => 8, "title" => "Todo H", 'description' => "Description todo H", "deadline" => new DateTime()->getTimestamp(), 'status' => "delayed"],
            ['id' => 9, "title" => "Todo I", 'description' => "Description todo I", "deadline" => new DateTime()->getTimestamp(), 'status' => "ongoing"],
            ['id' => 10, "title" => "Todo J", 'description' => "Description todo J", "deadline" => new DateTime()->getTimestamp(), 'status' => "delayed"],
            ['id' => 11, "title" => "Todo K", 'description' => "Description todo K", "deadline" => new DateTime()->getTimestamp(), 'status' => "done"],
            ['id' => 12, "title" => "Todo L", 'description' => "Description todo L", "deadline" => new DateTime()->getTimestamp(), 'status' => "ongoing"],
            ['id' => 13, "title" => "Todo M", 'description' => "Description todo M", "deadline" => new DateTime()->getTimestamp(), 'status' => "done"],
            ['id' => 14, "title" => "Todo N", 'description' => "Description todo N", "deadline" => new DateTime()->getTimestamp(), 'status' => "delayed"],
        ];
        try {
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS " . $this->table . " (
                                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                                            title TEXT NOT NULL,
                                            description TEXT,
                                            deadline INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
                                            status TEXT NOT NULL DEFAULT delayed
                                        )");


        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }

    }
    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM " . $this->table);
        $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $todos;
    }

    public function createTodo($title, $description, $status, $deadline)
    {
        try {
            $stmn = $this->pdo->prepare("INSERT INTO todos (title,description,deadline,status) VALUES (:title,:description,:deadline,:status)");
            $stmn->execute([
                ':title' => $title,
                ':description' => $description,
                ':deadline' => $deadline,
                ':status' => $status
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }
    public function getTodo($id)
    {
        try {


            $stmn = $this->pdo->prepare("SELECT * FROM todos WHERE id = :id LIMIT 1");
            $stmn->execute([
                ':id' => $id,
            ]);
            return $stmn->fetchAll(PDO::FETCH_ASSOC)[0];
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }
    public function deleteTodo($id)
    {
        try {
            $stmn = $this->pdo->prepare("DELETE FROM todos WHERE id = :id");
            $stmn->execute([
                ':id' => $id,
            ]);
            return $stmn;
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }

    public function updateTodo($id, $title, $description, $status, $deadline)
    {
        try {
            $stmn = $this->pdo->prepare("UPDATE todos SET title = :title, description = :description, status = :status, deadline = :deadline WHERE id = :id");
            $stmn->execute([
                ':id' => $id,
                ':title' => $title,
                ':description' => $description,
                ':status' => $status,
                ':deadline' => $deadline
            ]);
            return $stmn;
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }

}