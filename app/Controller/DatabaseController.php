<?php

namespace App\Controller;

use App\Core\Controller;

class DatabaseController extends Controller
{
    public function index()
    {
        require_once __DIR__ . "/../../database/migrate_mysql.php";
        echo "Database migration completed successfully.\n";
        // return $this->view('test');
    }
}
