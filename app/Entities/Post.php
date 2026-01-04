<?php
namespace App\Entities;

/**
 * Classe Post
 * C'est une photo postée sur le site.
 */
class Post {
    private int $id;
    private ?string $title;
    private ?string $description;
    private string $imageLink;
    private ?int $imageSize;
    private ?string $dimensions;
    private ?string $status;
    private int $viewCount;
    private ?string $publishedAt;
    private string $createdAt;
    private ?string $updatedAt;
    private int $userId;
    private ?int $albumId;

    // Constructeur
    public function __construct(
        $id, $title, $description, $imageLink, 
        $imageSize, $dimensions, $status, $viewCount, 
        $publishedAt, $createdAt, $updatedAt, $userId, $albumId
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->imageLink = $imageLink;
        $this->imageSize = $imageSize;
        $this->dimensions = $dimensions;
        $this->status = $status;
        $this->viewCount = $viewCount;
        $this->publishedAt = $publishedAt;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->userId = $userId;
        $this->albumId = $albumId;
    }

    // --- Getters Simples ---
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getImageLink() { return $this->imageLink; }
    public function getStatus() { return $this->status; }
    public function getViewCount() { return $this->viewCount; }
    public function getUserId() { return $this->userId; }
    public function getAlbumId() { return $this->albumId; }
}
