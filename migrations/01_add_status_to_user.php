<?php
require_once __DIR__ . '/../app/Core/Database.php';

try {
    $pdo = Database::getConnection();
    echo "Connected to database.\n";

    // check if column exists
    $checkSql = "SHOW COLUMNS FROM user LIKE 'status'";
    $stmt = $pdo->query($checkSql);
    if ($stmt->fetch()) {
        echo "Column 'status' already exists in 'user' table.\n";
    } else {
        echo "Adding 'status' column...\n";
        $sql = "ALTER TABLE user ADD COLUMN status VARCHAR(50) DEFAULT 'active'";
        $pdo->exec($sql);
        echo "Column 'status' added successfully.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
