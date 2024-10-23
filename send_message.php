<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id'];
$friend_id = $_POST['friend_id'];
$message = $_POST['message'];

// Insert the message into the database
$query = $db->prepare("INSERT INTO messages (sender_id, receiver_id, content) 
                       VALUES (?, ?, ?)");
$query->execute([$user_id, $friend_id, $message]);

echo "Message sent!";
?>
