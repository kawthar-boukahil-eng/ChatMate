<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Room</title>
    <style>
        body { font-family: Arial, sans-serif; }
        #chat-box { width: 100%; height: 300px; overflow-y: scroll; border: 1px solid #000; margin-bottom: 10px; }
        #chat-box div { padding: 5px; border-bottom: 1px solid #ddd; }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Fetch new messages every second
        function loadMessages() {
            $.get("fetch_messages.php", function(data) {
                $("#chat-box").html(data);
            });
        }

        $(document).ready(function() {
            loadMessages(); // Initial load
            setInterval(loadMessages, 1000); // Auto-refresh every second

            // Send message using AJAX
            $("#send-message").on("submit", function(e) {
                e.preventDefault(); // Prevent form from refreshing the page
                $.post("send_message.php", $(this).serialize(), function() {
                    $("#message").val(""); // Clear message input
                    loadMessages(); // Reload messages
                });
            });
        });
    </script>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <img src="<?php echo htmlspecialchars($_SESSION['profile_pic']); ?>" alt="Profile Picture" width="100">
    <div id="chat-box"></div>

    <form id="send-message">
        <input type="text" id="message" name="message" placeholder="Enter your message" required>
        <button type="submit">Send</button>
    </form>

    <form action="logout.php" method="POST">
        <button type="submit" name="logout">Logout</button>
    </form>
    <!-- Add this before the closing </body> tag -->
<script>
document.getElementById("searchBtn").addEventListener("click", function() {
    const searchTerm = document.getElementById("search").value;

    fetch(`search.php?username=${encodeURIComponent(searchTerm)}`)
        .then(response => response.json())
        .then(data => {
            const resultsDiv = document.getElementById("searchResults");
            resultsDiv.innerHTML = ""; // Clear previous results
            data.forEach(user => {
                resultsDiv.innerHTML += `<div>${user.username}</div>`;
            });
        });
});
</script>

</body>
</html>
