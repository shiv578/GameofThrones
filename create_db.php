<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    $pdo->exec('CREATE DATABASE IF NOT EXISTS got_conquest CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
    echo "Database 'got_conquest' created successfully.\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
