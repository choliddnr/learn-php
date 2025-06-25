<?php

namespace App\Controller;


use App\Core\Controller;
use App\Core\Database;
use App\Model\TodoCreateRequest;
// use App\Models\TodoModel;
use App\Model\TodoUpdateRequest;
use App\Service\SessionService;
use App\Service\TodoService;
use App\Service\TagService;
use DateTime;

class TodoController extends Controller
{
    // protected $todos;
    private TodoService $todo_service;
    private TagService $tag_service;
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
        $this->todo_service = new TodoService();
        $this->tag_service = new TagService();
    }
    public function index()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $_params = parse_url($uri, PHP_URL_QUERY);
        if (isset($_params) && !empty($_params)) {

            parse_str($_params, $params);



            if (isset($params['status']) && !empty($params['status'])) {
                $params['status'] = explode(',', $params['status']);
            } else {
                $params['status'] = [];
            }
            if (isset($params['tags']) && !empty($params['tags'])) {
                $params['tags'] = explode(',', $params['tags']);
            } else {
                $params['tags'] = [];
            }
            $params['tags'] = array_map('intval', $params['tags']);

            $todos =  $this->todo_service->getAllWithFilter($params['tags'], $params['status']);
            // echo "<pre>";
            // var_dump(empty([]));
            // echo "</pre>";
        } else {
            $todos = $this->todo_service->getAll();
        }
        return $this->view('todo/index', [
            'todos' => $todos,
            'tags' => $this->tag_service->getAll(),
            'tag_filter' => $params['tags'] ?? [],
            'status_filter' => $params['status'] ?? [],
            'view' => 'todo/index',
        ]);
    }

    public function getCreateForm()
    {
        $data = [
            'errors' => $this->getFlashData('errors'),
            'form' => $this->getFlashData('form'),
            'tags' => $this->tag_service->getAll()
        ];

        return $this->view('todo/createform', $data);
    }
    public function getUpdateForm($id)
    {
        $data = [
            'id' => $id,
            'tags' => $this->tag_service->getAll(),
            'errors' => $this->getFlashData('errors'),
        ];
        $form = $this->getFlashData('form');
        if ($form) {
            $form->tags = $this->tag_service->getTagsByTodoIds($form->tags);
            $data['form'] = $form;
        } else {
            $data['form'] = $this->todo_service->getById($id);
        }
        return $this->view('todo/updateform', $data);
    }

    public function showTodo($id)
    {
        $flash_data = $this->getFlashData('todo');
        // // $form = $this->getFlashData('form');
        // // if ($form) {
        // //     $form->tags = $this->tag_service->getTagsByTodoIds($form->tags);
        // //     $data['form'] = $form;
        // // } else {
        // //     $data['form'] = $this->todo_service->getById($id);
        // // }
        $todo = $flash_data ?? $this->todo_service->getById($id);
        return $this->view('todo/show', ['todo' => $todo]);
    }

    public function createTodo()
    {
        $request = new TodoCreateRequest($_POST['title'], $_POST['description'], $_POST['deadline'], $_POST['tags']);

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
        $request = new TodoUpdateRequest($id, $_POST['title'], $_POST['description'], $_POST['status'], $_POST['deadline'], $_POST['tags'] ?? []);

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
