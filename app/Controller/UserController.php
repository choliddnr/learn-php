<?php

namespace App\Controller;

use App\Core\Controller;
use App\Domain\UploadedFile;
use App\Model\UserCreateProfileRequest;
use App\Model\UserUpdateRequest;
use App\Service\SessionService;
use App\Service\UserService;

class UserController extends Controller
{
    protected UserService $user_service;
    protected SessionService $session_service;

    public function __construct()
    {
        $this->user_service = new UserService();
        $this->session_service = new SessionService();
    }


    public function getCreateProfileForm()
    {
        $errors = $this->getFlashData('errors') ?? null;
        $form = $this->getFlashData('form') ?? null;
        return $this->view('user/createform', [
            'form' => $form, // ?? $this->user_service->getProfileById(SessionService::$user_id),
            'errors' => $errors,
        ]);
    }


    public function getProfileUpdateForm()
    {
        $errors = $this->getFlashData('errors') ?? null;
        $form = $this->getFlashData('form') ?? null;


        return $this->view('user/updateform', [
            'form' => $form ?? (object)array_merge(get_object_vars($this->user_service->getById(SessionService::$user_id)), get_object_vars($this->user_service->getProfileById(SessionService::$user_id))),
            'errors' => $errors,
        ]);
    }


    public function createProfile()
    {

        $request = new UserCreateProfileRequest();
        $request->fullname = $_POST['fullname'] ?? '';
        $request->whatsapp = $_POST['whatsapp'] ?? '';
        $request->gender = $_POST['gender'] ?? '';
        $request->avatar = new UploadedFile($_FILES['avatar']) ?? null;
        $response = $this->user_service->createProfile($request);
        if (count($response->errors) > 0) {
            $this->setFlashData('errors', $response->errors);
            $this->setFlashData('form', $request);
            return $this->redirect('/user/create');
        }
        $this->setFlashData('profile', $response->profile);
        return $this->redirect('/');
    }

    public function updateProfile()
    {

        $request = new UserUpdateRequest();
        $request->id = SessionService::$user_id;

        $request->username = $_POST['username'];
        $request->current_password = $_POST['current_password'];
        $request->new_password = $_POST['new_password'];
        $request->confirm_password = $_POST['confirm_password'];

        $request->fullname = $_POST['fullname'] ?? '';
        $request->whatsapp = $_POST['whatsapp'] ?? '';
        $request->gender = $_POST['gender'] ?? '';
        $request->avatar = isset($_FILES['avatar']) ? new UploadedFile($_FILES['avatar']) : null;

        $response = $this->user_service->updateProfile($request);
        $this->setFlashData('success', 'Profile updated successfully');

        if (count($response->errors) > 0) {
            $this->setFlashData('errors', $response->errors);
            // $request->email = $_POST['email'];
            $this->setFlashData('form', $request);
            return $this->redirect('/user/update');
        }

        $response->user->email = $_POST['email'] ?? $response->user->email;




        $this->setFlashData('user', $response->user);
        $this->setFlashData('profile', $response->profile);

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
