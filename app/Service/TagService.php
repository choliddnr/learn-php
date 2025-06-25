<?php

namespace  App\Service;

use App\Core\Database;
use App\Domain\Tag;
use App\Model\TagCreateRequest;
use App\Model\TagCreateResponse;
use App\Model\TagUpdateRequest;
use App\Model\TagUpdateResponse;
use App\Repository\TagRepository;
use App\Service\SessionService;

class TagService
{
    private TagRepository $tag_repository;
    private SessionService $session_service;

    public function __construct()
    {
        $this->tag_repository = new TagRepository();
        $this->session_service = new SessionService();
    }

    public function create(TagCreateRequest $request): TagCreateResponse
    {
        // Validate the request data

        $error = [];
        if (empty($request->title)) {
            $error['title'] = "Title is required.";
        } else if (strlen($request->title) < 4) {
            $error['title'] = "Title cannot be less than 4 characters.";
        } else if (strlen($request->title) > 100) {
            $error['title'] = "Title cannot be more than 100 characters.";
        }
        $response = new TagCreateResponse();

        if (!empty($error)) {
            $response->errors = $error;
            return $response;
        }

        try {
            Database::beginTransaction();
            $tag = new Tag();
            $tag->title = $request->title;
            $tag->description = $request->description ?? "";
            $tag->user_id = SessionService::$user_id;

            $id = $this->tag_repository->save($tag);
            $tag->id = $id;
            $response->tag = $tag;
            Database::commitTransaction();
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            $error['general'] = $exception->getMessage();
            $response->errors = $error;
            return $response;
        }
    }

    public function getById($id): Tag
    {
        return $this->tag_repository->findById($id);
    }

    /**
     * @return Tag[]
     */
    public function getAll(): array
    {
        return $this->tag_repository->findAll();
    }

    public function update(TagUpdateRequest $request): TagUpdateResponse
    {
        // Validate the request data
        $error = [];
        if (empty($request->title)) {
            $error['title'] = "Title is required.";
        } else if (strlen($request->title) < 4) {
            $error['title'] = "Title cannot be less than 4 characters.";
        }

        $response = new TagUpdateResponse();
        if (!empty($error)) {
            $response->errors = $error;
            return $response;
        }

        try {
            Database::beginTransaction();
            $tag = new Tag();
            $tag->id = $request->id;
            $tag->title = $request->title;
            $tag->description = $request->description ?? "";
            $tag->user_id = SessionService::$user_id;

            $this->tag_repository->update($tag);
            Database::commitTransaction();
            $response->tag = $tag;
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            $error['general'] = $exception->getMessage();
            $response->errors = $error;
            return $response;
        }
    }

    public function delete($id): bool
    {
        return $this->tag_repository->delete($id);
    }

    public function getTagsByTodoIds(array $tagIds): array
    {

        return $this->tag_repository->findByMultipleId($tagIds);
    }
}
