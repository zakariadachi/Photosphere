<?php

require_once __DIR__ . '/../app/Core/Database.php';

try {
    $pdo = Database::getConnection();
    echo "Connexion à la base de données réussie.\n";

    $checkSql = "SHOW COLUMNS FROM user LIKE 'status'";
    $stmt = $pdo->query($checkSql);
    
    if ($stmt->fetch()) {
        echo "La colonne 'status' existe déjà dans la table 'user'.\n";
    } else {
        echo "Ajout de la colonne 'status'...\n";
        $sql = "ALTER TABLE user ADD COLUMN status VARCHAR(50) DEFAULT 'active'";
        $pdo->exec($sql);
        echo "Colonne 'status' ajoutée avec succès.\n";
    }

} catch (PDOException $e) {
    echo "Erreur lors de la migration : " . $e->getMessage() . "\n";
    exit(1);
}
