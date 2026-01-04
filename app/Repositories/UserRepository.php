<?php

namespace App\Repositories;

require_once __DIR__ . '/RepositoryInterface.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Entities/User.php';
require_once __DIR__ . '/../Services/UserFactory.php';

use App\Entities\User;
use App\Services\UserFactory;
use PDO;

class UserRepository implements RepositoryInterface
{
    private $pdo;

    public function __construct()
    {
        // connexion db
        $this->pdo = \Database::getConnection();
    }

    // trouver tous les users
    public function findAll()
    {
        $sql = "SELECT * FROM user";
        $stmt = $this->pdo->query($sql);
        $usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($usersData as $data) {
            $users[] = $this->hydrate($data);
        }

        return $users;
    }

    // trouver par id
    public function findById($id)
    {
        $sql = "SELECT * FROM user WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return $this->hydrate($data);
        } else {
            return null;
        }
    }

    // sauvegarder user
    public function save($user)
    {
        if ($user->getId() == 0 || $user->getId() == null) {
            return $this->create($user);
        } else {
            return $this->update($user);
        }
    }

    // creer nouveau
    private function create($user)
    {
        $sql = "INSERT INTO user (user_name, email, password, role, created_at, bio, adresse, last_login, isSuperAdmin, moderator_level, date_debut_abonnement, date_fin_abonnement, uploadCount) 
                VALUES (:name, :email, :pass, :role, :created, :bio, :adresse, :last, :super, :mod, :sub_start, :sub_end, :uploads)";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':name', $user->getUserName());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':pass', $user->getPassword());
        $stmt->bindValue(':role', $user->getRoleProp());
        $stmt->bindValue(':created', $user->getCreatedAt());
        $stmt->bindValue(':bio', $user->getBio());
        $stmt->bindValue(':adresse', $user->getAdresse());
        $stmt->bindValue(':last', $user->getLastLogin());
        
        // gestion simple boolean
        if ($user->getIsSuperAdmin()) {
            $stmt->bindValue(':super', 1);
        } else {
            $stmt->bindValue(':super', 0);
        }

        $stmt->bindValue(':mod', $user->getModeratorLevel());
        $stmt->bindValue(':sub_start', $user->getDateDebutAbonnement());
        $stmt->bindValue(':sub_end', $user->getDateFinAbonnement());
        $stmt->bindValue(':uploads', $user->getUploadCount());

        $stmt->execute();
        
        return $this->pdo->lastInsertId();
    }

    // mettre a jour
    private function update($user)
    {
        $sql = "UPDATE user SET 
                user_name = :name, 
                email = :email, 
                role = :role, 
                bio = :bio,
                adresse = :adresse,
                uploadCount = :uploads
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':name', $user->getUserName());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':role', $user->getRoleProp());
        $stmt->bindValue(':bio', $user->getBio());
        $stmt->bindValue(':adresse', $user->getAdresse());
        $stmt->bindValue(':uploads', $user->getUploadCount());
        $stmt->bindValue(':id', $user->getId());

        return $stmt->execute();
    }

    // supprimer
    public function delete($id)
    {
        $sql = "DELETE FROM user WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }

    // convertir array en objet
    private function hydrate($data)
    {
        return UserFactory::create($data);
    }
}
