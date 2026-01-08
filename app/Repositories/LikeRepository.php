<?php

namespace App\Repositories;

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Entities/Like.php';

use App\Entities\Like;
use PDO;

class LikeRepository {
    private PDO $pdo;
    
    public function __construct() {
        $this->pdo = \Database::getConnection();
    }
    
    public function addLike(int $userId, int $postId): bool {
        // Check if already liked
        if ($this->hasUserLiked($userId, $postId)) {
            return false;
        }
        
        $sql = "INSERT INTO likes (userId, postId, createdAt) VALUES (:userId, :postId, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public function removeLike(int $userId, int $postId): bool {
        $sql = "DELETE FROM likes WHERE userId = :userId AND postId = :postId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public function hasUserLiked(int $userId, int $postId): bool {
        $sql = "SELECT COUNT(*) as count FROM likes WHERE userId = :userId AND postId = :postId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'] > 0;
    }
    
    public function getLikesByPost(int $postId): array {
        $sql = "SELECT * FROM likes WHERE postId = :postId ORDER BY createdAt DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
        $stmt->execute();
        
        $likes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $likes[] = new Like(
                (int)$row['userId'],
                (int)$row['postId'],
                $row['createdAt'] ?? null
            );
        }
        
        return $likes;
    }
    
    public function getCountByPost(int $postId): int {
        $sql = "SELECT COUNT(*) as count FROM likes WHERE postId = :postId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }
    
    public function getLikesByUser(int $userId): array {
        $sql = "SELECT * FROM likes WHERE userId = :userId ORDER BY createdAt DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        $likes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $likes[] = new Like(
                (int)$row['userId'],
                (int)$row['postId'],
                $row['createdAt'] ?? null
            );
        }
        
        return $likes;
    }
}
