<?php
namespace App\Entities;

/**
 * Classe Album
 * Représente un album photo.
 */
class Album {
    private int $id;
    private string $name;
    private bool $isPublic;
    private ?string $description;
    private int $photoCount;
    private ?string $updatedAt;
    private int $userId; // ID du créateur

    // Constructeur
    public function __construct($id, $name, $isPublic, $description, $photoCount, $updatedAt, $userId) {
        $this->id = $id;
        $this->name = $name;
        $this->isPublic = $isPublic;
        $this->description = $description;
        $this->photoCount = $photoCount;
        $this->updatedAt = $updatedAt;
        $this->userId = $userId;
    }

    // --- Getters ---
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function isPublic() { return $this->isPublic; }
    public function getDescription() { return $this->description; }
    public function getPhotoCount() { return $this->photoCount; }
    public function getUpdatedAt() { return $this->updatedAt; }
    public function getUserId() { return $this->userId; }
}
