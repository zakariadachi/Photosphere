<?php

namespace App\Entities;

/**
 * Classe ProUser
 * Utilisateur payant avec des avantages.
 */
class ProUser extends User
{
    public function getRole(): string
    {
        return 'ProUser';
    }

    // Vérifie si l'abonnement est toujours valide
    public function hasActiveSubscription(): bool
    {
        // Si pas de date de fin, pas d'abonnement
        if ($this->dateFinAbonnement == null) {
            return false;
        }
        
        // On compare la date de fin avec la date d'aujourd'hui
        $dateFin = strtotime($this->dateFinAbonnement);
        $dateDuJour = time();
        
        if ($dateFin > $dateDuJour) {
            return true; // Encore abo
        } else {
            return false; // Abo fini
        }
    }

    // Le Pro peut créer des albums privés
    public function canCreatePrivateAlbum(): bool
    {
        return true;
    }
}
