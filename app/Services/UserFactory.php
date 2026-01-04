<?php

namespace App\Services;

// On charge les fichiers des classes dont on a besoin
require_once __DIR__ . '/../Entities/User.php';
require_once __DIR__ . '/../Entities/BasicUser.php';
require_once __DIR__ . '/../Entities/ProUser.php';
require_once __DIR__ . '/../Entities/Moderator.php';
require_once __DIR__ . '/../Entities/Administrator.php';

use App\Entities\User;
use App\Entities\BasicUser;
use App\Entities\ProUser;
use App\Entities\Moderator;
use App\Entities\Administrator;

/**
 * UserFactory
 * C'est une "usine" à utilisateurs. On lui donne un tableau de données,
 * et elle nous fabrique le bon objet (Admin, Pro, ou Basic).
 */
class UserFactory
{
    // Fonction statique pour créer l'objet
    public static function create($data)
    {
        // On prépare les variables. Si ça n'existe pas dans le tableau $data, on met une valeur par défaut.
        
        // ID
        if (isset($data['id'])) {
            $id = $data['id'];
        } else {
            $id = 0;
        }

        // Nom d'utilisateur (on gère les deux noms de colonne possibles pour être sûr)
        if (isset($data['user_name'])) {
            $userName = $data['user_name'];
        } elseif (isset($data['username'])) {
            $userName = $data['username'];
        } else {
            $userName = '';
        }

        // Email et Password
        $email = isset($data['email']) ? $data['email'] : '';
        $password = isset($data['password']) ? $data['password'] : '';

        // Le Rôle (très important pour savoir quelle classe créer)
        if (isset($data['role'])) {
            $role = $data['role'];
        } elseif (isset($data['level'])) {
            $role = $data['level'];
        } else {
            $role = 'basicuser'; // Par défaut
        }

        // Autres champs...
        $createdAt = isset($data['created_at']) ? $data['created_at'] : date('Y-m-d H:i:s');
        $bio = isset($data['bio']) ? $data['bio'] : null;
        $adresse = isset($data['adresse']) ? $data['adresse'] : null;
        $lastLogin = isset($data['last_login']) ? $data['last_login'] : null;
        
        // Est-ce un super admin ?
        if (isset($data['isSuperAdmin'])) {
            $isSuperAdmin = (bool)$data['isSuperAdmin'];
        } else {
            $isSuperAdmin = false;
        }

        $moderatorLevel = isset($data['moderator_level']) ? $data['moderator_level'] : null;
        $dateDebut = isset($data['date_debut_abonnement']) ? $data['date_debut_abonnement'] : null;
        $dateFin = isset($data['date_fin_abonnement']) ? $data['date_fin_abonnement'] : null;
        $uploadCount = isset($data['uploadCount']) ? $data['uploadCount'] : 0;

        // Maintenant on regarde le rôle pour créer le bon objet
        
        if ($role == 'admin' || $role == 'Administrator') {
            // C'est un admin !
            return new Administrator($id, $userName, $email, $password, $role, $createdAt, $bio, $adresse, $lastLogin, $isSuperAdmin, $moderatorLevel, $dateDebut, $dateFin, $uploadCount);
        }
        
        if ($role == 'moderator' || $role == 'Moderator') {
            // C'est un modérateur
            return new Moderator($id, $userName, $email, $password, $role, $createdAt, $bio, $adresse, $lastLogin, $isSuperAdmin, $moderatorLevel, $dateDebut, $dateFin, $uploadCount);
        }
        
        if ($role == 'prouser' || $role == 'ProUser') {
            // C'est un pro
            return new ProUser($id, $userName, $email, $password, $role, $createdAt, $bio, $adresse, $lastLogin, $isSuperAdmin, $moderatorLevel, $dateDebut, $dateFin, $uploadCount);
        }

        // Si on est ici, c'est un utilisateur basique par défaut
        return new BasicUser($id, $userName, $email, $password, $role, $createdAt, $bio, $adresse, $lastLogin, $isSuperAdmin, $moderatorLevel, $dateDebut, $dateFin, $uploadCount);
    }
}
