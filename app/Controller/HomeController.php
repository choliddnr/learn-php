<?php

namespace App\Controller;

use App\Core\Controller;
use App\Service\SessionService;
use App\Service\UserService;

class HomeController extends Controller
{

    public UserService $user_service;

    public function __construct()
    {
        $this->user_service = new UserService();
    }
    public function index()
    {
        // Load the index view
        // return $this->redirect('/todo');
        $user = $this->getFlashData('user');
        $profile = $this->getFlashData('profile') ?? $this->user_service->getProfileById(SessionService::$user_id);
        if (!$profile) $this->redirect("/user/create");
        $data = [
            'user' => $user ?? $this->user_service->getById(SessionService::$user_id),
            'profile' => $profile ?? $this->user_service->getProfileById(SessionService::$user_id),
        ];


        // var_dump($data);
        return $this->view("index", $data);
    }

    public function about()
    {
        // Load the about view
        include_once 'app/views/about.php';
    }
}
