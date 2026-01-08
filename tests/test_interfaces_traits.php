<?php

require_once __DIR__ . '/../app/Entities/Photo.php';

use App\Entities\Photo;

echo "=== Test : Interfaces et Traits sur Photo ===\n\n";

$photo = new Photo(
    1, 
    "Mon Super Post", 
    "Description", 
    "img.jpg", 
    1024, 
    "800x600", 
    "publie", 
    100, 
    "2024-01-01", 
    "2024-01-01 12:00:00", 
    null, 
    1, 
    null
);

echo "1. Test Taggable (via Trait)\n";
$photo->addTag("Paysage");
$photo->addTag("  NATURE  ");

$tags = $photo->getTags();
echo "   - Tags ajoutés : " . implode(", ", $tags) . "\n";

if ($photo->hasTag("nature")) {
    echo "   - Tag 'nature' trouvé : ✅ Oui\n";
} else {
    echo "   - Tag 'nature' trouvé : ❌ Non\n";
}

$photo->removeTag("paysage");
if (!$photo->hasTag("paysage")) {
    echo "   - Tag 'paysage' supprimé : ✅ Oui\n";
} else {
    echo "   - Tag 'paysage' supprimé : ❌ Non\n";
}
echo "\n";

echo "2. Test Commentable (via Implémentation)\n";
$photo->addComment("Super photo !", 2);
$photo->addComment("J'adore les couleurs.", 3);

echo "   - Nombre de commentaires : " . $photo->getCommentCount() . "\n";
$comments = $photo->getComments();
echo "   - Contenu com. 1 : " . $comments[0]['content'] . "\n";

if ($photo->removeComment(1)) {
    echo "   - Commentaire 1 supprimé : ✅ Oui\n";
}
echo "   - Nombre de commentaires après suppression : " . $photo->getCommentCount() . "\n";
echo "\n";


echo "3. Test Likeable (via Implémentation)\n";
$photo->addLike(10);
$photo->addLike(10); 

echo "   - Nombre de likes (après double like même user) : " . $photo->getLikeCount() . "\n";

if ($photo->isLikedBy(10)) {
    echo "   - Liké par user 10 : ✅ Oui\n";
}

$photo->removeLike(10);
if (!$photo->isLikedBy(10)) {
    echo "   - Like retiré : ✅ Oui\n";
}
echo "\n";


echo "4. Test Timestampable (via Trait)\n";
echo "   - Créé le : " . $photo->getCreatedAt('Y-m-d H:i:s') . "\n";
$photo->updateTimestamps();
echo "   - Mis à jour le : " . $photo->getUpdatedAt('Y-m-d H:i:s') . "\n";

echo "\n=== Fin des tests Interfaces/Traits ===\n";
