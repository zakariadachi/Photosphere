<?php

require_once __DIR__ . '/../app/Services/UserFactory.php';
require_once __DIR__ . '/../app/Entities/BasicUser.php';

use App\Services\UserFactory;
use App\Entities\BasicUser;

echo "=== TEST LIMITE UPLOAD BASIC USER ===\n\n";

// 1. Test avec 9 uploads
echo "Test 1: Basic User avec 9 uploads\n";
$user9 = UserFactory::create([
    'role' => 'basicuser',
    'uploadCount' => 9
]);

if ($user9 instanceof BasicUser) {
    echo "   -> Instance BasicUser confirmée.\n";
    echo "   -> Upload Count: " . $user9->getUploadCount() . "\n";
    echo "   -> Peut uploader ? " . ($user9->canUpload() ? "✅ OUI" : "❌ NON") . "\n";
}

// 2. Test avec 10 uploads
echo "\nTest 2: Basic User avec 10 uploads\n";
$user10 = UserFactory::create([
    'role' => 'basicuser',
    'uploadCount' => 10
]);

if ($user10 instanceof BasicUser) {
    echo "   -> Upload Count: " . $user10->getUploadCount() . "\n";
    echo "   -> Peut uploader ? " . ($user10->canUpload() ? "❌ POSSIBLE (Erreur)" : "✅ IMPOSSIBLE (Attendu)") . "\n";
}

// 3. Test avec 15 uploads (cas limite)
echo "\nTest 3: Basic User avec 15 uploads\n";
$user15 = UserFactory::create([
    'role' => 'basicuser',
    'uploadCount' => 15
]);
echo "   -> Peut uploader ? " . ($user15->canUpload() ? "❌ POSSIBLE (Erreur)" : "✅ IMPOSSIBLE (Attendu)") . "\n";
