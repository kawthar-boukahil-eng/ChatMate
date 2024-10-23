<?php
session_start();
require 'db.php'; // Adjust if your database connection is elsewhere

if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch friends from the database
$query = $db->prepare("SELECT users.id, users.username FROM friendships JOIN users ON users.id = friendships.friend_id WHERE friendships.user_id = ?");
$query->execute([$user_id]);

$friends = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($friends as $friend) {
    echo "<div class='friend list-group-item' data-id='{$friend['id']}'>{$friend['username']}</div>";
}
?>
