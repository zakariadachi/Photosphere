<?php
namespace App\Entities;

/**
 * Classe Tag
 * Un mot-clé pour les photos.
 */
class Tag {
    private int $id;
    private string $name;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getName() { return $this->name; }
}
