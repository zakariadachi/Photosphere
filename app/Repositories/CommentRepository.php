<?php

namespace App\Repositories;

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Entities/Comment.php';

use App\Entities\Comment;
use PDO;

class CommentRepository {
    private PDO $pdo;
    
    public function __construct() {
        $this->pdo = \Database::getConnection();
    }
    
    public function create(string $content, int $userId, int $postId): ?Comment {
        $sql = "INSERT INTO comments (content, userId, postId, createdAt) 
                VALUES (:content, :userId, :postId, NOW())";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $id = (int)$this->pdo->lastInsertId();
            return new Comment($id, $content, $userId, $postId, false, date('Y-m-d H:i:s'));
        }
        
        return null;
    }
    
    public function findByPost(int $postId): array {
        $sql = "SELECT * FROM comments WHERE postId = :postId AND isArchive = 0 ORDER BY createdAt DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
        $stmt->execute();
        
        $comments = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = $this->hydrate($row);
        }
        
        return $comments;
    }
    
    public function findById(int $id): ?Comment {
        $sql = "SELECT * FROM comments WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }
    
    public function archive(int $commentId): bool {
        $sql = "UPDATE comments SET isArchive = 1, updatedAt = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $commentId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public function delete(int $commentId): bool {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $commentId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public function getCountByPost(int $postId): int {
        $sql = "SELECT COUNT(*) as count FROM comments WHERE postId = :postId AND isArchive = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }
    
    private function hydrate(array $data): Comment {
        return new Comment(
            (int)$data['id'],
            $data['content'],
            (int)$data['userId'],
            (int)$data['postId'],
            (bool)$data['isArchive'],
            $data['createdAt'] ?? null,
            $data['updatedAt'] ?? null
        );
    }
}
