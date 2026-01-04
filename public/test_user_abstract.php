<?php

/**
 * Test : Vérifier que la classe abstraite User ne peut pas être instanciée
 */

require_once __DIR__ . '/../app/Entities/User.php';

use App\Entities\User;

echo "=== Test de la classe abstraite User ===\n\n";

try {
    // Tentative d'instanciation directe
    // Même avec les bons arguments, ça DOIT échouer car la classe est abstraite
    $user = new User(
        1, 'test', 'test@test.com', 'pass', 'basic', '2024-01-01'
    );
    
    echo "❌ ÉCHEC: La classe User a pu être instanciée!\n";
    
} catch (Error $e) {
    echo "✅ SUCCÈS: Impossible d'instancier User directement.\n";
    echo "Message d'erreur PHP : " . $e->getMessage() . "\n";
}

echo "\n=== Fin du test ===\n";
