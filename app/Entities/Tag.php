<?php

declare(strict_types=1);

namespace App\Entities;

class Tag {
    private int $id;
    private string $name;
    private string $slug;
    private int $photoCount;

    // Constructeur
    public function __construct(int $id, string $name, ?string $slug = null, int $photoCount = 0) {
        $this->id = $id;
        $this->name = $name;
        
        if ($slug == null) {
            $this->slug = self::normalizeSlug($name);
        } else {
            $this->slug = $slug;
        }
        
        $this->photoCount = $photoCount;
    }

    // --- Getters
    
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
