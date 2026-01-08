<?php

namespace App\Entities;

class Like {
    private int $userId;
    private int $postId;
    private ?string $createdAt;
    
    public function __construct(
        int $userId,
        int $postId,
        ?string $createdAt = null
    ) {
        $this->userId = $userId;
        $this->postId = $postId;
        $this->createdAt = $createdAt;
    }
    
    // Getters
    public function getUserId(): int { return $this->userId; }
    public function getPostId(): int { return $this->postId; }
    public function getCreatedAt(): ?string { return $this->createdAt; }
    
    public function toArray(): array {
        return [
            'userId' => $this->userId,
            'postId' => $this->postId,
            'createdAt' => $this->createdAt
        ];
    }
}
