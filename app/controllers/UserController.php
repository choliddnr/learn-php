<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserUpdateProfileRequest;
use App\Services\SessionService;
use App\Services\UserService;

class UserController extends Controller
{

    protected UserService $user_service;
    protected SessionService $session_service;

    public function __construct()
    {
        $this->user_service = new UserService();
        $this->session_service = new SessionService();

    }

    public function getProfileUpdateForm()
    {
        $errors = $this->getFlashData('errors') ?? null;
        $form = $this->getFlashData('form') ?? null;
        return $this->view('user/updateform', [
            'form' => $form ?? $this->user_service->getById(SessionService::$user_id),
            'errors' => $errors,
        ]);

    }
    public function updateProfile()
    {

        $request = new UserUpdateProfileRequest();
        $request->id = SessionService::$user_id;
        $request->name = $_POST['name'];
        $request->current_password = $_POST['current_password'];
        $request->new_password = $_POST['new_password'];
        $request->confirm_password = $_POST['confirm_password'];

        $response = $this->user_service->updateProfile($request);
        $this->setFlashData('success', 'Profile updated successfully');

        if (count($response->errors) > 0) {
            $this->setFlashData('errors', $response->errors);
            $request->email = $_POST['email'];
            $this->setFlashData('form', $request);
            return $this->redirect('/user/update');
        }

        $this->setFlashData('user', $response->user);
        return $this->redirect('/');

    }

    public function delete()
    {
        $this->user_service->delete(SessionService::$user_id);
        $this->session_service->destroy();
        // $this->session_service->destroySession();
        // return $this->redirect('/login');
    }
}