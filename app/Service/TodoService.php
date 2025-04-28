<?php

namespace App\Service;

use App\Core\Database;
use App\Domain\Todo;
use App\Model\TodoCreateRequest;
use App\Model\TodoCreateResponse;
use App\Model\TodoUpdateRequest;
use App\Model\TodoUpdateResponse;
use App\Repository\TodoRepository;
use App\Service\SessionService;

class TodoService
{
    private TodoRepository $todo_repository;
    private SessionService $session_service;
    public static string $default_status = 'pending';
    public function __construct()
    {
        $this->todo_repository = new TodoRepository();
        $this->session_service = new SessionService();

    }
    public function create(TodoCreateRequest $request): TodoCreateResponse
    {
        // Validate the request data
        $error = [];
        if (empty($request->title)) {
            $error['title'] = "Title is required.";
        } else if (strlen($request->title) < 4) {
            $error['title'] = "Title cannot be less than 4 characters.";
        }
        if (empty($request->deadline)) {
            $error['deadline'] = "Deadline is required.";
        }
        $request->deadline = strtotime($request->deadline);

        if ($request->deadline < time()) {
            $error['deadline'] = "Deadline cannot be in the past.";
        }
        $response = new TodoCreateResponse();
        if (!empty($error)) {
            $response->errors = $error;
            return $response;
        }


        try {
            Database::beginTransaction();

            $todo = new Todo();
            $todo->title = $request->title;
            $todo->description = $request->description ?? "";
            $todo->user_id = SessionService::$user_id;
            $todo->status = self::$default_status;
            $todo->deadline = (int) $request->deadline;

            $id = $this->todo_repository->save($todo);
            $todo->id = $id;

            // Create a response object
            $response = new TodoCreateResponse();
            $response->todo = $todo;
            Database::commitTransaction();
        } catch (\Throwable $th) {
            //throw $th;
            Database::rollbackTransaction();
            $response->errors = ['general' => 'Failed to create todo.'];
            return $response;
        }



        return $response;
    }

    public function getById($id): Todo
    {
        return $this->todo_repository->findById($id);
    }

    /**
     * @return Todo[]
     */
    public function getAll(): array
    {
        return $this->todo_repository->findAll();
    }

    public function update(TodoUpdateRequest $request): TodoUpdateResponse
    {
        // Validate the request data
        $error = [];
        if (empty($request->title)) {
            $error['title'] = "Title is required.";
        } else if (strlen($request->title) < 4) {
            $error['title'] = "Title cannot be less than 4 characters.";

        }
        if (empty($request->deadline)) {
            $error['deadline'] = "Deadline is required.";
        }

        $current_todo = $this->todo_repository->findById($request->id);
        if ($current_todo->title === $request->title && $current_todo->description === $request->description && $current_todo->status === $request->status && $current_todo->deadline === strtotime($request->deadline)) {
            $error['general'] = "No changes were made to the todo.";
        }
        if ($current_todo->user_id !== SessionService::$user_id) {
            $error['general'] = "You are not authorized to update this todo.";
        }
        if ($current_todo->status === "done") {
            $error['general'] = "You cannot update a todo that is already done.";
        }
        if (!$current_todo) {
            $error['general'] = "Todo not found.";
        }
        if ($request->status !== "pending" && $request->status !== "processed" && $request->status !== "done") {
            $error['status'] = "Invalid status value.";
        }

        $request->deadline = strtotime($request->deadline);
        if ($request->deadline < time()) {
            $error['deadline'] = "Deadline cannot be in the past.";
        }
        if ($request->deadline < time() && $request->status !== "done") {
            $error['general'] = "You cannot set a deadline in the past unless the todo is done.";
        }


        $response = new TodoUpdateResponse();
        if (!empty($error)) {
            $response->errors = $error;
            // $response->errors = $error;
            return $response;
        }
        try {
            Database::beginTransaction();
            $this->todo_repository->update($request);
            $todo = new Todo();
            $todo->id = $request->id;
            $todo->title = $request->title;
            $todo->description = $request->description;
            $todo->user_id = SessionService::$user_id;
            $todo->status = $request->status;
            $todo->deadline = (int) $request->deadline;

            $response = new TodoUpdateResponse;
            $response->todo = $todo;
            Database::commitTransaction();
            return $response;
        } catch (\Throwable $th) {
            //throw $th;
            Database::rollbackTransaction();
            $response->errors = ['general' => 'Failed to update todo.'];
            return $response;
        }

    }

    public function delete($id): bool
    {
        return $this->todo_repository->delete($id);
    }


}