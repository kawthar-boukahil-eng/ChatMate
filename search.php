<?php
include 'db_connection.php'; // Include your database connection file

if (isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];
    $userId = $_SESSION['user_id']; // Get the logged-in user's ID

    $stmt = $conn->prepare("SELECT id, username, profile_pic FROM users WHERE username LIKE ? AND id != ?");
    $searchTerm = "%" . $searchTerm . "%"; // Prepare the search term for wildcard search
    $stmt->bind_param("si", $searchTerm, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Output the search results
    while ($row = $result->fetch_assoc()) {
        // Display user info and a button to add as a friend
        echo "<div>";
        echo "<img src='" . $row['profile_pic'] . "' alt='" . $row['username'] . "' />";
        echo "<p>" . $row['username'] . "</p>";
        echo "<button onclick='addFriend(" . $row['id'] . ")'>Add Friend</button>";
        echo "</div>";
    }
}
?>
<?php
// Database connection
$conn = new mysqli("localhost", "username", "password", "chat_app");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search term from the URL
$username = $_GET['username'];

// Prepare the SQL query
$sql = "
    SELECT u.username 
    FROM users u
    WHERE u.username LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$username%"; // Prepare the search term with wildcards
$stmt->bind_param("s", $searchTerm); // Bind the parameter

$stmt->execute();
$result = $stmt->get_result();

// Fetch results
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row; // Collect matching users
}

// Return results as JSON
echo json_encode($users);

$stmt->close();
$conn->close();
?>

