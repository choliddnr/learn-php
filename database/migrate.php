<?php

use App\Core\Database;

require_once __DIR__ . "/../app/Core/Database.php";
echo "migrating database...\n";

$pdo = Database::connect();

try {
    Database::beginTransaction();
    $pdo->exec("CREATE TABLE IF NOT EXISTS users(
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                email TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                created_at INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
                updated_at INTEGER NOT NULL DEFAULT (strftime('%s', 'now'))
            )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS user_profile(
                id INTEGER PRIMARY KEY,
                fullname TEXT NOT NULL ,
                whatsapp TEXT NOT NULL,
                avatar TEXT,
                gender BOOLEAN NOT NULL DEFAULT 0,
                created_at INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
                updated_at INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
                FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE
            )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS sessions(
                id TEXT PRIMARY KEY ,
                user_id INTEGER NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");

    $pdo->exec(" CREATE TABLE IF NOT EXISTS todos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                description TEXT,
                deadline INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
                user_id INTEGER NOT NULL,
                status TEXT NOT NULL DEFAULT delayed,
                created_at INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
                updated_at INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");

    $pdo->exec(" CREATE TABLE IF NOT EXISTS tags (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                description TEXT,
                user_id INTEGER NOT NULL,
                -- color TEXT NOT NULL DEFAULT '#000000',
                created_at INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
                updated_at INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");

    $pdo->exec(" CREATE TABLE IF NOT EXISTS todo_tags (
                tag_id INTEGER NOT NULL,
                todo_id INTEGER NOT NULL,
                created_at INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
                updated_at INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
                PRIMARY KEY (todo_id, tag_id),
                FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE,
                FOREIGN KEY (todo_id) REFERENCES todos(id) ON DELETE CASCADE
            )");
    Database::commitTransaction();
} catch (\Exception $exception) {
    //throw $th;
    Database::rollbackTransaction();
    echo "Error migrating database: " . $exception->getMessage() . "\n";
    throw $exception;
}
