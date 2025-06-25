<?php

namespace App\Controller;


use App\Core\Controller;
use App\Core\Database;
use App\Model\TagCreateRequest;
use App\Model\TagUpdateRequest;
use App\Service\TagService;
use DateTime;

class TagController extends Controller
{
    // protected $todos;
    private TagService $tag_service;
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
        $this->tag_service = new TagService();
    }
    public function index()
    {
        return $this->view('tag/index', ['tags' => $this->tag_service->getAll()]);
    }

    public function getCreateForm()
    {
        $data = [
            'errors' => $this->getFlashData('errors'),
            'form' => $this->getFlashData('form'),
        ];

        return $this->view('tag/createform', $data);
    }
    public function getUpdateForm($id)
    {
        $data = [
            'id' => $id,
            'form' => $this->getFlashData('form') ?? $this->tag_service->getById($id),
            'errors' => $this->getFlashData('errors'),
        ];
        return $this->view('tag/updateform', $data);
    }

    public function showtag($id)
    {
        $flash_data = $this->getFlashData('tag');
        $tag = $flash_data ?? $this->tag_service->getById($id);
        return $this->view('tag/show', ['tag' => $tag]);
    }

    public function createTag()
    {
        $request = new TagCreateRequest($_POST['title'], $_POST['description']);

        $response = $this->tag_service->create($request);
        if (!empty($response->errors)) {
            $this->setFlashData('errors', $response->errors);
            $this->setFlashData('form', $request);
            $this->redirect('/tag/create');
        }
        $this->setFlashData('tag', $response->tag);
        $this->redirect('/tag/' . $response->tag->id);
    }
    public function deletetag($id)
    {
        return $this->tag_service->delete($id);
    }

    public function updatetag($id)
    {
        $request = new TagUpdateRequest($id, $_POST['title'], $_POST['description']);

        $response = $this->tag_service->update($request);

        if (!empty($response->errors)) {
            $this->setFlashData('errors', $response->errors);
            $this->setFlashData('form', $request);
            $this->redirect('/tag/' . $id . '/update');
            return;
        }
        $this->setFlashData('tag', $response->tag);
        $this->redirect('/tag/' . $response->tag->id);
    }

    public function delete($id)
    {
        $this->tag_service->delete($id);
        $this->redirect('/tag');
    }
}
