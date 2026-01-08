<?php

declare(strict_types=1);

namespace App\Entities;

class Moderator extends User
{
    public function getRole(): string
    {
        return 'Modérateur';
    }

    public function canModerate(): bool
    {
        return true;
    }

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
