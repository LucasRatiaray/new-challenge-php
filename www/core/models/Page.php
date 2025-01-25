<?php

namespace Core\Models;

use Core\Utils\Database;
use PDO;

class Page
{
    public function getAll(): array
    {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM pages WHERE is_active = TRUE ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM pages WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM pages WHERE slug = :slug AND is_active = TRUE");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }


    public function create(array $data): bool
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            INSERT INTO pages (title, slug, content, is_active) 
            VALUES (:title, :slug, :content, :is_active)
        ");
        return $stmt->execute([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'is_active' => $data['is_active'] ?? true
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            UPDATE pages 
            SET title = :title, slug = :slug, content = :content, is_active = :is_active, updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'is_active' => $data['is_active'] ?? true
        ]);
    }

    public function delete(int $id): bool
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM pages WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
