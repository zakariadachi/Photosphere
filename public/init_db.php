<?php

$config = require __DIR__ . '/../config/database.php';

try {
    // Connexion sans base de données pour pouvoir la créer/supprimer
    $dsn = "mysql:host={$config['host']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    
    echo "Connexion au serveur MySQL réussie.\n";
    
    // Lire le fichier SQL
    $sql = file_get_contents(__DIR__ . '/../shema.sql');
    if (!$sql) {
        die("Impossible de lire shema.sql");
    }

    echo "Exécution du script shema.sql...\n";
    
    // Exécution multiple (nécessite que l'émulation soit activée ou supportée)
    // PDO::exec ne supporte pas toujours les requêtes multiples correctement selon la config,
    // mais pour un script d'init local c'est souvent OK.
    $pdo->exec($sql);
    
    echo "✅ Base de données 'photosphere' initialisée avec succès (Tables + Données) !\n";
    
    // Vérification rapide
    $pdo->exec("USE photosphere");
    $stmt = $pdo->query("SELECT COUNT(*) FROM user");
    $count = $stmt->fetchColumn();
    echo "Validation : $count utilisateurs trouvés dans la table 'user'.\n";

} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}
