<?php
require_once __DIR__ . '/../app/Services/UserFactory.php';

use App\Services\UserFactory;
use App\Entities\User;

echo "=== Test de la hiérarchie d'héritage ===\n\n";

// Test 1: BasicUser
echo "Création d'un BasicUser\n";
$basicUser = UserFactory::create([
    'id' => 1,
    'user_name' => 'john_doe',
    'email' => 'john@example.com',
    'password' => 'pass123',
    'role' => 'basicuser'
]);

echo "✅ BasicUser créé avec succès\n";
echo "   - ID: " . $basicUser->getId() . "\n";
echo "   - Username: " . $basicUser->getUserName() . "\n";
echo "   - Role: " . $basicUser->getRole() . "\n\n";

// Test 2: ProUser
echo "Création d'un ProUser\n";
$proUser = UserFactory::create([
    'id' => 2,
    'user_name' => 'jane_pro',
    'role' => 'prouser',
    'date_fin_abonnement' => '2026-12-31'
]);

echo "✅ ProUser créé avec succès\n";
echo "   - Role : " . $proUser->getRole() . "\n";

if ($proUser->hasActiveSubscription()) {
    echo "   - Abonnement actif : Oui\n\n";
} else {
    echo "   - Abonnement actif : Non\n\n";
}


// Test 3: Moderator
echo "Création d'un Moderator\n";
$moderator = UserFactory::create([
    'id' => 3,
    'user_name' => 'mod_alice',
    'role' => 'moderator'
]);
echo "✅ Moderator créé avec succès\n";
echo "   - Role : " . $moderator->getRole() . "\n";

if ($moderator->canModerate()) {
    echo "   - Peut modérer : Oui\n\n";
} else {
    echo "   - Peut modérer : Non\n\n";
}

// Test 4: Administrator
echo "Création d'un Administrator\n";
$admin = UserFactory::create([
    'id' => 4,
    'user_name' => 'admin_bob',
    'role' => 'admin',
    'isSuperAdmin' => true
]);
echo "✅ Administrator créé avec succès\n";
echo "   - Role : " . $admin->getRole() . "\n";

if ($admin->isSuperAdmin()) {
    echo "   - Super Admin : Oui\n\n";
} else {
    echo "   - Super Admin : Non\n\n";
}

echo "Vérification de l'héritage\n";
$parentClass = get_parent_class($basicUser);
echo "Vérification de l'héritage\n";

$parentClass = get_parent_class($basicUser);

if ($parentClass === 'App\Entities\User') {
    echo "   - Le parent de BasicUser est bien User : ✅ Oui\n\n";
} else {
    echo "   - Le parent de BasicUser est bien User : ❌ Non (C'est " . $parentClass . ")\n\n";
}

echo "=== Tous les tests réussis ! ===\n";
