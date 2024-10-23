<?php
session_start();
$host = 'localhost';
$dbname = 'chat_app';
$db_user = 'root';  // Renamed to avoid confusion with username in query
$db_password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $searchTerm = $_GET['username'] ?? ''; // Use a meaningful variable name

    // Prepare the SQL statement to search for users who are NOT friends
    $stmt = $pdo->prepare("
        SELECT id, username FROM users 
        WHERE username LIKE :searchTerm 
        AND id != :user_id
        AND id NOT IN (
            SELECT friend_id FROM friendships WHERE user_id = :user_id
        )
    ");
    
    $stmt->execute([
        ':searchTerm' => '%' . $searchTerm . '%',
        ':user_id' => $_SESSION['user_id']
    ]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the search results as a JSON response
    echo json_encode($results);
} catch (PDOException $e) {
    // Return an error message as JSON with HTTP 500 status
    echo json_encode(['error' => $e->getMessage()]);
    http_response_code(500);
}?>