<?php
session_start();
include 'db_connection.php'; // Make sure to include your DB connection

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE username LIKE ? LIMIT 10");
    $stmt->bind_param("s", $usernameParam);
    $usernameParam = "%" . $username . "%";
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
}
?>