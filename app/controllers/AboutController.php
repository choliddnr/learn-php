<?php
namespace App\Controllers;

use App\Core\Controller;

class AboutController extends Controller
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