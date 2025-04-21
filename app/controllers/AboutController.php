<?php
namespace App\Controllers;

require_once __DIR__ . "/../core/BaseController.php";

use App\Controllers\BaseController;

class AboutController extends BaseController
{
    public function index()
    {
        return $this->view('about');
    }

    public function test($test)
    {
        // Load the about view
        $data = [
            'test' => $test
        ];
        // echo $test;
        return $this->view('about', $data);
    }
}