<?php

use App\Core\Database;

require_once __DIR__ . "/../app/Core/Database.php";
echo "migrating database...\n";

$pdo = Database::connect();

try {
    Database::beginTransaction();

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT  CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS user_profile (
                id INT PRIMARY KEY,
                fullname VARCHAR(50) NOT NULL,
                whatsapp VARCHAR(15) NOT NULL,
                avatar VARCHAR(255),
                gender TINYINT(1) NOT NULL DEFAULT 0,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT  CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE
            )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS sessions (
                id VARCHAR(255) PRIMARY KEY,
                user_id INT NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS todos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                deadline TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                user_id INT NOT NULL,
                status VARCHAR(10) NOT NULL DEFAULT 'delayed',
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT  CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS tags (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(100) NOT NULL,
                description TEXT,
                user_id INT NOT NULL,
                -- color VARCHAR(7) NOT NULL DEFAULT '#000000',
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT  CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS todo_tags (
                tag_id INT NOT NULL,
                todo_id INT NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT  CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (todo_id, tag_id),
                FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE,
                FOREIGN KEY (todo_id) REFERENCES todos(id) ON DELETE CASCADE
            )");

    Database::commitTransaction();
} catch (\Exception $exception) {
    Database::rollbackTransaction();
    echo "Error migrating database: " . $exception->getMessage() . "\n";
    throw $exception;
}
