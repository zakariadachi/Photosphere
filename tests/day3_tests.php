<?php

/**
 * Tests Complets du Polymorphisme (Jour 3)
 * 
 * Ce fichier teste l'ensemble de la chaîne : Factory -> Entities -> Repository
 * et vérifie le comportement polymorphique des objets User.
 */

// Chargement des dépendances
require_once __DIR__ . '/../app/Services/UserFactory.php';
require_once __DIR__ . '/../app/Repositories/UserRepository.php';

use App\Services\UserFactory;
use App\Repositories\UserRepository;
use App\Entities\User;

echo "=== TESTS COMPLETS DU POLYMORPHISME (JOUR 3) ===\n\n";

// 1. Initialisation
$repo = new UserRepository();
$usersToCreate = [
    [
        'username' => 'poly_basic',
        'email' => 'poly.basic@test.com',
        'password' => 'secret',
        'level' => 'basic'
    ],
    [
        'username' => 'poly_pro',
        'email' => 'poly.pro@test.com',
        'password' => 'secret',
        'level' => 'prouser',
        'subscriptionEnd' => date('Y-m-d', strtotime('+1 year'))
    ],
    [
        'username' => 'poly_mod',
        'email' => 'poly.mod@test.com',
        'password' => 'secret',
        'level' => 'moderator'
    ],
    [
        'username' => 'poly_admin',
        'email' => 'poly.admin@test.com',
        'password' => 'secret',
        'level' => 'admin',
        'isSuperAdmin' => 1
    ]
];

$createdIds = [];

echo "--- Étape 1 : Création et Sauvegarde via Factory & Repository ---\n";

foreach ($usersToCreate as $data) {
    echo "Création de {$data['username']} ({$data['level']})...\n";
    
    // Utilisation de la Factory pour créer l'objet
    try {
        // Note: Le Repository prend un tableau pour create(), mais on va tester la logique complète
        // Ici on simule un flux : Factory -> Objet -> (hypothétiquement sauvegarde, mais le repo create prend un array)
        // Pour ce test, on va utiliser le repo->create qui utilise en interne la logique d'hydratation
        
        // Utilisation correcte : Factory -> Objet -> Save
        $userObj = UserFactory::create($data);
        $newId = $repo->save($userObj);
        
        // On récupère l'objet créé pour avoir l'ID
        $user = null;
        if($newId) {
            $user = $repo->findById($newId);
        }
        
        if ($user) {
            $createdIds[] = $user->getId();
            echo "✅ Sauvegardé avec ID: " . $user->getId() . " (Type: " . get_class($user) . ")\n";
        } else {
            echo "❌ Erreur de sauvegarde\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Exception: " . $e->getMessage() . "\n";
    }
}

echo "\n--- Étape 2 : Test du Polymorphisme ---\n";

// Récupération de tous les utilisateurs créés
echo "Lecture des utilisateurs depuis la BDD...\n";
$users = [];
foreach ($createdIds as $id) {
    $users[] = $repo->findById($id);
}

// Itération polymorphique
foreach ($users as $user) {
    if (!$user) continue;

    echo "\nUtilisateur ID: " . $user->getId() . "\n";
    echo "Pseudo: " . $user->getUsername() . "\n";
    
    // Appel de la méthode abstraite implémentée différemment (Polymorphisme)
    echo "Rôle (via getRole()): " . $user->getRole() . "\n";
    
    // Vérification de type spécifique (instanceof)
    if ($user instanceof \App\Entities\ProUser) {
        echo "⭐⭐ Fonctionnalité PRO détectée : Abonnement actif ? " . ($user->hasActiveSubscription() ? 'Oui' : 'Non') . "\n";
    }
    
    if ($user instanceof \App\Entities\Moderator) {
        echo "🛡️ Fonctionnalité MODERATEUR détectée : Peut modérer ? " . ($user->canModerate() ? 'Oui' : 'Non') . "\n";
    }
    
    if ($user instanceof \App\Entities\Administrator) {
        echo "👑 Fonctionnalité ADMIN détectée : Super Admin ? " . ($user->isSuperAdmin() ? 'Oui' : 'Non') . "\n";
    }
}

echo "\n--- Étape 3 : Nettoyage ---\n";

foreach ($createdIds as $id) {
    if ($repo->delete($id)) {
        echo "Utilisateur $id supprimé.\n";
    } else {
        echo "Erreur lors de la suppression de l'utilisateur $id.\n";
    }
}

echo "\n=== FIN DES TESTS ===\n";
