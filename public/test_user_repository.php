<?php

/**
 * Test du UserRepository
 */

require_once __DIR__ . '/../app/Repositories/UserRepository.php';
require_once __DIR__ . '/../app/Services/UserFactory.php';

use App\Repositories\UserRepository;
use App\Services\UserFactory;
use App\Entities\BasicUser;
use App\Entities\Administrator;

echo "=== Test du UserRepository ===\n\n";

$repo = new UserRepository();

// Nettoyage préalable
$testEmails = ['test_basic@example.com', 'test_admin@example.com'];
foreach ($testEmails as $email) {
    // Note: on aurait besoin d'une méthode findByEmail mais findAll suffit pour le test brut
    $all = $repo->findAll();
    foreach ($all as $u) {
        if ($u->getEmail() === $email) {
            $repo->delete($u->getId());
        }
    }
}

// 1. Test Create Basic User
echo "Test 1: Création d'un BasicUser... ";
// On crée d'abord l'OBJET via la factory
$basicUserObject = UserFactory::create([
    'user_name' => 'test_basic',
    'email' => 'test_basic@example.com',
    'password' => 'pass123',
    'role' => 'basicuser'
]);

// Puis on le sauvegarde via le repo
$newId = $repo->save($basicUserObject);

if ($newId) {
    echo "✅ SUCCÈS (ID: " . $newId . ")\n";
    // On met à jour l'ID de l'objet pour la suite
    // (Dans un framework ORM ce serait auto, ici on le fait à la main ou on recharge)
    $createdBasic = $repo->findById($newId);
} else {
    echo "❌ ÉCHEC\n";
    $createdBasic = null;
}

// 2. Test Create Admin User
echo "Test 2: Création d'un Administrator... ";
$adminUserObject = UserFactory::create([
    'user_name' => 'test_admin',
    'email' => 'test_admin@example.com',
    'password' => 'adminpass',
    'role' => 'admin',
    'isSuperAdmin' => true
]);

$newAdminId = $repo->save($adminUserObject);

if ($newAdminId) {
    $createdAdmin = $repo->findById($newAdminId);
    if ($createdAdmin instanceof Administrator && $createdAdmin->isSuperAdmin()) {
        echo "✅ SUCCÈS (ID: " . $createdAdmin->getId() . ")\n";
    } else {
        echo "❌ ÉCHEC (Type incorrect)\n";
    }
} else {
    echo "❌ ÉCHEC (Save failed)\n";
    $createdAdmin = null;
}

// 3. Test Find By ID
echo "Test 3: Recherche par ID... ";
if ($createdBasic) {
    $found = $repo->findById($createdBasic->getId());
    if ($found && $found->getEmail() === 'test_basic@example.com') {
        echo "✅ SUCCÈS\n";
    } else {
        echo "❌ ÉCHEC\n";
    }
} else {
    echo "⚠️ IGNORÉ\n";
}

// 4. Test Update
echo "Test 4: Mise à jour... ";
if ($createdBasic) {
    // On modifie l'objet
    $createdBasic->setBio('Updated Bio');
    // On sauvegarde
    $repo->save($createdBasic);
    
    // On relit pour vérifier
    $updatedUser = $repo->findById($createdBasic->getId());
    if ($updatedUser->getBio() === 'Updated Bio') {
        echo "✅ SUCCÈS\n";
    } else {
        echo "❌ ÉCHEC\n";
    }
} else {
    echo "⚠️ IGNORÉ\n";
}

// 5. Test Find All
echo "Test 5: Find All... ";
$allUsers = $repo->findAll();
if (count($allUsers) >= 2) {
    echo "✅ SUCCÈS (" . count($allUsers) . " utilisateurs trouvés)\n";
} else {
    echo "❌ ÉCHEC\n";
}

// 6. Test Delete
echo "Test 6: Suppression... ";
if ($createdBasic) {
    $repo->delete($createdBasic->getId());
    $notFound = $repo->findById($createdBasic->getId());
    if ($notFound === null) {
        echo "✅ SUCCÈS\n";
    } else {
        echo "❌ ÉCHEC\n";
    }
} else {
    echo "⚠️ IGNORÉ\n";
}

// Clean up admin
if ($createdAdmin) {
    $repo->delete($createdAdmin->getId());
}

echo "\n=== Fin des tests ===\n";
