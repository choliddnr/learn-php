<?php

namespace App\Models;

use PDO;
use PDOException;

class BaseModel
{
    protected $pdo;
    private $db_config;
    public function __construct()
    {
        try {
            $this->db_config = require_once __DIR__ . "/../../config/database.php";
            $this->pdo = new PDO($this->db_config['dsn'] . ":" . $this->db_config['path']);

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            //throw $th;

            die("Database error: " . $e->getMessage());
        }
    }



}