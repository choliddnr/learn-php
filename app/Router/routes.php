<?php
// require_once __DIR__ . "/../core/Router.php";

use App\Controller\AboutController;
use App\Controller\LandingpageController;
use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\DatabaseController;
use App\Controller\TodoController;
use App\Controller\TagController;
use App\Controller\UserController;
use App\Middleware\AuthMiddleware;
use App\Core\Router;

Router::add('/', 'GET', HomeController::class, 'index', [AuthMiddleware::class]);
Router::add('/about', 'GET', AboutController::class, 'index');
Router::add('/about/:test', 'GET', AboutController::class, 'test');
Router::add('/todo', 'GET', TodoController::class, 'index', [AuthMiddleware::class]);
Router::add('/todo/create', 'GET', TodoController::class, 'getCreateForm', [AuthMiddleware::class]);
Router::add('/todo/:id', 'GET', TodoController::class, 'showTodo', [AuthMiddleware::class]);
Router::add('/todo/:id/update', 'GET', TodoController::class, 'getUpdateForm', [AuthMiddleware::class]);
Router::add('/todo', 'POST', TodoController::class, 'createTodo', [AuthMiddleware::class]);
Router::add('/todo/:id', 'DELETE', TodoController::class, 'deleteTodo', [AuthMiddleware::class]);
Router::add('/todo/:id', 'POST', TodoController::class, 'updateTodo', [AuthMiddleware::class]);


Router::add('/tag', 'GET', TagController::class, 'index', [AuthMiddleware::class]);
Router::add('/tag/create', 'GET', TagController::class, 'getCreateForm', [AuthMiddleware::class]);
Router::add('/tag/:id', 'GET', TagController::class, 'showTag', [AuthMiddleware::class]);
Router::add('/tag/:id/update', 'GET', TagController::class, 'getUpdateForm', [AuthMiddleware::class]);
Router::add('/tag', 'POST', TagController::class, 'createTag', [AuthMiddleware::class]);
Router::add('/tag/:id', 'DELETE', TagController::class, 'deleteTag', [AuthMiddleware::class]);
Router::add('/tag/:id', 'POST', TagController::class, 'updateTag', [AuthMiddleware::class]);



Router::add('/login', 'GET', AuthController::class, 'getLoginForm');
Router::add('/login', 'POST', AuthController::class, 'login');
Router::add('/register', 'GET', AuthController::class, 'getRegisterForm');
Router::add('/register', 'POST', AuthController::class, 'register');
Router::add('/logout', 'GET', AuthController::class, 'logout', [AuthMiddleware::class]);
Router::add('/user/update', 'GET', UserController::class, 'getProfileUpdateForm', [AuthMiddleware::class]);
Router::add('/user/update', 'Post', UserController::class, 'updateProfile', [AuthMiddleware::class]);
Router::add('/user', 'DELETE', UserController::class, 'delete', [AuthMiddleware::class]);
Router::add('/user/create', 'GET', UserController::class, 'getCreateProfileForm', [AuthMiddleware::class]);
Router::add('/user/create', 'POST', UserController::class, 'createProfile', [AuthMiddleware::class]);

Router::add('/landingpage', 'GET', LandingpageController::class, 'index');
Router::add('/db', 'GET', DatabaseController::class, 'index');




Router::run();
