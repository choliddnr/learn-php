<?php

namespace App\Service;

use App\Core\Database;
use App\Domain\User;
use App\Domain\UserProfile;
use App\Model\UserLoginRequest;
use App\Model\UserLoginResponse;
use App\Model\UserRegisterResponse;
use App\Model\UserUpdateRequest;
use App\Model\UserUpdateResponse;
use App\Model\UserCreateProfileRequest;
use App\Model\UserCreateProfileResponse;
use App\Repository\UserRepository;
use App\Model\UserRegisterRequest;

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
        if (!isset($request->username) || empty($request->username)) {
            $error['username'] = 'username is required';
        } else {
            $username = $request->username;
            if (strlen($username) < 4) {
                $error['username'] = 'username must be at least 4 characters long';
            } else if (strlen($username) > 50) {
                $error['username'] = 'username must be less than 50 characters long';
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
            $user->username = $request->username;
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
            $error['general'] = $exception->getMessage();
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

    public function getProfileById($id): UserProfile|null
    {
        $user = $this->user_repository->findProfileById($id);
        return $user;
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

    public function createProfile(UserCreateProfileRequest $request): UserCreateProfileResponse
    {
        $error = [];
        if (!isset($request->fullname) || empty($request->fullname)) {
            $error['fullname'] = 'fullname is required';
        } else {
            $fullname = $request->fullname;
            if (strlen($fullname) < 4) {
                $error['fullname'] = 'fullname must be at least 4 characters long';
            } else if (strlen($fullname) > 50) {
                $error['fullname'] = 'fullname must be less than 50 characters long';
            }
        }

        if (!isset($request->whatsapp) || empty($request->whatsapp)) {
            $error['whatsapp'] = 'whatsapp is required';
        } else {
            $whatsapp = $request->whatsapp;
            if (strlen($whatsapp) < 10) {
                $error['whatsapp'] = 'whatsapp must be at least 10 characters long';
            } else if (strlen($whatsapp) > 14) {
                $error['whatsapp'] = 'whatsapp must be less than 14 characters long';
            }
        }

        if (!isset($request->gender) || empty($request->gender)) {
            $error['gender'] = 'gender is required';
        } else {
            $gender = $request->gender;
            if ((int)$gender !== 0 && (int)$gender !== 1) {
                $error['gender'] = 'gender is not valid';
            }
        }

        $is_avatar_valid = isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK;

        if ($is_avatar_valid) {
            $file_tmp_path = $_FILES['avatar']['tmp_name'];
            $file_name = $_FILES['avatar']['name'];
            $file_size = $_FILES['avatar']['size'];
            $file_type = $_FILES['avatar']['type'];

            // Optional: sanitize the filename
            $file_name_safe = preg_replace("/[^a-zA-Z0-9\.\-_]/", "_", basename($file_name));
            $upload_dir = __DIR__ . '/../../public/uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true); // create uploads/ if not exists
            }

            $destination = $upload_dir . $file_name_safe;
        } else {
            $error['avatar'] = "❌ File upload error: " . $_FILES['avatar']['error'];
        }


        $response = new UserCreateProfileResponse;
        if (count($error) > 0) {
            $response->errors = $error;
            return $response;
        }

        try {
            Database::beginTransaction();
            $profile = new UserProfile;
            $profile->id = SessionService::$user_id;
            $profile->fullname = $request->fullname;
            $profile->whatsapp = $request->whatsapp;
            $profile->gender = $request->gender;


            if ($is_avatar_valid) {
                if (move_uploaded_file($file_tmp_path, $destination)) {
                    $profile->avatar = $file_name_safe;
                    // echo "✅ File uploaded successfully!";
                } else {
                    $error['avatar'] = "❌ File upload error: " . $_FILES['avatar']['error'];
                    throw new \Exception($error['avatar']);
                }
            }
            $this->user_repository->saveProfile($profile);
            $response->profile = $profile;
            Database::commitTransaction();
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            $error['general'] = $exception->getMessage();
            $response->errors = $error;
            return $response;
        }
    }

    public function updateProfile(UserUpdateRequest $request): UserUpdateResponse
    {
        $error = [];
        if (!isset($request->username) || empty($request->username)) {
            $error['username'] = 'username is required';
        } else {
            $username = $request->username;
            if (strlen($username) < 4) {
                $error['username'] = 'username must be at least 4 characters long';
            } else if (strlen($username) > 50) {
                $error['username'] = 'username must be less than 50 characters long';
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



        if (!isset($request->fullname) || empty($request->fullname)) {
            $error['fullname'] = 'fullname is required';
        } else {
            $fullname = $request->fullname;
            if (strlen($fullname) < 4) {
                $error['fullname'] = 'fullname must be at least 4 characters long';
            } else if (strlen($fullname) > 50) {
                $error['fullname'] = 'fullname must be less than 50 characters long';
            }
        }

        if (!isset($request->whatsapp) || empty($request->whatsapp)) {
            $error['whatsapp'] = 'whatsapp is required';
        } else {
            $whatsapp = $request->whatsapp;
            if (strlen($whatsapp) < 10) {
                $error['whatsapp'] = 'whatsapp must be at least 10 characters long';
            } else if (strlen($whatsapp) > 14) {
                $error['whatsapp'] = 'whatsapp must be less than 14 characters long';
            }
        }


        $gender = $request->gender;
        if ((int)$gender !== 0 && (int)$gender !== 1) {
            $error['gender'] = 'gender is not valid';
        }

        $is_avatar_valid = isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK;

        if ($is_avatar_valid) {
            $file_tmp_path = $_FILES['avatar']['tmp_name'];
            $file_name = $_FILES['avatar']['name'];
            $file_size = $_FILES['avatar']['size'];
            $file_type = $_FILES['avatar']['type'];

            // Optional: sanitize the filename
            $file_name_safe = preg_replace("/[^a-zA-Z0-9\.\-_]/", "_", basename($file_name));
            $upload_dir = __DIR__ . '/../../public/uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true); // create uploads/ if not exists
            }

            $destination = $upload_dir . $file_name_safe;
        }

        $user = $this->getById(SessionService::$user_id);
        $profile = $this->getProfileById(SessionService::$user_id);
        if (!isset($request->new_password) && $user->username === $request->username && empty($error) && $profile->fullname === $request->fullname && $profile->whatsapp === $request->whatsapp && !$is_avatar_valid) {
            $error['general'] = 'No changes were made';
        }



        $response = new UserUpdateResponse;
        if (count($error) > 0) {
            $response->errors = $error;
            return $response;
        }

        try {
            Database::beginTransaction();
            $user->username = $request->username;
            if (isset($request->new_password) && !empty($request->new_password)) {
                $user->password = password_hash($request->new_password, PASSWORD_BCRYPT);
            }
            // $new_data->updated_at = date('Y-m-d H:i:s');
            $this->user_repository->update($user);
            $response->user = $user;

            $profile->fullname = $request->fullname;
            $profile->whatsapp = $request->whatsapp;
            $profile->gender = $request->gender;


            if ($is_avatar_valid) {
                if ($profile->avatar && file_exists(__DIR__ . '/../../public/uploads/' . $profile->avatar)) {
                    unlink(__DIR__ . '/../../public/uploads/' . $profile->avatar); // delete old avatar
                }
                if (move_uploaded_file($file_tmp_path, $destination)) {
                    $profile->avatar = $file_name_safe;
                    // echo "✅ File uploaded successfully!";
                } else {
                    $error['avatar'] = "❌ File upload error: " . $_FILES['avatar']['error'];
                    throw new \Exception($error['avatar']);
                }
            }
            $this->user_repository->updateProfile($profile);
            $response->profile = $profile;

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
