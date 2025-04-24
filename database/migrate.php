<?php

use App\Core\Database;

require_once __DIR__ . "/../app/core/Database.php";
echo "migrate.php\n";

$pdo = Database::connect();

try {
    Database::beginTransaction();
    $pdo->exec("CREATE TABLE IF NOT EXISTS users(
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL ,
                email TEXT NOT NULL,
                password TEXT NOT NULL,
                created_at INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
                updated_at INTEGER NOT NULL DEFAULT (strftime('%s', 'now'))
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
    Database::commitTransaction();
} catch (\Exception $exception) {
    //throw $th;
    Database::rollbackTransaction();
    throw $exception;

}

