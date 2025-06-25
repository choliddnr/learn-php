<?php

$database = "mysql";
if ($database === "sqlite") {
    $database_path = __DIR__ . "/../database/db.sqlite";

    return ['dsn' => $database, 'path' => $database_path];
}

if ($database === "mysql") {
    $database_name = getenv('MYSQL_DATABASE');
    $user = getenv('MYSQL_USER');
    $password = getenv('MYSQL_PASSWORD');
    return [
        'dsn' => 'mysql:host=mysql;dbname=' . $database_name,
        'user' => $user,
        'password' => $password
    ];
}

// $dsn = 'sqlite';
// $database_path = __DIR__ . "/../database/db.sqlite";


// $db = new PDO(
//     'mysql:host=mysql;dbname=' . getenv('MYSQL_DATABASE'),
//     getenv('MYSQL_USER'),
//     getenv('MYSQL_PASSWORD')
// );

// if (!$db) {
//     die("Database connection failed: " . $db->errorInfo()[2]);
// }
// return ['dsn' => $dsn, 'path' => $database_path];
