<?php

namespace App\Repositories;

require_once __DIR__ . '/RepositoryInterface.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Entities/Tag.php'; 

use App\Entities\Tag;
use PDO;

class TagRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = \Database::getConnection();
    }

    public function findById(int $id): ?Tag {
        $sql = "SELECT * FROM tags WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? new Tag((int)$row['id'], $row['name'], $row['slug'], (int)$row['photoCount']) : null;
        }
        return null;
    }

    public function findByName(string $name): ?Tag {
        $sql = "SELECT * FROM tags WHERE name = :name OR slug = :slug";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":name", $name);
        $stmt->bindValue(":slug", Tag::normalizeSlug($name));
        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? new Tag((int)$row['id'], $row['name'], $row['slug'], (int)$row['photoCount']) : null;
        }
        return null;
    }

    public function getPopularTags(int $limit = 10): array {
        $sql = "SELECT * FROM tags ORDER BY photoCount DESC LIMIT :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $tags = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tags[] = new Tag((int)$row['id'], $row['name'], $row['slug'], (int)$row['photoCount']);
        }
        return $tags;
    }

    public function searchTags(string $query, int $limit = 20): array {
        $sql = "SELECT * FROM tags WHERE name LIKE :query OR slug LIKE :query ORDER BY photoCount DESC LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':query', '%' . $query . '%');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $tags = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tags[] = new Tag((int)$row['id'], $row['name'], $row['slug'], (int)$row['photoCount']);
        }
        return $tags;
    }

    public function getPhotosByTag(string $tagName): array {
        $sql = "SELECT p.* 
                FROM post p
                JOIN post_tag pt ON p.id = pt.post_id
                JOIN tags t ON pt.tag_id = t.id
                WHERE t.name = :name
                ORDER BY p.created_at DESC";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":name", $tagName);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTagStats(string $tagName): array {
        $sql = "SELECT t.*, 
                COUNT(DISTINCT pt.post_id) as totalPhotos,
                COUNT(DISTINCT p.userId) as totalUsers
                FROM tags t
                LEFT JOIN post_tag pt ON t.id = pt.tag_id
                LEFT JOIN post p ON pt.post_id = p.id
                WHERE t.name = :name
                GROUP BY t.id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':name', $tagName);
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return [];
        }
        
        return [
            'tag' => new Tag((int)$data['id'], $data['name'], $data['slug'], (int)$data['photoCount']),
            'totalPhotos' => (int)$data['totalPhotos'],
            'totalUsers' => (int)$data['totalUsers']
        ];
    }
    
    public function mergeTags(string $fromTagName, string $toTagName): bool {
        try {
            $this->pdo->beginTransaction();
            
            // Find source tag
            $stmtFrom = $this->pdo->prepare("SELECT id FROM tags WHERE name = :name");
            $stmtFrom->execute([':name' => $fromTagName]);
            $fromTagData = $stmtFrom->fetch(PDO::FETCH_ASSOC);
            
            if (!$fromTagData) {
                $this->pdo->rollBack();
                return false;
            }
            
            $fromTagId = (int)$fromTagData['id'];
            
            // Find or create destination tag
            $stmtTo = $this->pdo->prepare("SELECT id FROM tags WHERE name = :name");
            $stmtTo->execute([':name' => $toTagName]);
            $toTagData = $stmtTo->fetch(PDO::FETCH_ASSOC);
            
            if (!$toTagData) {
                // Create destination tag
                $insertStmt = $this->pdo->prepare("INSERT INTO tags (name) VALUES (:name)");
                $insertStmt->execute([':name' => $toTagName]);
                $toTagId = (int)$this->pdo->lastInsertId();
            } else {
                $toTagId = (int)$toTagData['id'];
            }
            
            // Update post_tag entries
            $updateStmt = $this->pdo->prepare(
                "UPDATE post_tag SET tag_id = :toTagId 
                 WHERE tag_id = :fromTagId 
                 AND post_id NOT IN (
                     SELECT post_id FROM (SELECT post_id FROM post_tag WHERE tag_id = :toTagId2) AS tmp
                 )"
            );
            $updateStmt->execute([
                ':toTagId' => $toTagId,
                ':fromTagId' => $fromTagId,
                ':toTagId2' => $toTagId
            ]);
            
            // Delete remaining associations with source tag
            $deleteStmt = $this->pdo->prepare("DELETE FROM post_tag WHERE tag_id = :fromTagId");
            $deleteStmt->execute([':fromTagId' => $fromTagId]);
            
            // Delete source tag
            $deleteTagStmt = $this->pdo->prepare("DELETE FROM tags WHERE id = :id");
            $deleteTagStmt->execute([':id' => $fromTagId]);
            
            // Recount photoCount for destination tag
            $recountStmt = $this->pdo->prepare(
                "UPDATE tags SET photoCount = (
                    SELECT COUNT(*) FROM post_tag WHERE tag_id = :tagId
                ) WHERE id = :tagId2"
            );
            $recountStmt->execute([':tagId' => $toTagId, ':tagId2' => $toTagId]);
            
            $this->pdo->commit();
            return true;
            
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}
