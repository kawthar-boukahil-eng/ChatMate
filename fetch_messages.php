<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['friend_id'])) {
    echo "Invalid request.";
    exit();
}

$user_id = $_SESSION['user_id'];
$friend_id = $_GET['friend_id'];

// Fetch messages between the user and the selected friend
$query = $db->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY created_at");
$query->execute([$user_id, $friend_id, $friend_id, $user_id]);

$messages = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($messages as $message) {
    $messageText = htmlspecialchars($message['message'], ENT_QUOTES);
    $sender = $message['sender_id'] == $user_id ? 'You' : 'Friend';
    echo "<div><strong>{$sender}:</strong> {$messageText}</div>";
}
?>
