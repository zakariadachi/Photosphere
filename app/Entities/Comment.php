<?php
namespace App\Entities;

/**
 * Classe Comment
 * Un commentaire sur un post.
 */
class Comment {
    // En mode débutant, on peut être plus souple sur les types ou utiliser ?
    private int $id;
    private string $content;
    private bool $isArchive;
    private string $createdAt;
    private ?string $updatedAt; // Le ? permet d'accepter null
    private int $userId;
    private int $postId;

    // Constructeur simple
    public function __construct($id, $content, $isArchive, $createdAt, $updatedAt, $userId, $postId) {
        $this->id = $id;
        $this->content = $content;
        $this->isArchive = $isArchive;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt; // Peut être null
        $this->userId = $userId;
        $this->postId = $postId;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getContent() { return $this->content; }
    public function isArchive() { return $this->isArchive; }
    public function getCreatedAt() { return $this->createdAt; } 
    public function getUpdatedAt() { return $this->updatedAt; }
    public function getUserId() { return $this->userId; }
    public function getPostId() { return $this->postId; }
}
