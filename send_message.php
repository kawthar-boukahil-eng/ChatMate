<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['message']) || !isset($_POST['friend_id'])) {
    echo "Invalid request.";
    exit();
}

$user_id = $_SESSION['user_id'];
$message = $_POST['message'];
$friend_id = $_POST['friend_id'];

// Insert the message into the messages table
$query = $db->prepare("INSERT INTO messages (sender_id, receiver_id, message, created_at) VALUES (?, ?, ?, NOW())");
if ($query->execute([$user_id, $friend_id, $message])) {
    echo "Message sent!";
} else {
    echo "Error sending message.";
}
?>
