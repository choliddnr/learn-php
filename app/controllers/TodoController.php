<?php
namespace App\Controllers;


use App\Core\Controller;
use App\Core\Database;
use App\Models\TodoCreateRequest;
// use App\Models\TodoModel;
use App\Models\TodoUpdateRequest;
use App\Services\SessionService;
use App\Services\TodoService;
use DateTime;

class TodoController extends Controller
{
    // protected $todos;
    private TodoService $todo_service;
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
        $this->todo_service = new TodoService();
    }
    public function index()
    {
        return $this->view('todo/index', ['todos' => $this->todo_service->getAll()]);
    }

    public function getCreateForm()
    {
        $data = [
            'errors' => $this->getFlashData('errors'),
            'form' => $this->getFlashData('form'),
        ];
        var_dump($data);

        return $this->view('todo/createform', $data);
    }
    public function getUpdateForm($id)
    {
        $data = [
            'id' => $id,
            'form' => $this->getFlashData('form') ?? $this->todo_service->getById($id),
            'errors' => $this->getFlashData('errors'),
        ];
        return $this->view('todo/updateform', $data);
    }

    public function showTodo($id)
    {
        $flash_data = $this->getFlashData('todo');
        $todo = $flash_data ?? $this->todo_service->getById($id);
        return $this->view('todo/show', ['todo' => $todo]);
    }

    public function createTodo()
    {
        $request = new TodoCreateRequest($_POST['title'], $_POST['description'], $_POST['deadline']);

        $response = $this->todo_service->create($request);
        if (!empty($response->errors)) {
            $this->setFlashData('errors', $response->errors);
            $this->setFlashData('form', $request);
            $this->redirect('/todo/create');
        }
        $this->setFlashData('todo', $response->todo);
        $this->redirect('/todo/' . $response->todo->id);


    }
    public function deleteTodo($id)
    {
        return $this->todo_service->delete($id);
    }

    public function updateTodo($id)
    {
        $request = new TodoUpdateRequest($id, $_POST['title'], $_POST['description'], $_POST['status'], $_POST['deadline']);

        $response = $this->todo_service->update($request);

        if (!empty($response->errors)) {
            $this->setFlashData('errors', $response->errors);
            $this->setFlashData('form', $request);
            $this->redirect('/todo/' . $id . '/update');
            return;
        }
        $this->setFlashData('todo', $response->todo);
        $this->redirect('/todo/' . $response->todo->id);
    }

    public function delete($id)
    {
        $this->todo_service->delete($id);
        $this->redirect('/todo');
    }
}