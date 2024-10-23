<?php
$host = 'localhost';
$dbname = 'chat_app';
$user = 'root'; // Replace with your MySQL username
$pass = ''; // Replace with your MySQL password

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
