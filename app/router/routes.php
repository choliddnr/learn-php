<?php
require_once __DIR__ . "/../core/Router.php";

use App\Controllers\AboutController;
use App\Controllers\HomeController;
use App\Controllers\TodoController;
use App\Router\Router;

Router::add('/', 'GET', HomeController::class, 'index');
Router::add('/about', 'GET', AboutController::class, 'index');
Router::add('/about/:test', 'GET', AboutController::class, 'test');
Router::add('/todo', 'GET', TodoController::class, 'index');
Router::add('/todo/create', 'GET', TodoController::class, 'getCreateForm');
Router::add('/todo/:id', 'GET', TodoController::class, 'showTodo');
Router::add('/todo/:id/update', 'GET', TodoController::class, 'getUpdateForm');
Router::add('/todo', 'POST', TodoController::class, 'createTodo');
Router::add('/todo/:id', 'DELETE', TodoController::class, 'deleteTodo');
Router::add('/todo/:id', 'POST', TodoController::class, 'updateTodo');

Router::run();