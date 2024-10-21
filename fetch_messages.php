<?php
$host = 'localhost';
$dbname = 'chat_app';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT users.username, messages.message, messages.sent_at 
                         FROM messages 
                         JOIN users ON messages.user_id = users.id 
                         ORDER BY messages.sent_at ASC");

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<div><strong>" . htmlspecialchars($row['username']) . ":</strong> " 
             . htmlspecialchars($row['message']) . " <small>(" 
             . $row['sent_at'] . ")</small></div>";
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
