<?php

namespace App\Entities;

class BasicUser extends User
{
    public function getRole(): string
    {
        return 'BasicUser';
    }


    // La limite est de 10 photos.
    public function canUpload(): bool
    {
        if ($this->uploadCount < 10) {
            return true;
        } else {
            return false;
        }
    }
}
