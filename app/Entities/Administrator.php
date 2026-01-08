<?php

namespace App\Entities;

class Administrator extends User
{
    public function getRole(): string
    {
        return 'Administrator';
    }

    public function hasFullAccess(): bool
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
