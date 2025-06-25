<?php

namespace App\Repository;

use App\Core\Database;
use App\Domain\Tag;


class TagRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function save(Tag $tag): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO tags (title, description, user_id) VALUES (?, ?, ?)");
        $stmt->execute([
            $tag->title,
            $tag->description,
            $tag->user_id,
        ]);

        return $this->pdo->lastInsertId();
    }

    public function findById($id): Tag
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tags WHERE id = ?");
        $stmt->execute([$id]);
        $record = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($record) {
            $tag = new Tag();
            $tag->id = $record['id'];
            $tag->title = $record['title'];
            $tag->description = $record['description'];
            return $tag;
        }

        throw new \Exception("Tag not found.");
    }

    /**
     * @return Tag[]
     */

    public function findAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tags");
        $stmt->execute();
        $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $tags = [];
        foreach ($records as $record) {
            $tag = new Tag();
            $tag->id = $record['id'];
            $tag->title = $record['title'];
            $tag->description = $record['description'];
            $tags[] = $tag;
        }

        return $tags;
    }

    /**
     * @return Tag[]
     */

    public function findByMultipleId(array $ids): array
    {

        // Create placeholders for prepared statement
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $stmt = $this->pdo->prepare("SELECT * FROM tags WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $tags = [];
        foreach ($records as $record) {
            $tag = new Tag();
            $tag->id = $record['id'];
            $tag->title = $record['title'];
            $tag->description = $record['description'];
            $tags[] = $tag;
        }

        return $tags;
    }

    public function update(Tag $tag): bool
    {
        $stmt = $this->pdo->prepare("UPDATE tags SET title = ?, description = ? WHERE id = ?");
        return $stmt->execute([
            $tag->title,
            $tag->description,
            $tag->id,
        ]);
    }

    public function delete($id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM tags WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
