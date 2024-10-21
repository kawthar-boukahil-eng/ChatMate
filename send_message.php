<?php
session_start();

if (!isset($_SESSION['user_id']) || empty($_POST['message'])) {
    exit();
}

$host = 'localhost';
$dbname = 'chat_app';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], trim($_POST['message'])]);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
