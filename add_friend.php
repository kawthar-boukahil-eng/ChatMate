<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id'];
$friend_id = $_POST['friend_id'];

// Add the friend to the friendships table
$query = $db->prepare("INSERT INTO friendships (user_id, friend_id) VALUES (?, ?)");
$query->execute([$user_id, $friend_id]);
echo "Friend request sent!";?>
