<?php

namespace App\Entities;

/**
 * Classe BasicUser
 * C'est l'utilisateur standard du site.
 */
class BasicUser extends User
{
    // Méthode obligatoire : retourne le nom du rôle
    public function getRole(): string
    {
        return 'BasicUser';
    }

    // Vérifie si l'utilisateur peut encore uploader
    // La limite est de 10 photos.
    public function canUpload(): bool
    {
        if ($this->uploadCount < 10) {
            return true; // OK, il peut uploader
        } else {
            return false; // Non, limite atteinte
        }
    }
}
