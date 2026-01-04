<?php

/**
 * Exercices de Débogage et Optimisation
 */

require_once __DIR__ . '/../app/Services/UserFactory.php';

use App\Entities\User;
use App\Entities\BasicUser;
use App\Entities\ProUser;
use App\Services\UserFactory;

echo "=== EXERCICES DE DÉBOGAGE ===\n\n";

// ---------------------------------------------------------
// EXERCICE 1 : Que se passe-t-il si vous essayez d'instancier User directement ?
// ---------------------------------------------------------
echo "1. Test Instanciation Directe de User :\n";
try {
    // Tentative d'instanciation de la classe abstraite
    // Note: On utilise Reflection pour contourner la protection du constructeur si nécessaire, 
    // mais ici new User() suffit pour déclencher l'erreur PHP.
    $user = new User(1, 'test', 'test', 'test');
    echo "❌ CRITIQUE : L'instanciation a réussi (ce n'est pas normal pour une classe abstraite) !\n";
} catch (Error $e) {
    echo "✅ RÉSULTAT ATTENDU : Une erreur fatale a été attrapée.\n";
    echo "   Message : " . $e->getMessage() . "\n";
    echo "   Explication : Une classe abstraite sert de modèle et ne peut pas exister en tant qu'objet autonome.\n";
}

echo "\n---------------------------------------------------------\n";

// ---------------------------------------------------------
// EXERCICE 3 : Test de Liskov (Les sous-classes peuvent-elles remplacer les classes parentes ?)
// ---------------------------------------------------------
echo "3. Test de Substitution de Liskov (LSP) :\n";

/**
 * Fonction qui attend un objet de type User (classe parente)
 * Si le LSP est respecté, elle doit fonctionner avec n'importe quelle sous-classe
 * sans connaître leur type spécifique.
 */
function afficherInfosUtilisateur(User $utilisateur) {
    echo "   -> Traitement de l'utilisateur : " . $utilisateur->getUserName() . "\n";
    echo "   -> Rôle détecté : " . $utilisateur->getRole() . "\n";
    echo "   -> Classe réelle : " . get_class($utilisateur) . "\n";
    echo "   ✅ Succès : La sous-classe a été acceptée comme 'User'.\n\n";
}

// Création de différents types via la Factory
$basic = UserFactory::create(['level' => 'basicuser', 'username' => 'Liskov_Basic', 'email' => 'b@l.com', 'password' => 'p']);
$pro = UserFactory::create(['level' => 'prouser', 'username' => 'Liskov_Pro', 'email' => 'p@l.com', 'password' => 'p']);
$admin = UserFactory::create(['level' => 'admin', 'username' => 'Liskov_Admin', 'email' => 'a@l.com', 'password' => 'p']);

echo "Test avec BasicUser :\n";
afficherInfosUtilisateur($basic);

echo "Test avec ProUser :\n";
afficherInfosUtilisateur($pro);

echo "Test avec Administrator :\n";
afficherInfosUtilisateur($admin);

echo "Conclusion LSP : Le code client (la fonction) fonctionne avec toutes les variantes de User sans modification.\n";
