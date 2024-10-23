<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id'];
$friend_id = $_GET['friend_id'];

// Fetch messages between the logged-in user and the selected friend
$query = $db->prepare("SELECT * FROM messages 
                       WHERE (sender_id = ? AND receiver_id = ?) 
                       OR (sender_id = ? AND receiver_id = ?) 
                       ORDER BY timestamp ASC");
$query->execute([$user_id, $friend_id, $friend_id, $user_id]);

$messages = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($messages as $message) {
    echo "<div>{$message['content']}</div>";
}
?>
