<?php

declare(strict_types=1);

namespace App\Entities;

class ProUser extends User
{

    public function getRole(): string
    {
        return 'proUser';
    }

    public function hasActiveSubscription(): bool
    {
        if ($this->dateFinAbonnement == null) {
            return false;
        }
        
        $dateFin = strtotime($this->dateFinAbonnement);
        $dateDuJour = time();
        
        if ($dateFin > $dateDuJour) {
            return true;
        } else {
            return false;
        }
    }

    public function canCreatePrivateAlbum(): bool
    {
        return true;
    }
}
