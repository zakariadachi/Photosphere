<?php

declare(strict_types=1);

namespace App\Entities;

class Album {
    private int $id;
    private string $name;
    private bool $isPublic;
    private ?string $description;
    private int $photoCount;
    private ?string $updatedAt;
    private int $userId;

    // Constructeur
    public function __construct(
        int $id, 
        string $name, 
        bool $isPublic, 
        ?string $description, 
        int $photoCount, 
        ?string $updatedAt, 
        int $userId
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->isPublic = $isPublic;
        $this->description = $description;
        $this->photoCount = $photoCount;
        $this->updatedAt = $updatedAt;
        $this->userId = $userId;
    }

    // --- Getters
    
    public function getId(): int { 
        return $this->id; 
    }
    
    public function getName(): string { 
        return $this->name; 
    }
    
    public function isPublic(): bool { 
        return $this->isPublic; 
    }
    
    public function getDescription(): ?string { 
        return $this->description; 
    }
    
    public function getPhotoCount(): int { 
        return $this->photoCount; 
    }
    
    public function getUpdatedAt(): ?string { 
        return $this->updatedAt; 
    }
    
    public function getUserId(): int { 
        return $this->userId; 
    }
}
