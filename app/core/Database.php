<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    protected static ?PDO $pdo = null;
    public static function connect()
    {
        if (self::$pdo) {
            return self::$pdo;
        }
        try {
            $config = require_once __DIR__ . "/../../config/database.php";
            self::$pdo = new PDO($config['dsn'] . ":" . $config['path']);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return self::$pdo;
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }

    public static function beginTransaction()
    {
        self::$pdo->beginTransaction();
    }

    public static function commitTransaction()
    {
        self::$pdo->commit();
    }

    public static function rollbackTransaction()
    {
        self::$pdo->rollback();
    }


}