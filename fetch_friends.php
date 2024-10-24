<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';  // Make sure this path is correct

session_start();

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    echo 'User not logged in.';
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT id, username FROM users WHERE username = ?");
    $stmt->execute([$userId]);
    $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($friends as $friend) {
        echo '<div class="friend" data-id="' . $friend['id'] . '">' . htmlspecialchars($friend['username']) . '</div>';
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
