<?php

declare(strict_types=1);

namespace App\Entities;

/**
 * Classe BasicUser représentant un utilisateur standard.
 */
class BasicUser extends User
{
    /**
     * Retourne le libellé du rôle.
     */
    public function getRole(): string
    {
        return 'Utilisateur Standard';
    }

    /**
     * Vérifie si l'utilisateur peut encore envoyer des photos.
     * La limite est de 10 photos pour un utilisateur standard.
     */
    public function canUpload(): bool
    {
        if ($this->uploadCount < 10) {
            return true;
        } else {
            return false;
        }
    }
}
