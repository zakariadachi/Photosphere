<?php

require_once __DIR__ . '/../app/Core/Database.php';

try {
    $pdo = Database::getConnection();
    
    // Lire le fichier SQL
    $sql = file_get_contents(__DIR__ . '/insert_test_data.sql');
    
    if (!$sql) {
        throw new Exception("Impossible de lire le fichier insert_test_data.sql");
    }

    echo "Insertion des données de test...\n";
    
    // Exécuter les requêtes
    // Note: PDO::exec ne gère pas toujours bien les multiples requêtes selon les drivers
    // On peut splitter si nécessaire, mais mysql le supporte souvent par défaut
    
    // Pour être sûr, on vide d'abord les tables (sauf si on vient de faire init_db)
    // Mais on va supposer que c'est un ajout ou que init_db a été lancé
    
    $pdo->exec($sql);
    
    echo "✅ Données de test insérées avec succès !\n";
    echo "   - 4 Utilisateurs créés (Basic, Pro, Mod, Admin)\n";
    echo "   - 2 Albums\n";
    echo "   - 5 Photos\n";
    echo "   - 4 Tags\n";
    echo "   - 2 Commentaires\n";
    echo "   - 3 Likes\n";
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    // Si c'est une erreur de duplicate entry, c'est que les données sont déjà là
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        echo "   (Indice : Les données semblent déjà exister. Essayez de relancer 'php public/init_db.php' d'abord pour repartir à zéro.)\n";
    }
}
