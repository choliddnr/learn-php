<?php

namespace App\Repository;

use App\Core\Database;
use App\Domain\TodoTags;
use App\Domain\Tag;

class TodoTagsRepository
{
    private \PDO $pdo;


    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function save(TodoTags $todo_tags): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO todo_tags (tag_id, todo_id) VALUES (?, ?)");
        $stmt->execute([
            $todo_tags->tag_id,
            $todo_tags->todo_id,
        ]);
        return $this->pdo->lastInsertId();
    }

    /**
     * @return  Tag[]
     */
    public function filterByTodo($id): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM todo_tags INNER JOIN tags ON todo_tags.tag_id = tags.id WHERE todo_id = ?");
        $stmt->execute([$id]);
        $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $tags = [];
        foreach ($records as $record) {
            $tag = new Tag();
            $tag->id = $record['id'];
            $tag->title = $record['title'];
            $tags[] = $tag;
        }
        return $tags;
    }

    /**
     * @return TodoTags[]
     */
    public function filterByTag($id): array
    {
        // $stmt = $this->pdo->prepare("SELECT * FROM todo_tags");
        // $stmt->execute();

        $stmt = $this->pdo->prepare("SELECT * FROM todo_tags WHERE user_id = ?");
        $stmt->execute([$id]);
        $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $todo_tags = [];

        foreach ($records as $record) {
            $todo = new TodoTags();
            $todo->todo_id = $record['todo_id'];
            $todo->tag_id = $record['tag_id'];
            $todo_tags[] = $todo;
        }

        return $todo_tags;
    }

    public function syncTags(int $todo_id, array $new_tag_ids)
    {
        $existing_tags = $this->filterByTodo($todo_id);
        $existing_tag_ids = array_map(fn($tag) => $tag->id, $existing_tags);

        $toDelete = array_diff($existing_tag_ids, $new_tag_ids);
        $toAdd = array_diff($new_tag_ids, $existing_tag_ids);


        foreach ($toDelete as $tagId) {
            $todo_tags = new TodoTags();
            $todo_tags->todo_id = $todo_id;
            $todo_tags->tag_id = $tagId;
            $this->deleteOne($todo_tags);
        }

        foreach ($toAdd as $tagId) {
            $todo_tags = new TodoTags();
            $todo_tags->todo_id = $todo_id;
            $todo_tags->tag_id = $tagId; // Assuming you have a session service to get the user ID
            $this->save($todo_tags);
        }
    }

    public function isEqual(int $todo_id, array $new_tag_ids): bool
    {
        $existing_tags = $this->filterByTodo($todo_id);
        $existing_tag_ids = array_map(fn($tag) => $tag->id, $existing_tags);

        $toDelete = array_diff($existing_tag_ids, $new_tag_ids);
        $toAdd = array_diff($new_tag_ids, $existing_tag_ids);
        if (empty($toDelete) && empty($toAdd)) {
            return true; // No changes needed
        }
        return false;
    }


    public function delete($id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM todo_tags WHERE todo_id = ?");
        return $stmt->execute([$id]);
    }

    public function deleteOne(TodoTags $todo_tags): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM todo_tags WHERE todo_id = ? AND tag_id = ?");
        return $stmt->execute([$todo_tags->todo_id, $todo_tags->tag_id]);
    }
}
