<?php

namespace App\Traits;

trait CommentableTrait {
    protected array $comments = [];
    protected int $commentCount = 0;

    public function addComment(string $content, int $userId): int {
        $this->comments[] = ['id' => count($this->comments) + 1, 'userId' => $userId, 'text' => $content];
        $this->commentCount++;
        return count($this->comments);
    }

    public function getComments(): array {
        return $this->comments;
    }

    public function getCommentCount(): int {
        return $this->commentCount;
    }

    public function removeComment(int $commentId): bool {
        if ($this->commentCount > 0) {
            $this->commentCount--;
            return true;
        }
        return false;
    }
}
