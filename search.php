<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';  // Include DB connection

$searchTerm = $_GET['username'] ?? '';
if (empty($searchTerm)) {
    echo json_encode([]);  // Return empty array if no search term
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT id, username FROM users WHERE username LIKE ? LIMIT 10");
    $stmt->execute(['%' . $searchTerm . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);  // Return valid JSON
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);  // Handle exceptions
}
?>