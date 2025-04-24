<?php

namespace App\Services;

use App\Core\Database;
use App\Domain\User;
use App\Models\UserLoginRequest;
use App\Models\UserLoginResponse;
use App\Models\UserRegisterResponse;
use App\Models\UserUpdateProfileRequest;
use App\Models\UserUpdateProfileResponse;
use App\Repositories\UserRepository;
use App\Models\UserRegisterRequest;
class UserService
{
    private UserRepository $user_repository;
    public function __construct()
    {
        $this->user_repository = new UserRepository();
    }

    public function register(UserRegisterRequest $request): UserRegisterResponse
    {
        // Validate the request


        $error = [];
        if (!isset($request->name) || empty($request->name)) {
            $error['name'] = 'Name is required';
        } else {
            $name = $request->name;
            if (strlen($name) < 4) {
                $error['name'] = 'Name must be at least 4 characters long';
            } else if (strlen($name) > 50) {
                $error['name'] = 'Name must be less than 50 characters long';
            }
        }

        if (!isset($request->email) || empty($request->email)) {
            $error['email'] = 'Email is required.';
        } else {
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                $error['email'] = "Invalid email format.";
            } else {
                $user = $this->getByEmail($request->email);
                if ($user) {
                    $error['email'] = "Email already exists.";
                }
            }
        }

        if (!isset($request->password) || empty($request->password)) {
            var_dump($request->password);
            $error['password'] = 'Password is required.';
        } else {
            if (strlen($request->password) < 4) {
                $error['password'] = 'Password must be at least 4 characters long';
            } else if (strlen($request->password) > 50) {
                $error['password'] = 'Password must be less than 50 characters long';
            }
        }

        if (!isset($request->confirm_password) || empty($request->confirm_password)) {
            $error['confirm_password'] = 'Confirm password is required';
        } else {
            if ($request->password !== $request->confirm_password) {
                $error['confirm_password'] = 'Password does not match';
            }
        }

        $response = new UserRegisterResponse;

        if (count($error) > 0) {
            $response->errors = $error;
            return $response;
        }

        try {

            Database::beginTransaction();

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);
            $user->created_at = date('Y-m-d H:i:s');
            $user->updated_at = date('Y-m-d H:i:s');

            $user_id = $this->user_repository->save($user);
            $user->id = $user_id;

            $response = new UserRegisterResponse;
            $response->user = $user;

            Database::commitTransaction();
            return $response;

        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            $error['general'] = 'Something went wrong. Please try again later.';
            $response->errors = $error;
            return $response;
        }
    }

    public function login(UserLoginRequest $request): UserLoginResponse
    {

        $error = [];
        if (!isset($request->email) || empty($request->email)) {
            $error['email'] = 'Email is required.';
        } else {
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                $error['email'] = "Invalid email format.";
            }
        }

        if (!isset($request->password) || empty($request->password)) {
            $error['password'] = 'Password is required.';
        }


        $user = $this->user_repository->findByEmail($request->email);
        if (!$user || !password_verify($request->password, $user->password)) {
            $error['general'] = "Something wrong with your email or password.";
        }

        $response = new UserLoginResponse;
        if (count($error) > 0) {
            $response->errors = $error;
            return $response;
        }

        $response->user = $user;
        return $response;
    }

    public function getById($id): User|null
    {
        return $this->user_repository->findById($id);
    }

    public function getByEmail($email): User|null
    {
        return $this->user_repository->findByEmail($email);
    }

    public function checkPassword($id, $password): bool
    {
        $user = $this->user_repository->findById($id);
        if (!$user) {
            throw new \Exception("User not found.");
        }
        return password_verify($password, $user->password);
    }

    public function delete($id)
    {
        $user = $this->user_repository->findById($id);
        if (!$user) {
            throw new \Exception("User not found.");
        }
        $this->user_repository->delete($id);
        return true;
    }

    public function updateProfile(UserUpdateProfileRequest $request): UserUpdateProfileResponse
    {
        $error = [];
        if (!isset($request->name) || empty($request->name)) {
            $error['name'] = 'Name is required';
        } else {
            $name = $request->name;
            if (strlen($name) < 4) {
                $error['name'] = 'Name must be at least 4 characters long';
            } else if (strlen($name) > 50) {
                $error['name'] = 'Name must be less than 50 characters long';
            }
        }

        if (!isset($request->current_password) || empty($request->current_password)) {
            $error['current_password'] = 'Current password is required';
        } else {
            if (!$this->checkPassword(SessionService::$user_id, $request->current_password)) {
                $error['current_password'] = 'Current password is incorrect';
            }
        }
        if (isset($request->new_password) && !empty($request->new_password)) {
            if (!isset($request->confirm_password) || empty($request->confirm_password)) {
                $error['confirm_password'] = 'Confirm password is required';
            }
            if ($request->new_password !== $request->confirm_password) {
                $error['confirm_password'] = 'Password does not match';
            }
            if ($request->new_password == $request->current_password) {
                $error['new_password'] = 'You can\'t use the same password as current password';
            }
        }
        $user = $this->getById(SessionService::$user_id);
        if (!isset($request->new_password) && $user->name === $request->name && empty($error)) {
            $error['general'] = 'No changes were made';
        }

        $response = new UserUpdateProfileResponse;
        if (count($error) > 0) {
            $response->errors = $error;
            return $response;
        }

        try {
            Database::beginTransaction();
            $user->name = $request->name;
            if (isset($request->new_password) && !empty($request->new_password)) {
                $user->password = password_hash($request->new_password, PASSWORD_BCRYPT);
            }
            // $new_data->updated_at = date('Y-m-d H:i:s');
            $this->user_repository->update($user);
            $response->user = $user;
            Database::commitTransaction();
            return $response;
        } catch (\Exception $e) {
            Database::rollbackTransaction();
            $error['general'] = 'No changes were made';
            $response->errors = $error;
            return $response;

        }



    }

}