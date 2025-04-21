<?php
namespace App\Controllers;

require_once __DIR__ . "/../core/BaseController.php";

use App\Controllers\BaseController;
use App\Models\TodoModel;
use DateTime;

class TodoController extends BaseController
{
    protected $todos;
    // public $thetodos;

    public function __construct()
    {
        // $this->thetodos = [
        //     ['id' => 1, "title" => "Todo A", 'description' => "Description todo A", "deadline" => new DateTime()->getTimestamp(), 'status' => "ongoing"],
        //     ['id' => 2, "title" => "Todo B", 'description' => "Description todo B", "deadline" => new DateTime()->getTimestamp(), 'status' => "delayed"],
        //     ['id' => 3, "title" => "Todo C", 'description' => "Description todo C", "deadline" => new DateTime()->getTimestamp(), 'status' => "ongoing"],
        //     ['id' => 4, "title" => "Todo D", 'description' => "Description todo D", "deadline" => new DateTime()->getTimestamp(), 'status' => "done"],
        //     ['id' => 5, "title" => "Todo E", 'description' => "Description todo E", "deadline" => new DateTime()->getTimestamp(), 'status' => "delayed"],
        //     ['id' => 6, "title" => "Todo F", 'description' => "Description todo F", "deadline" => new DateTime()->getTimestamp(), 'status' => "ongoing"],
        //     ['id' => 7, "title" => "Todo G", 'description' => "Description todo G", "deadline" => new DateTime()->getTimestamp(), 'status' => "done"],
        //     ['id' => 8, "title" => "Todo H", 'description' => "Description todo H", "deadline" => new DateTime()->getTimestamp(), 'status' => "delayed"],
        //     ['id' => 9, "title" => "Todo I", 'description' => "Description todo I", "deadline" => new DateTime()->getTimestamp(), 'status' => "ongoing"],
        //     ['id' => 10, "title" => "Todo J", 'description' => "Description todo J", "deadline" => new DateTime()->getTimestamp(), 'status' => "delayed"],
        //     ['id' => 11, "title" => "Todo K", 'description' => "Description todo K", "deadline" => new DateTime()->getTimestamp(), 'status' => "done"],
        //     ['id' => 12, "title" => "Todo L", 'description' => "Description todo L", "deadline" => new DateTime()->getTimestamp(), 'status' => "ongoing"],
        //     ['id' => 13, "title" => "Todo M", 'description' => "Description todo M", "deadline" => new DateTime()->getTimestamp(), 'status' => "done"],
        //     ['id' => 14, "title" => "Todo N", 'description' => "Description todo N", "deadline" => new DateTime()->getTimestamp(), 'status' => "delayed"],
        // ];
        $this->todos = new TodoModel();
    }
    public function index()
    {

        // for ($i = 0; $i < 14; $i++) {
        //     $this->todos->createTodo($this->thetodos[$i]['title'], $this->thetodos[$i]['description'], $this->thetodos[$i]['status'], time(), );

        // }
        return $this->view('todo/index', ['todos' => $this->todos->getAll()]);
    }

    public function getCreateForm()
    {

        return $this->view('todo/createform');
    }
    public function getUpdateForm($id)
    {
        // Load the about view
        $data = [
            'task' => $this->todos->getTodo($id)
        ];
        // echo $test;
        return $this->view('todo/updateform', $data);
    }

    public function showTodo($id)
    {
        $task = $this->todos->getTodo($id);
        return $this->view('todo/show', ['task' => $task]);
    }

    public function createTodo()
    {
        $id = $this->todos->createTodo($_POST['title'], $_POST['description'], $_POST['status'], strtotime($_POST['deadline']), );
        // var_dump($todo);
        return $this->redirect('/todo/' . $id);
    }

    public function deleteTodo($id)
    {
        return $this->todos->deleteTodo($id);
    }

    public function updateTodo($id)
    {
        $this->todos->updateTodo($id, $_POST['title'], $_POST['description'], $_POST['status'], strtotime($_POST['deadline']));
        return $this->redirect("/todo/" . (string) $id);
    }
}