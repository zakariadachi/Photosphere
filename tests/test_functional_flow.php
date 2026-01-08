<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../app/Repositories/UserRepository.php';
require_once __DIR__ . '/../app/Repositories/AlbumRepository.php';
require_once __DIR__ . '/../app/Repositories/PhotoRepository.php';
require_once __DIR__ . '/../app/Repositories/TagRepository.php';
require_once __DIR__ . '/../app/Repositories/CommentRepository.php';
require_once __DIR__ . '/../app/Repositories/LikeRepository.php';
require_once __DIR__ . '/../app/Entities/BasicUser.php';
require_once __DIR__ . '/../app/Entities/Album.php';
require_once __DIR__ . '/../app/Entities/photo.php';

use App\Repositories\UserRepository;
use App\Repositories\AlbumRepository;
use App\Repositories\PhotoRepository;
use App\Repositories\TagRepository;
use App\Repositories\CommentRepository;
use App\Repositories\LikeRepository;
use App\Entities\BasicUser;
use App\Entities\Album;
use App\Entities\Photo;

echo "=== Photosphere Functional Flow Test ===\n\n";

try {
    $userRepo = new UserRepository();
    $albumRepo = new AlbumRepository();
    $photoRepo = new PhotoRepository();
    $tagRepo = new TagRepository();
    $commentRepo = new CommentRepository();
    $likeRepo = new LikeRepository();

    // --- USER MANAGEMENT ---
    echo "[Step 1] Creating a new User... ";
    $timestamp = date('His');
    $uniqUser = "user_flow_" . $timestamp;
    $uniqEmail = "flow_" . $timestamp . "@test.com";

    $newUser = new BasicUser(0, $uniqUser, $uniqEmail, 'secret', 'basicuser', date('Y-m-d H:i:s'));
    
    $userId = $userRepo->save($newUser);
    if ($userId) {
        echo "OK (ID: $userId)\n";
    } else {
        echo "FAILED\n";
        exit;
    }

    // --- ALBUM CREATION ---
    echo "[Step 2] Creating an Album... ";
    $newAlbum = new Album(0, "Flow Album", true, "Test Album for Flow", 0, null, $userId);
    
    if ($albumRepo->save($newAlbum)) {
         $albums = $albumRepo->findUserAlbums($userId);
         if (count($albums) > 0) {
             $albumId = $albums[0]->getId();
             echo "OK (Album ID: $albumId)\n";
         } else {
             echo "FAILED (Album saved but not found)\n";
             exit;
         }
    } else {
        echo "FAILED\n";
        exit;
    }

    // --- PHOTO & TAGS ---
    echo "[Step 3] Creating a Photo with Tags... ";
    $newPhoto = new Photo(0, "Flow Photo", "Beautiful scenery", "flow.jpg", 2048, "1920x1080", "draft", 0, null, null, null, (int)$userId, (int)$albumId);
    
    if ($photoRepo->saveWithTags($newPhoto, ['nature', 'flow', 'test'])) {
        echo "OK\n";
    } else {
        echo "FAILED\n";
        exit;
    }

    // --- VERIFY PHOTO IN ALBUM ---
    echo "[Step 4] Verifying Photo in Album... ";
    $latest = $photoRepo->getLatest(1);
    if (count($latest) > 0 && $latest[0]->getTitle() === "Flow Photo") {
        $photoId = $latest[0]->getId();
        echo "OK (Photo ID: $photoId)\n";
        
        echo "         Linking Photo to Album... ";
        if ($albumRepo->addPhotoToAlbum($photoId, $albumId, $userId)) {
            echo "OK\n";
        } else {
             echo "ALREADY LINKED\n";
        }
    } else {
        echo "FAILED (Photo not found)\n";
        exit;
    }

    // --- SEARCH ---
    echo "[Step 5] Searching for Photo... ";
    $results = $photoRepo->search("Flow");
    if (count($results) > 0) {
        echo "OK (Found " . count($results) . ")\n";
    } else {
        echo "FAILED\n";
    }

    // --- USE TRAITS (LIKE/COMMENT) ---
    echo "[Step 6] Testing Interaction (Traits)... ";
    $photoObj = $photoRepo->findById($photoId);
    if ($photoObj) {
        $photoObj->addLike(123);
        $photoObj->addComment("Great pic!", 123);
        
        if ($photoObj->getLikeCount() == 1 && $photoObj->getCommentCount() == 1) {
             echo "OK\n";
        } else {
             echo "FAILED (Counts mismatch)\n";
        }
    } else {
        echo "FAILED (Could not retrieve Photo object)\n";
    }

    // --- ARCHIVE USER ---
    echo "[Step 7] Archiving User... ";
    if ($userRepo->archive($userId)) {
        $archived = $userRepo->findById($userId);
        if ($archived->getStatus() === 'archived') {
            echo "OK\n";
        } else {
            echo "FAILED (Status: " . $archived->getStatus() . ")\n";
        }
    } else {
        echo "FAILED\n";
    }

    // --- COMMENTS (PhotoCommunity) ---
    echo "\n[Step 8] Testing Comments... ";
    $comment1 = $commentRepo->create("Amazing photo!", 1, $photoId);
    $comment2 = $commentRepo->create("Great work!", 2, $photoId);
    
    if ($comment1 && $comment2) {
        $commentCount = $commentRepo->getCountByPost($photoId);
        echo "OK (Total: $commentCount)\n";
    } else {
        echo "FAILED\n";
    }

    // --- LIKES (PhotoCommunity) ---
    echo "[Step 9] Testing Likes... ";
    $likeRepo->addLike(1, $photoId);
    $likeRepo->addLike(2, $photoId);
    $likeRepo->addLike(3, $photoId);
    
    $likeCount = $likeRepo->getCountByPost($photoId);
    $hasLiked = $likeRepo->hasUserLiked(1, $photoId);
    
    if ($likeCount >= 3 && $hasLiked) {
        echo "OK (Likes: $likeCount)\n";
    } else {
        echo "FAILED\n";
    }

    // --- TAG STATS (PhotoCommunity) ---
    echo "[Step 10] Testing Tag Statistics... ";
    $stats = $tagRepo->getTagStats('nature');
    
    if (!empty($stats)) {
        echo "OK\n";
        echo "   -> Tag: " . $stats['tag']->getName() . "\n";
        echo "   -> Photos: " . $stats['totalPhotos'] . "\n";
        echo "   -> Users: " . $stats['totalUsers'] . "\n";
    } else {
        echo "WARNING (Tag 'nature' not found)\n";
    }

} catch (PDOException $e) {
    file_put_contents('error_log.txt', "DATABASE ERROR: " . $e->getMessage() . "\nSQL State: " . $e->errorInfo[0] . "\n" . $e->getTraceAsString());
    echo "ERROR LOGGED to error_log.txt\n";
} catch (Throwable $e) {
    file_put_contents('error_log.txt', "FATAL ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    echo "ERROR LOGGED to error_log.txt\n";
}

echo "\n=== Test Complete ===\n";
