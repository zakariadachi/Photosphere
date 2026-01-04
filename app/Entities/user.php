<?php

namespace App\Entities;

/**
 * Classe User (Utilisateur)
 * C'est la classe parente pour tous les types d'utilisateurs.
 * Elle est "abstraite" car on ne peut pas créer juste un "User", il faut choisir un type précis.
 */
abstract class User
{
    // Propriétés de l'utilisateur (comme dans la base de données)
    protected int $id;
    protected string $userName;
    protected string $email;
    protected string $password;
    protected ?string $bio;      // Le ? veut dire que ça peut être vide (null)
    protected ?string $adresse;
    protected string $role;
    protected string $createdAt; // Date de création
    protected ?string $lastLogin;
    
    // Champs spécifiques pour certains types
    protected ?bool $isSuperAdmin;
    protected ?string $moderatorLevel;
    protected ?string $dateDebutAbonnement;
    protected ?string $dateFinAbonnement;
    protected int $uploadCount;

    // Le Constructeur : c'est la fonction qui est appelée quand on fait "new User(...)"
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
        int $uploadCount = 0
    ) {
        // On remplit les propriétés de l'objet avec les valeurs données
        $this->id = $id;
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->createdAt = $createdAt;
        $this->bio = $bio;
        $this->adresse = $adresse;
        $this->lastLogin = $lastLogin;
        
        // Champs spéciaux
        $this->isSuperAdmin = $isSuperAdmin;
        $this->moderatorLevel = $moderatorLevel;
        $this->dateDebutAbonnement = $dateDebutAbonnement;
        $this->dateFinAbonnement = $dateFinAbonnement;
        $this->uploadCount = $uploadCount;
    }

    // --- GETTERS (Pour récupérer les infos) ---

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

    public function getIsSuperAdmin(): ?bool 
    { 
        return $this->isSuperAdmin; 
    }

    public function getModeratorLevel(): ?string 
    { 
        return $this->moderatorLevel; 
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

    // --- SETTERS (Pour modifier les infos) ---

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

    public function setIsSuperAdmin(?bool $val): void 
    { 
        $this->isSuperAdmin = $val; 
    }

    public function setUploadCount(int $count): void 
    { 
        $this->uploadCount = $count; 
    }

    // --- METHODES PAR DEFAUT ---

    // Chaque enfant devra dire quel est son rôle exact (BasicUser, Admin...)
    abstract public function getRole(): string;

    // Par défaut, personne ne peut créer d'album privé (sauf si on change ça dans l'enfant)
    public function canCreatePrivateAlbum(): bool
    {
        return false;
    }

    // Par défaut, tout le monde peut créer un post
    public function canCreatePost(): bool
    {
        return true;
    }

    // Par défaut, tout le monde peut créer un album
    public function canCreateAlbum(): bool
    {
        return true;
    }
}
