<?php

declare(strict_types=1);

namespace App\Traits;

trait TaggableTrait
{
    protected array $tags = [];

    public function addTag(string $tag): void
    {
        $normalizedTag = $this->normalizeTag($tag);
        
        if ($this->hasTag($normalizedTag) == false) {
            $this->tags[] = $normalizedTag;
        } else {
        }
    }

    public function removeTag(string $tag): void
    {
        $normalizedTag = $this->normalizeTag($tag);
        
        // Cherche l'index du tag dans le tableau
        $key = array_search($normalizedTag, $this->tags);
        
        if ($key !== false) {
            unset($this->tags[$key]);
            
            $this->tags = array_values($this->tags);
        } else {
           
        }
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function hasTag(string $tag): bool
    {
        $normalizedTag = $this->normalizeTag($tag);
        
        if (in_array($normalizedTag, $this->tags)) {
            return true;
        } else {
            return false;
        }
    }

    public function clearTags(): void
    {
        $this->tags = [];
    }

    protected function normalizeTag(string $tag): string
    {
        return strtolower(trim($tag));
    }
    public function hasAllTags(array $tags): bool
    {
        foreach ($tags as $tag) {
            if ($this->hasTag($tag) == false) {
                return false;
            }
        }
        
        return true;
    }

    public function hasAnyTag(array $tags): bool
    {
        foreach ($tags as $tag) {
            if ($this->hasTag($tag) == true) {
                return true;
            }
        }
        
        return false;
    }
}
