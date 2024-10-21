<?php
include 'db_connection.php'; // Include your database connection file

if (isset($_POST['friend_id'])) {
    $friendId = $_POST['friend_id'];
    $userId = $_SESSION['user_id']; // Get the logged-in user's ID

    // Insert into the friends table
    $stmt = $conn->prepare("INSERT INTO friends (user_id, friend_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $friendId);
    if ($stmt->execute()) {
        echo "Friend request sent!";
    } else {
        echo "Error sending friend request.";
    }
}
?>
