<?php

require_once __DIR__ . '/../app/Repositories/UserRepository.php';
require_once __DIR__ . '/../app/Services/UserFactory.php';

use App\Repositories\UserRepository;
use App\Services\UserFactory;

echo "Test de UserRepository \n";

$repository = new UserRepository();

// CReATION d'un utilisateur
$uniqueSuffix = uniqid();
echo "$uniqueSuffix";
$userData = [
    'user_name' => 'UtilisateurTest_' . $uniqueSuffix,
    'email'     => 'test_' . $uniqueSuffix . '@example.com',
    'password'  => 'motdepasse123',
    'role'      => 'basicuser',
    'bio'       => 'Ceci est une bio de test.',
    'adresse'   => '123 Rue de PHP, WebCity'
];

$newUser = UserFactory::create($userData);
$newId = $repository->save($newUser);

if ($newId) {
    echo "Utilisateur créé avec succès ! ID généré : $newId";
} else {
    echo "Erreur lors de la création de l'utilisateur.";
}

// 2. findById
$user = $repository->findById($newId);

if ($user) {
    echo "Utilisateur trouvé en base ";
    echo "- Nom : " . $user->getUserName();
    echo "- Email : " . $user->getEmail();
    echo "- Bio : " . $user->getBio() ;
} else {
    echo "Utilisateur introuvable par ID";
}

// 3. save / update

echo "3. Test de mise à jour";
if ($user) {
    $user->setUserName("Testeur_Modifie_" . $uniqueSuffix);
    $user->setBio("Voici ma bio mise à jour après le test.");
    
    $success = $repository->save($user);
    
    if ($success) {
        $updatedUser = $repository->findById($newId);
        echo  "Mise à jour réussie ";
        echo "Nouveau nom : " . $updatedUser->getUserName();
        echo "Nouvelle bio : " . $updatedUser->getBio();
    } else {
        echo "Erreur lors de la mise à jour";
    }
}

// 4. findAll
echo "4. Test de findAll";
$allUsers = $repository->findAll();
echo "Nombre total d'utilisateurs dans la base : " . count($allUsers);

// 5. delete
echo "5. Test de suppression";
$deleteSuccess = $repository->delete($newId);

if ($deleteSuccess) {
    echo "Utilisateur ID $newId supprimé avec succès.";
       $checkUser = $repository->findById($newId);
    if ($checkUser === null) {
        echo "Vérification : L'utilisateur n'existe plus dans la base de données.";
    }
} else {
    echo "Erreur lors de la suppression.";
}