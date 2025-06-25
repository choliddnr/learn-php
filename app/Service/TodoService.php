<?php

namespace App\Service;

use App\Core\Database;
use App\Domain\Todo;
use App\Domain\TodoTags;
use App\Model\TodoCreateRequest;
use App\Model\TodoCreateResponse;
use App\Model\TodoUpdateRequest;
use App\Model\TodoUpdateResponse;
use App\Repository\TodoRepository;
use App\Repository\TagRepository;
use App\Repository\TodoTagsRepository;
use App\Service\SessionService;
use PHPUnit\Framework\Constraint\IsEqual;

class TodoService
{
    private TodoRepository $todo_repository;
    private TagRepository $tag_repository;
    private TodoTagsRepository $todo_tags_repository;
    private SessionService $session_service;
    public static string $default_status = 'pending';
    public function __construct()
    {
        $this->todo_repository = new TodoRepository();
        $this->tag_repository = new TagRepository();
        $this->todo_tags_repository = new TodoTagsRepository();
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
        if (empty($request->tags) || !is_array($request->tags) || count($request->tags) === 0) {
            $error['tags'] = "At least 1 tag is required.";
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

            foreach ($request->tags as $tag) {
                $todo_tag = new TodoTags();
                $todo_tag->tag_id = (int)$tag;
                $todo_tag->todo_id = $todo->id;
                $this->todo_tags_repository->save($todo_tag);
            }

            $todo->tags = $this->todo_tags_repository->filterByTodo($todo->id);

            $response = new TodoCreateResponse();
            $response->todo = $todo;
            Database::commitTransaction();
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            $error['general'] = $exception->getMessage();
            $response->errors = $error;
            // $response->errors = ['general' => 'Failed to create todo.'];
            return $response;
        }
        return $response;
    }

    public function getById($id): Todo
    {
        $todo = $this->todo_repository->findById($id);
        $todo->tags = $this->todo_tags_repository->filterByTodo($id);
        return $todo;
        // return $this->todo_repository->findById($id);
    }

    /**
     * @return Todo[]
     */
    public function getAll(): array
    {
        return $this->todo_repository->findAll();
    }

    /**
     * @return Todo[]
     */
    public function getAllWithFilter(array $tags, array $status): array
    {
        return $this->todo_repository->findAllWithFilter($tags, $status);
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
        $is_tags_changed = !$this->todo_tags_repository->isEqual($request->id, $request->tags);
        if ($current_todo->title === $request->title && $current_todo->description === $request->description && $current_todo->status === $request->status && $current_todo->deadline === strtotime($request->deadline) && !$is_tags_changed) {
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

        if (empty($request->tags) || !is_array($request->tags) || count($request->tags) === 0) {
            $error['tags'] = "At least 1 tag is required.";
        }

        foreach ($request->tags as $tag) {
            if (!is_numeric($tag)) {
                $error['tags'] = "Invalid tag ID: " . htmlspecialchars($tag);
                break;
            }
        }

        $response = new TodoUpdateResponse();
        if (!empty($error)) {
            $response->errors = $error;
            // $response->errors = $error;
            return $response;
        }
        try {
            Database::beginTransaction();
            $this->todo_tags_repository->syncTags($request->id, $request->tags);

            $todo = new Todo();
            $todo->id = $request->id;
            $todo->title = $request->title;
            $todo->description = $request->description;
            $todo->tags =  $this->tag_repository->findByMultipleId($request->tags);
            $todo->user_id = SessionService::$user_id;
            $todo->status = $request->status;
            $todo->deadline = (int) $request->deadline;
            $this->todo_repository->update($todo);
            $response = new TodoUpdateResponse;
            $response->todo = $todo;
            Database::commitTransaction();
            return $response;
        } catch (\Throwable $exception) {
            Database::rollbackTransaction();
            $response->errors = ['general' => $exception->getMessage()];
            return $response;
        }
    }

    public function delete($id): bool
    {
        try {
            Database::beginTransaction();
            $this->todo_repository->delete($id);
            $this->todo_tags_repository->syncTags($id, []);

            Database::commitTransaction();
            return true;
        } catch (\Throwable $exception) {
            Database::rollbackTransaction();
            throw $exception->getMessage();
        }
    }
}
