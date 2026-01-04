<?php

/**
 * Test de la UserFactory
 */

require_once __DIR__ . '/../app/Services/UserFactory.php';

use App\Services\UserFactory;
use App\Entities\BasicUser;
use App\Entities\ProUser;
use App\Entities\Moderator;
use App\Entities\Administrator;

echo "=== Test de la UserFactory ===\n\n";

// Test 1: Création BasicUser
echo "Test 1: Factory BasicUser... ";
$basicData = [
    'id' => 1,
    'username' => 'factory_basic',
    'email' => 'basic@test.com',
    'password' => 'pass',
    'level' => 'basic'
];
$user1 = UserFactory::create($basicData);
if ($user1 instanceof BasicUser) {
    echo "✅ SUCCÈS (Role: " . $user1->getRole() . ")\n";
} else {
    echo "❌ ÉCHEC\n";
}

// Test 2: Création ProUser
echo "Test 2: Factory ProUser... ";
$proData = [
    'id' => 2,
    'username' => 'factory_pro',
    'email' => 'pro@test.com',
    'password' => 'pass',
    'level' => 'prouser'
];
$user2 = UserFactory::create($proData);
if ($user2 instanceof ProUser) {
    echo "✅ SUCCÈS (Role: " . $user2->getRole() . ")\n";
} else {
    echo "❌ ÉCHEC\n";
}

// Test 3: Création Moderator
echo "Test 3: Factory Moderator... ";
$modData = [
    'id' => 3,
    'username' => 'factory_mod',
    'email' => 'mod@test.com',
    'password' => 'pass',
    'level' => 'moderator'
];
$user3 = UserFactory::create($modData);
if ($user3 instanceof Moderator) {
    echo "✅ SUCCÈS (Role: " . $user3->getRole() . ")\n";
} else {
    echo "❌ ÉCHEC\n";
}

// Test 4: Création Administrator
echo "Test 4: Factory Administrator... ";
$adminData = [
    'id' => 4,
    'username' => 'factory_admin',
    'email' => 'admin@test.com',
    'password' => 'pass',
    'level' => 'admin',
    'isSuperAdmin' => 1
];
$user4 = UserFactory::create($adminData);
if ($user4 instanceof Administrator && $user4->isSuperAdmin()) {
    echo "✅ SUCCÈS (Role: " . $user4->getRole() . ", Super: Oui)\n";
} else {
    echo "❌ ÉCHEC\n";
}

// Test 5: Fallback default
echo "Test 5: Factory Default (Inconnu -> Basic)... ";
$unknownData = [
    'id' => 5,
    'username' => 'factory_unknown',
    'email' => 'unknown@test.com',
    'password' => 'pass',
    'level' => 'alien_overlord' 
];
$user5 = UserFactory::create($unknownData);
if ($user5 instanceof BasicUser) {
    echo "✅ SUCCÈS (Fallback sur BasicUser)\n";
} else {
    echo "❌ ÉCHEC\n";
}

echo "\n=== Fin des tests ===\n";
