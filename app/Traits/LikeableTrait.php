<?php

namespace App\Traits;

trait LikeableTrait {
    protected int $likeCount = 0;
    protected array $likedBy = [];

    public function addLike(int $userId): bool {
        if (!$this->isLikedBy($userId)) {
            $this->likedBy[] = $userId;
            $this->likeCount++;
            return true;
        }
        return false;
    }

    public function removeLike(int $userId): bool {
        if ($this->isLikedBy($userId)) {
            $this->likedBy = array_values(array_diff($this->likedBy, [$userId]));
            $this->likeCount--;
            return true;
        }
        return false;
    }

    public function isLikedBy(int $userId): bool {
        return in_array($userId, $this->likedBy);
    }

    public function getLikeCount(): int {
        return $this->likeCount;
    }

    public function getLikedBy(): array {
        return $this->likedBy;
    }
}
