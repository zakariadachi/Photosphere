<?php

namespace App\Entities;

class Tag {
    private int $id;
    private string $name;
    private string $slug;
    private int $photoCount;

    public function __construct(int $id, string $name, ?string $slug = null, int $photoCount = 0) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug ?? self::normalizeSlug($name);
        $this->photoCount = $photoCount;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }
    
    public function getSlug(): string {
        return $this->slug;
    }
    
    public function getPhotoCount(): int {
        return $this->photoCount;
    }
    
    public static function normalizeSlug(string $name): string {
        return strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $name), '-'));
    }
}
