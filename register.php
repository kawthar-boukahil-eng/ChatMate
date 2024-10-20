<!-- register.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form action="register.php" method="POST" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="profile_pic">Profile Picture:</label>
        <input type="file" id="profile_pic" name="profile_pic" accept="image/*"><br><br>

        <button type="submit" name="register">Register</button>
    </form>
</body>
</html>



<?php
$host = 'localhost';
$dbname = 'chat_app';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['register'])) {
        $user = trim($_POST['username']);
        $pass = trim($_POST['password']);
        $profilePic = $_FILES['profile_pic'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$user]);

        if ($stmt->rowCount() > 0) {
            echo "Username already exists. Try another.";
        } else {
            $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);
            $uploadDir = 'uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $profilePicPath = 'uploads/default.png'; // Default profile pic
            if ($profilePic['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($profilePic['name'], PATHINFO_EXTENSION);
                $newFilename = uniqid() . '.' . $ext;
                $profilePicPath = $uploadDir . $newFilename;

                if (!move_uploaded_file($profilePic['tmp_name'], $profilePicPath)) {
                    echo "Failed to upload profile picture.";
                    exit;
                }
            }

            $stmt = $pdo->prepare("INSERT INTO users (username, password, profile_pic) VALUES (?, ?, ?)");
            $stmt->execute([$user, $hashedPassword, $profilePicPath]);

            echo "Registration successful! <a href='login.php'>Login here</a>";
        }
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
