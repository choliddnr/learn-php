<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserLoginRequest;
use App\Models\UserRegisterRequest;
use App\Services\SessionService;
use App\Services\UserService;

class AuthController extends Controller
{
    protected UserService $user_service;
    protected SessionService $session_service;
    public function __construct()
    {
        $this->user_service = new UserService();
        $this->session_service = new SessionService();
    }
    public function getLoginForm()
    {
        $data = [
            'errors' => $this->getFlashData('errors'),
        ];

        $this->view('auth/login', $data);
    }
    public function getRegisterForm()
    {
        $data = [
            'errors' => $this->getFlashData('errors'),
            'form' => $this->getFlashData('form'),
        ];
        $this->view('auth/register', $data);
    }

    public function register()
    {


        $request = new UserRegisterRequest($_POST['name'], $_POST['email'], $_POST['password'], $_POST['confirm_password']);

        $response = $this->user_service->register($request);
        if (count($response->errors) > 0) {

            $this->setFlashData('errors', $response->errors);
            $this->setFlashData('form', $request);
            return $this->redirect('/register');
        }
        $this->session_service->create($response->user->id);
        $this->setFlashData('user', $response->user);
        $this->redirect('/');
    }
    public function login()
    {
        $request = new UserLoginRequest($_POST['email'], $_POST['password']);
        $response = $this->user_service->login($request);

        if (count($response->errors) > 0) {
            $this->setFlashData('errors', $response->errors);
            return $this->redirect('/login');
        }
        $this->session_service->create($response->user->id);
        $this->redirect('/todo');


    }

    public function logout()
    {
        $this->session_service->destroy();
        $this->redirect('/login');
    }
}