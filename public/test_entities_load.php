<?php

require_once __DIR__ . '/../app/Services/UserFactory.php';
require_once __DIR__ . '/../app/Entities/Album.php';
require_once __DIR__ . '/../app/Entities/Post.php';
require_once __DIR__ . '/../app/Entities/Comment.php';
require_once __DIR__ . '/../app/Entities/Like.php';
require_once __DIR__ . '/../app/Entities/Tag.php';

use App\Services\UserFactory;
use App\Entities\Album;
use App\Entities\Post;
use App\Entities\Comment;
use App\Entities\Like;
use App\Entities\Tag;

echo "=== TEST D'INSTANCIATION DES ENTITÉS ===\n\n";

// 1. User via Factory
$user = UserFactory::create([
    'id' => 1,
    'user_name' => 'test_user',
    'email' => 'test@user.com',
    'password' => 'pass',
    'role' => 'basicuser'
]);
echo "✅ User crée : " . get_class($user) . " (Role: " . $user->getRoleProp() . ")\n";

// 2. Album
$album = new Album(1, 'Vacances', true, 'Description album', 0, null, 1);
echo "✅ Album crée : " . $album->getName() . "\n";

// 3. Post
$post = new Post(1, 'Titre', 'Desc', 'img.jpg', 100, '800x600', 'published', 0, null, date('Y-m-d'), null, 1, 1);
echo "✅ Post crée : " . $post->getTitle() . "\n";

// 4. Comment
$comment = new Comment(1, 'Super !', false, date('Y-m-d H:i:s'), null, 1, 1);
echo "✅ Comment crée : " . $comment->getContent() . "\n";

// 5. Like
$like = new Like(1, 1, date('Y-m-d H:i:s'));
echo "✅ Like crée pour Post ID : " . $like->getPostId() . "\n";

// 6. Tag
$tag = new Tag(1, 'voyage');
echo "✅ Tag crée : " . $tag->getName() . "\n";

echo "\n--- Tout semble OK ! ---\n";
