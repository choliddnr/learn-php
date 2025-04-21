<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        // Load the index view
        include_once __DIR__ . '/../views/index.php';
    }

    public function about()
    {
        // Load the about view
        include_once 'app/views/about.php';
    }
}