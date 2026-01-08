<?php

require_once __DIR__ . '/../app/Services/UserFactory.php';

use App\Services\UserFactory;
use App\Entities\User;
use App\Entities\BasicUser;
use App\Entities\ProUser;
use App\Entities\Moderator;
use App\Entities\Administrator;

echo "=== Test: Polymorphisme (Day 3) ===\n\n";

$users = [
    UserFactory::create(['id' => 1, 'user_name' => 'Basic', 'role' => 'basicuser', 'uploadCount' => 5]),
    UserFactory::create(['id' => 2, 'user_name' => 'Pro', 'role' => 'prouser', 'date_fin_abonnement' => '2025-01-01']),
    UserFactory::create(['id' => 3, 'user_name' => 'Mod', 'role' => 'moderator']),
    UserFactory::create(['id' => 4, 'user_name' => 'Admin', 'role' => 'admin', 'isSuperAdmin' => true]),
];

echo "1. Vérification du type (instanceof User)\n";
foreach ($users as $user) {
    if ($user instanceof User) {
        $message = "✅ Est un User";
    } else {
        $message = "❌ N'est pas un User";
    }
    echo "   - " . $user->getUserName() . " : " . $message . "\n";
}

echo "\n2. Comportement Polymorphique (méthodes spécifiques)\n";

foreach ($users as $user) {
    echo "--- Utilisateur : " . $user->getUserName() . " (" . $user->getRole() . ") ---\n";
    
    // Test canUpload
    if ($user instanceof BasicUser) {
        if ($user->canUpload()) {
            echo "   [Basic] Peut envoyer des photos : Oui\n";
        } else {
            echo "   [Basic] Peut envoyer des photos : Non\n";
        }
    }
    
    // Test Droit Album Privé
    if ($user->canCreatePrivateAlbum()) {
        echo "   Droit Album Privé : ✅ Oui\n";
    } else {
        echo "   Droit Album Privé : ❌ Non\n";
    }
    
    echo "\n";
}

echo "3. Test de substitution (Liskov)\n";
function afficherInfoUtilisateur(User $u) {
    echo "   " . $u->getUserName() . " (ID: " . $u->getId() . ")\n";
}

echo "   Appel d'une fonction attendant 'User' avec les sous-classes:\n";
foreach ($users as $user) {
    afficherInfoUtilisateur($user);
}

echo "\n=== Fin des tests Day 3 ===\n";
