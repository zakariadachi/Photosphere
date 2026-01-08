<?php

require_once __DIR__ . '/../app/Services/UserFactory.php';
require_once __DIR__ . '/../app/Entities/Album.php';
require_once __DIR__ . '/../app/Entities/Photo.php';
require_once __DIR__ . '/../app/Entities/Comment.php';
require_once __DIR__ . '/../app/Entities/Like.php';
require_once __DIR__ . '/../app/Entities/Tag.php';

use App\Services\UserFactory;
use App\Entities\Album;
use App\Entities\Photo;
use App\Entities\Comment;
use App\Entities\Like;
use App\Entities\Tag;

echo "=== TEST D'INSTANCIATION DES ENTITÉS ===\n\n";

// User via Factory
$user = UserFactory::create([
    'id' => 1,
    'user_name' => 'test_user',
    'email' => 'test@user.com',
    'password' => 'pass',
    'role' => 'basicuser'
]);
echo "✅ User crée : " . get_class($user) . " (Role: " . $user->getRoleProp() . ")\n";

// Album
$album = new Album(1, 'Vacances', true, 'Description album', 0, null, 1);
echo "✅ Album crée : " . $album->getName() . "\n";

// Photo
$photo = new Photo(1, 'Titre', 'Desc', 'img.jpg', 100, '800x600', 'published', 0, null, date('Y-m-d'), null, 1, 1);
echo "✅ Photo crée : " . $photo->getTitle() . "\n";

// Comment
$comment = new Comment(1, 'Super !', 1, 1, false, date('Y-m-d H:i:s'));
echo "✅ Comment crée : " . $comment->getContent() . "\n";

// Like
$like = new Like(1, 1, date('Y-m-d H:i:s'));
echo "✅ Like crée\n";

// Tag
$tag = new Tag(1, 'voyage');
echo "✅ Tag crée : " . $tag->getName() . "\n";