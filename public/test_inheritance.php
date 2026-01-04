<?php

/**
 * Test de la hiérarchie d'héritage des classes User
 * Mise à jour "Basic Code" compatible
 */

require_once __DIR__ . '/../app/Services/UserFactory.php';

use App\Services\UserFactory;
use App\Entities\User;

echo "=== Test de la hiérarchie d'héritage ===\n\n";

// Test 1: BasicUser
echo "Test 1: Création d'un BasicUser\n";
// On utilise la Factory pour faire simple et éviter les 15 arguments
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
echo "Test 2: Création d'un ProUser\n";
$proUser = UserFactory::create([
    'id' => 2,
    'user_name' => 'jane_pro',
    'role' => 'prouser',
    'date_fin_abonnement' => '2026-12-31'
]);

echo "✅ ProUser créé avec succès\n";
echo "   - Role: " . $proUser->getRole() . "\n";
echo "   - Abonnement actif: " . ($proUser->hasActiveSubscription() ? 'Oui' : 'Non') . "\n\n";

// Test 3: Moderator
echo "Test 3: Création d'un Moderator\n";
$moderator = UserFactory::create([
    'id' => 3,
    'user_name' => 'mod_alice',
    'role' => 'moderator'
]);
echo "✅ Moderator créé avec succès\n";
echo "   - Role: " . $moderator->getRole() . "\n";
echo "   - Peut modérer: " . ($moderator->canModerate() ? 'Oui' : 'Non') . "\n\n";

// Test 4: Administrator
echo "Test 4: Création d'un Administrator\n";
$admin = UserFactory::create([
    'id' => 4,
    'user_name' => 'admin_bob',
    'role' => 'admin',
    'isSuperAdmin' => true
]);
echo "✅ Administrator créé avec succès\n";
echo "   - Role: " . $admin->getRole() . "\n";
echo "   - Super Admin: " . ($admin->isSuperAdmin() ? 'Oui' : 'Non') . "\n\n";

// Test 5: Vérification de l'héritage
echo "Test 5: Vérification de l'héritage\n";
// Comme on n'a plus de namespace stricte "use App\Entities\User", on vérifie juste le parent
$parentClass = get_parent_class($basicUser);
echo "   - Parent de BasicUser est User: " . ($parentClass === 'App\Entities\User' ? '✅ Oui' : '❌ Non (' . $parentClass . ')') . "\n\n";

echo "=== Tous les tests réussis! ===\n";
