<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to add friends.";
    exit();
}

$currentUserId = $_SESSION['user_id'];

if (isset($_POST['friend_id'])) {
    $friendId = $_POST['friend_id'];

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the friendship already exists
        $checkFriendship = $pdo->prepare("SELECT * FROM friendships 
                                          WHERE (user_id = ? AND friend_id = ?) 
                                          OR (user_id = ? AND friend_id = ?)");
        $checkFriendship->execute([$currentUserId, $friendId, $friendId, $currentUserId]);

        if ($checkFriendship->rowCount() > 0) {
            echo "You are already friends.";
        } else {
            // Add friendship
            $stmt = $pdo->prepare("INSERT INTO friendships (user_id, friend_id) VALUES (?, ?)");
            $stmt->execute([$currentUserId, $friendId]);
            echo "Friend added successfully!";
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    echo "Invalid request.";
}
?>
