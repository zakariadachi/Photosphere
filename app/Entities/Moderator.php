<?php

namespace App\Entities;

/**
 * Classe Moderator
 * Peut modérer le site mais pas créer de contenu personnel.
 */
class Moderator extends User
{
    public function getRole(): string
    {
        return 'Moderator';
    }

    // Méthode spécifique pour savoir s'il peut modérer
    public function canModerate(): bool
    {
        return true;
    }

    // --- RESTRICTIONS ---

    // Un modérateur ne crée pas d'album privé
    public function canCreatePrivateAlbum(): bool
    {
        return false;
    }

    // Il ne poste pas de photos
    public function canCreatePost(): bool
    {
        return false;
    }

    // Il ne crée pas d'albums
    public function canCreateAlbum(): bool
    {
        return false;
    }
}
