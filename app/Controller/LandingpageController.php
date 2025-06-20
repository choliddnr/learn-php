<?php

namespace App\Controller;

use App\Core\Controller;

class LandingpageController extends Controller{

    public function index() {
        return $this->view('landingpage');
    }
}