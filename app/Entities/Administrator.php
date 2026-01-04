<?php

namespace App\Entities;

/**
 * Classe Administrator
 * Le chef du site, il a tous les droits sauf créer du contenu comme un user normal.
 */
class Administrator extends User
{
    public function getRole(): string
    {
        return 'Administrator';
    }

    // Vérifie si c'est un Super Admin
    public function isSuperAdmin(): bool
    {
        if ($this->isSuperAdmin == true) {
            return true;
        } else {
            return false;
        }
    }

    public function hasFullAccess(): bool
    {
        return true;
    }

    // --- RESTRICTIONS ---

    public function canCreatePrivateAlbum(): bool
    {
        return false;
    }

    public function canCreatePost(): bool
    {
        return false;
    }

    public function canCreateAlbum(): bool
    {
        return false;
    }
}
