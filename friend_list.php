<?php
include 'db_connection.php'; // Include your database connection file

$userId = $_SESSION['user_id']; // Get the logged-in user's ID

$stmt = $conn->prepare("SELECT u.id, u.username, u.profile_pic, u.last_active 
                         FROM friends f 
                         JOIN users u ON (f.friend_id = u.id) 
                         WHERE f.user_id = ? AND f.status = 'accepted'");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    // Check if the user is online (last_active within a certain time frame, e.g., 5 minutes)
    $isOnline = (strtotime($row['last_active']) > (time() - 300)) ? 'online' : 'offline';
    echo "<div class='friend' data-id='" . $row['id'] . "'>";
    echo "<img src='" . $row['profile_pic'] . "' alt='" . $row['username'] . "' />";
    echo "<p>" . $row['username'] . " <span class='$isOnline'></span></p>";
    echo "</div>";
}
?>
