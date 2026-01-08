<?php

require_once __DIR__ . '/../app/Core/Database.php';

try {
    $pdo = Database::getConnection();
    
    echo "Démarrage de la migration pour la table 'tags'...\n";
    
    // Ajouter la colonne 'slug'
    $sqlSlug = "ALTER TABLE tags ADD COLUMN slug VARCHAR(100) UNIQUE NULL AFTER name";
    $pdo->exec($sqlSlug);
    echo "✓ Colonne 'slug' ajoutée.\n";
    
    // Ajouter la colonne 'photoCount'
    $sqlCount = "ALTER TABLE tags ADD COLUMN photoCount INT DEFAULT 0 AFTER slug";
    $pdo->exec($sqlCount);
    echo "✓ Colonne 'photoCount' ajoutée.\n";
    
    // Remplir les slugs pour les tags existants
    $stmt = $pdo->query("SELECT id, name FROM tags");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $row['name']), '-'));
        $update = $pdo->prepare("UPDATE tags SET slug = :slug WHERE id = :id");
        $update->execute([':slug' => $slug, ':id' => $row['id']]);
    }
    echo "✓ Slugs existants générés.\n";
    
    // Mettre à jour photoCount pour les tags existants
    $sqlUpdateCount = "UPDATE tags t SET photoCount = (SELECT COUNT(*) FROM post_tag pt WHERE pt.tag_id = t.id)";
    $pdo->exec($sqlUpdateCount);
    echo "✓ photoCount mis à jour pour les tags existants.\n";
    
    echo "Migration terminée avec succès !\n";

} catch (PDOException $e) {
    if ($e->getCode() == '42S21') {
        echo "INFO: Les colonnes existent déjà.\n";
    } else {
        echo "ERREUR : " . $e->getMessage() . "\n";
    }
}
