<!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit" name="login">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>
<?php
session_start(); // Start the session

$host = 'localhost';
$dbname = 'chat_app';
$username = 'root';
$password = '';

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['login'])) {
        $user = trim($_POST['username']);
        $pass = trim($_POST['password']);

        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$user]);
        $userRecord = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userRecord && password_verify($pass, $userRecord['password'])) {
            // Login successful, set session variables
            $_SESSION['user_id'] = $userRecord['id'];
            $_SESSION['username'] = $userRecord['username'];
            $_SESSION['profile_pic'] = $userRecord['profile_pic'];

            // Redirect to chat page
            header("Location: chat.php");
            exit();
        } else {
            echo "Invalid username or password.";
        }
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
