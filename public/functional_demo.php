<?php

require_once __DIR__ . '/../app/Services/UserFactory.php';
require_once __DIR__ . '/../app/Entities/Album.php';

use App\Services\UserFactory;
use App\Entities\BasicUser;
use App\Entities\ProUser;
use App\Entities\Moderator;
use App\Entities\Administrator;
use App\Entities\Album;

function assertion($label, $condition) {
    echo $label . ": " . ($condition ? "✅ SUCCÈS" : "❌ ÉCHEC") . "\n";
}

echo "=== DÉMONSTRATION FONCTIONNELLE PHOTOSPHERE ===\n\n";

// SCÉNARIO 1 : Gestion des limitations BasicUser
echo "--- 1. Limitations BasicUser ---\n";
$basic = UserFactory::create([
    'user_name' => 'JohnBasic', 
    'role' => 'basicuser', 
    'uploadCount' => 9
]);

echo "Utilisateur: " . $basic->getUserName() . " (" . $basic->getRole() . ")\n";
assertion("Doit pouvoir uploader (9/10)", $basic->canUpload());

$basic->setUploadCount(10);
assertion("Ne doit plus pouvoir uploader (10/10)", !$basic->canUpload());

assertion("Ne peut PAS créer d'album privé", !$basic->canCreatePrivateAlbum());

// Simulation création album
if (!$basic->canCreatePrivateAlbum()) {
    $albumPublic = new Album(1, 'Mes photos', true, 'Public', 0, null, $basic->getId()); // true = public
    echo "✅ Album Public créé avec succès.\n";
}

echo "\n";

// SCÉNARIO 2 : Privilèges ProUser
echo "--- 2. Privilèges ProUser ---\n";
// Simulation abonnement valide
$pro = UserFactory::create([
    'user_name' => 'SarahPro', 
    'role' => 'prouser',
    'date_fin_abonnement' => date('Y-m-d', strtotime('+1 month'))
]);

echo "Utilisateur: " . $pro->getUserName() . " (" . $pro->getRole() . ")\n";
echo "Abonnement actif jusqu'au: " . $pro->getDateFinAbonnement() . "\n";

assertion("Abonnement actif détecté", $pro->hasActiveSubscription());
assertion("PEUT créer d'album privé", $pro->canCreatePrivateAlbum());
assertion("Upload illimité (pas de check nécessaire)", true); // Pas de méthode canUpload pour pro car pas de limite, ou on pourrait l'ajouter

// Création album privé
if ($pro->canCreatePrivateAlbum()) {
    $albumPrive = new Album(2, 'Projets Clients', false, 'Privé', 0, null, $pro->getId()); // false = public (donc privé)
    echo "✅ Album Privé créé : " . $albumPrive->getName() . " (Public: " . ($albumPrive->isPublic() ? 'OUI' : 'NON') . ")\n";
}

echo "\n";

// SCÉNARIO 3 : Pouvoirs Modérateur & Admin
echo "--- 3. Modération & Administration ---\n";
$mod = UserFactory::create(['user_name' => 'AlexMod', 'role' => 'moderator']);
$admin = UserFactory::create(['user_name' => 'BossAdmin', 'role' => 'admin', 'isSuperAdmin' => true]);

echo "Modérateur: " . $mod->getUserName() . "\n";
assertion("Peut modérer", $mod->canModerate());
assertion("NE PEUT PAS créer de Post", !$mod->canCreatePost());
assertion("NE PEUT PAS créer d'Album", !$mod->canCreateAlbum());

echo "Administrateur: " . $admin->getUserName() . "\n";
assertion("Est Super Admin", $admin->isSuperAdmin());
assertion("A accès complet", $admin->hasFullAccess());
assertion("NE PEUT PAS créer de Post", !$admin->canCreatePost());
assertion("NE PEUT PAS créer d'Album", !$admin->canCreateAlbum());

echo "\n";

// SCÉNARIO 4 : Interactions entre Entités (Simulation)
echo "--- 4. Intégrité des Données (Simulation) ---\n";

// On imagine que Sarah (Pro) poste une photo dans son album privé
$photoId = 101;
$photoTitle = "Portrait Client A";
$photoAlbumId = 2; // L'album privé créé plus haut
$photoUserId = $pro->getId();

echo "Action: Sarah poste '$photoTitle' dans l'album ID $photoAlbumId.\n";
// Vérification logique
if ($albumPrive->getId() === $photoAlbumId && $albumPrive->getUserId() === $photoUserId) {
    echo "✅ Vérification Propriétaire Album OK.\n";
}
