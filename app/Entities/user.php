<?php

namespace App\Entities;

abstract class User
{
    protected int $id;
    protected string $userName;
    protected string $email;
    protected string $password;
    protected ?string $bio;
    protected ?string $adresse;
    protected string $role;
    protected string $createdAt;
    protected ?string $lastLogin;
    protected ?bool $isSuperAdmin;
    protected ?string $moderatorLevel;
    protected ?string $dateDebutAbonnement;
    protected ?string $dateFinAbonnement;
    protected string $status;
    protected int $uploadCount;

    // Le Constructeur
    public function __construct(
        int $id,
        string $userName,
        string $email,
        string $password,
        string $role,
        string $createdAt,
        ?string $bio = null,
        ?string $adresse = null,
        ?string $lastLogin = null,
        ?bool $isSuperAdmin = null,
        ?string $moderatorLevel = null,
        ?string $dateDebutAbonnement = null,
        ?string $dateFinAbonnement = null,
        int $uploadCount = 0,
        string $status = 'active'
    ) {
        $this->id = $id;
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->createdAt = $createdAt;
        $this->bio = $bio;
        $this->adresse = $adresse;
        $this->lastLogin = $lastLogin;
        $this->status = $status;
        
        $this->isSuperAdmin = $isSuperAdmin;
        $this->moderatorLevel = $moderatorLevel;
        $this->dateDebutAbonnement = $dateDebutAbonnement;
        $this->dateFinAbonnement = $dateFinAbonnement;
        $this->uploadCount = $uploadCount;
    }

    // --- GETTERS

    public function getId(): int 
    { 
        return $this->id; 
    }

    public function getUserName(): string 
    { 
        return $this->userName; 
    }

    public function getEmail(): string 
    { 
        return $this->email; 
    }

    public function getPassword(): string 
    { 
        return $this->password; 
    }

    public function getRoleProp(): string 
    { 
        return $this->role; 
    }

    public function getCreatedAt(): string 
    { 
        return $this->createdAt; 
    }

    public function getBio(): ?string 
    { 
        return $this->bio; 
    }

    public function getAdresse(): ?string 
    { 
        return $this->adresse; 
    }

    public function getLastLogin(): ?string 
    { 
        return $this->lastLogin; 
    }

    public function getDateDebutAbonnement(): ?string 
    { 
        return $this->dateDebutAbonnement; 
    }

    public function getDateFinAbonnement(): ?string 
    { 
        return $this->dateFinAbonnement; 
    }

    public function getUploadCount(): int 
    { 
        return $this->uploadCount; 
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function isSuperAdmin(): bool
    {
        if ($this->isSuperAdmin == true) {
            return true;
        } else {
            return false;
        }
    }

    public function getModeratorLevel(): ?string
    {
        return $this->moderatorLevel;
    }

    // --- SETTERS

    public function setUserName(string $name): void 
    { 
        $this->userName = $name; 
    }

    public function setEmail(string $email): void 
    { 
        $this->email = $email; 
    }

    public function setPassword(string $password): void 
    { 
        $this->password = $password; 
    }

    public function setBio(?string $bio): void 
    { 
        $this->bio = $bio; 
    }

    public function setAdresse(?string $adresse): void 
    { 
        $this->adresse = $adresse; 
    }

    public function setRole(string $role): void 
    { 
        $this->role = $role; 
    }

    public function setLastLogin(?string $date): void 
    { 
        $this->lastLogin = $date; 
    }

    public function setUploadCount(int $count): void 
    { 
        $this->uploadCount = $count; 
    }

    // --- METHODES PAR DEFAUT ---

    abstract public function getRole(): string;

    public function canCreatePrivateAlbum(): bool
    {
        return false;
    }

    public function canCreatePost(): bool
    {
        return true;
    }

    public function canCreateAlbum(): bool
    {
        return true;
    }
}
