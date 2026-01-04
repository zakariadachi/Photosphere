<?php
namespace App\Entities;

/**
 * Classe Like
 * Quand quelqu'un aime un post.
 */
class Like {
    private int $userId;
    private int $postId;
    private string $createdAt;

    public function __construct($userId, $postId, $createdAt) {
        $this->userId = $userId;
        $this->postId = $postId;
        $this->createdAt = $createdAt;
    }

    public function getUserId() { return $this->userId; }
    public function getPostId() { return $this->postId; }
}
