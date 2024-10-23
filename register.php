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

    <?php
    session_start();
    require 'db.php'; // Database connection

    if (isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $profilePic = $_FILES['profile_pic'];

        try {
            // Check if the username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $username]);

            if ($stmt->rowCount() > 0) {
                echo "<p style='color:red;'>Username already exists. Try another.</p>";
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Handle profile picture upload
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $profilePicPath = 'uploads/default.png'; // Default profile picture
                if ($profilePic['error'] === UPLOAD_ERR_OK) {
                    $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    $ext = strtolower(pathinfo($profilePic['name'], PATHINFO_EXTENSION));

                    if (in_array($ext, $validExtensions)) {
                        $newFilename = uniqid() . '.' . $ext;
                        $profilePicPath = $uploadDir . $newFilename;

                        if (!move_uploaded_file($profilePic['tmp_name'], $profilePicPath)) {
                            echo "<p style='color:red;'>Failed to upload profile picture.</p>";
                            exit();
                        }
                    } else {
                        echo "<p style='color:red;'>Invalid file type. Only JPG, PNG, and GIF are allowed.</p>";
                        exit();
                    }
                }

                // Insert new user into the database
                $stmt = $pdo->prepare(
                    "INSERT INTO users (username, password, profile_pic) VALUES (:username, :password, :profile_pic)"
                );
                $stmt->execute([
                    ':username' => $username,
                    ':password' => $hashedPassword,
                    ':profile_pic' => $profilePicPath
                ]);

                echo "<p style='color:green;'>Registration successful! <a href='login.php'>Login here</a></p>";
            }
        } catch (PDOException $e) {
            echo "<p style='color:red;'>An error occurred: " . $e->getMessage() . "</p>";
        }
    }
    ?>
</body>
</html>
