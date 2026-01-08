<?php

namespace App\Services;
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

class UserFactory
{
    //créer l'objet
    public static function create($data)
    {
        if (isset($data['id'])) {
            $id = $data['id'];
        } else {
            $id = 0;
        }

        if (isset($data['user_name'])) {
            $userName = $data['user_name'];
        } elseif (isset($data['username'])) {
            $userName = $data['username'];
        } else {
            $userName = '';
        }

        $email = isset($data['email']) ? $data['email'] : '';
        $password = isset($data['password']) ? $data['password'] : '';

        if (isset($data['role'])) {
            $role = $data['role'];
        } elseif (isset($data['level'])) {
            $role = $data['level'];
        } else {
            $role = 'basicuser';
        }

        // Gestion des valeurs
        if (isset($data['created_at'])) {
            $createdAt = $data['created_at'];
        } else {
            $createdAt = date('Y-m-d H:i:s');
        }

        if (isset($data['bio'])) {
            $bio = $data['bio'];
        } else {
            $bio = null;
        }

        if (isset($data['adresse'])) {
            $adresse = $data['adresse'];
        } else {
            $adresse = null;
        }

        if (isset($data['last_login'])) {
            $lastLogin = $data['last_login'];
        } else {
            $lastLogin = null;
        }

        if (isset($data['moderator_level'])) {
            $moderatorLevel = $data['moderator_level'];
        } else {
            $moderatorLevel = null;
        }

        if (isset($data['date_debut_abonnement'])) {
            $dateDebut = $data['date_debut_abonnement'];
        } else {
            $dateDebut = null;
        }

        if (isset($data['date_fin_abonnement'])) {
            $dateFin = $data['date_fin_abonnement'];
        } else {
            $dateFin = null;
        }

        if (isset($data['uploadCount'])) {
            $uploadCount = $data['uploadCount'];
        } else {
            $uploadCount = 0;
        }

        if (isset($data['isSuperAdmin'])) {
            $isSuperAdmin = $data['isSuperAdmin'];
        } else {
            $isSuperAdmin = null;
        }
        if (isset($data['status'])) {
            $status = $data['status'];
        } else {
            $status = 'active';
        }
        
        if ($role == 'admin' || $role == 'Administrator') {
            return new Administrator($id, $userName, $email, $password, $role, $createdAt, $bio, $adresse, $lastLogin, $isSuperAdmin, $moderatorLevel, $dateDebut, $dateFin, $uploadCount, $status);
        }
        
        if ($role == 'moderator' || $role == 'Moderator') {
            return new Moderator($id, $userName, $email, $password, $role, $createdAt, $bio, $adresse, $lastLogin, $isSuperAdmin, $moderatorLevel, $dateDebut, $dateFin, $uploadCount, $status);
        }
        
        if ($role == 'prouser' || $role == 'ProUser') {
            return new ProUser($id, $userName, $email, $password, $role, $createdAt, $bio, $adresse, $lastLogin, $isSuperAdmin, $moderatorLevel, $dateDebut, $dateFin, $uploadCount, $status);
        }

        return new BasicUser($id, $userName, $email, $password, $role, $createdAt, $bio, $adresse, $lastLogin, $isSuperAdmin, $moderatorLevel, $dateDebut, $dateFin, $uploadCount, $status);
    }
}
