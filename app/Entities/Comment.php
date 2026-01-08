<?php

declare(strict_types=1);

namespace App\Entities;

class Comment {
    private int $id;
    private string $content;
    private bool $isArchive;
    private ?string $createdAt;
    private ?string $updatedAt;
    private int $userId;
    private int $postId;
    
    // Constructeur
    public function __construct(
        int $id,
        string $content,
        int $userId,
        int $postId,
        bool $isArchive = false,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->content = $content;
        $this->userId = $userId;
        $this->postId = $postId;
        $this->isArchive = $isArchive;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
    
    // --- Getters
    
    public function getId(): int { 
        return $this->id; 
    }
    
    public function getContent(): string { 
        return $this->content; 
    }
    
    public function isArchived(): bool { 
        return $this->isArchive; 
    }
    
    public function getCreatedAt(): ?string { 
        return $this->createdAt; 
    }
    
    public function getUpdatedAt(): ?string { 
        return $this->updatedAt; 
    }
    
    public function getUserId(): int { 
        return $this->userId; 
    }
    
    public function getPostId(): int { 
        return $this->postId; 
    }
    
    // --- Setters
    
    public function setContent(string $content): void { 
        $this->content = $content; 
    }
    
    public function archive(): void { 
        $this->isArchive = true; 
    }
    
    // Conversion en tableau
    public function toArray(): array {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'isArchive' => $this->isArchive,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'userId' => $this->userId,
            'postId' => $this->postId
        ];
    }
}
