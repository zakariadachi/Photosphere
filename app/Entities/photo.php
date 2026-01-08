<?php

declare(strict_types=1);

namespace App\Entities;

require_once __DIR__ . '/../Interfaces/Taggable.php';
require_once __DIR__ . '/../Interfaces/Commentable.php';
require_once __DIR__ . '/../Interfaces/Likeable.php';
require_once __DIR__ . '/../Traits/TaggableTrait.php';
require_once __DIR__ . '/../Traits/TimestampableTrait.php';

use App\Interfaces\Taggable;
use App\Interfaces\Commentable;
use App\Interfaces\Likeable;
use App\Traits\TaggableTrait;
use App\Traits\TimestampableTrait;

class Photo implements Taggable, Commentable, Likeable {
    use TaggableTrait;
    use TimestampableTrait;
    
    private int $id;
    private ?string $title;
    private ?string $description;
    private string $imageLink;
    private ?int $imageSize;
    private ?string $dimensions;
    private ?string $status;
    private int $viewCount;
    private ?string $publishedAt;
    private int $userId;
    private ?int $albumId;        
    protected int $likeCount = 0;
    protected int $commentCount = 0;
    protected bool $isPublic = true;
    
    private array $likedByUsers = [];
    private array $comments = [];


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
        
        // Initialize timestamps
        $this->setCreatedAt($createdAt);
        $this->setUpdatedAt($updatedAt);
        
        $this->userId = $userId;
        $this->albumId = $albumId;
    }

    // --- Getters de base
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getImageLink() { return $this->imageLink; }
    public function getStatus() { return $this->status; }
    public function getViewCount() { return $this->viewCount; }
    public function getPublishedAt() { return $this->publishedAt; }
    public function getUserId() { return $this->userId; }
    public function getAlbumId() { return $this->albumId; }


    // --- IMPLEMENTATION COMMENTABLE ---

    public function addComment(string $content, int $userId): int
    {
        $newCommentId = count($this->comments) + 1;
        $this->comments[] = [
            'id' => $newCommentId,
            'content' => $content,
            'userId' => $userId
        ];
        $this->commentCount = $this->commentCount + 1;
        return $newCommentId;
    }

    public function removeComment(int $commentId): bool
    {
        foreach ($this->comments as $key => $comment) {
            if ($comment['id'] === $commentId) {
                unset($this->comments[$key]);
                $this->comments = array_values($this->comments);
                $this->commentCount = $this->commentCount - 1;
                return true;
            }
        }
        return false;
    }

    public function getComments(): array
    {
        return $this->comments;
    }

    public function getCommentCount(): int
    {
        return $this->commentCount;
    }

    // IMPLEMENTATION LIKEABLE 

    public function addLike(int $userId): bool
    {
        if ($this->isLikedBy($userId) == true) {
            return false;
        } else {
            $this->likedByUsers[] = $userId;
            $this->likeCount = $this->likeCount + 1;
            return true;
        }
    }


    public function isLikedBy(int $userId): bool
    {
        if (in_array($userId, $this->likedByUsers)) {
            return true;
        } else {
            return false;
        }
    }

    public function getLikeCount(): int
    {
        return $this->likeCount;
    }

    public function removeLike(int $userId): bool
    {
        $key = array_search($userId, $this->likedByUsers);
        if ($key !== false) {
            unset($this->likedByUsers[$key]);
            $this->likedByUsers = array_values($this->likedByUsers);
            $this->likeCount = $this->likeCount - 1;
            return true;
        }
        return false;
    }

    public function getLikedBy(): array
    {
        return $this->likedByUsers;
    }
    
    protected function loadTagsFromDatabase(): void
    {
    }
}
