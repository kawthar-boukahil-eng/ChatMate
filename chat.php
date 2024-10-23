<?php
session_start();

// Redirect if not logged in
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body { 
            height: 100vh; 
            background-color: #f5f5f5; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            margin: 0;
        }
        .chat-container { 
            width: 80%; 
            height: 90vh; 
            display: flex; 
            background-color: white; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
            border-radius: 10px; 
            overflow: hidden; 
        }
        #friends-list { 
            width: 30%; 
            border-right: 1px solid #ddd; 
            overflow-y: auto; 
            padding: 15px; 
        }
        #chat-section { 
            width: 70%; 
            display: flex; 
            flex-direction: column; 
            padding: 15px;
        }
        #chat-box { 
            flex: 1; 
            overflow-y: auto; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            padding: 10px; 
            margin-bottom: 10px; 
        }
        #chat-box div { 
            margin-bottom: 5px; 
        }
        .friend { 
            cursor: pointer; 
            padding: 10px; 
            border-bottom: 1px solid #eee; 
        }
        .friend:hover { 
            background-color: #f8f9fa; 
        }
        .active-friend { 
            background-color: #e9ecef; 
            font-weight: bold; 
        }
        .search-results {
            margin-top: 10px;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        let selectedFriendId = null; // Store selected friend's ID

        // Load the list of friends
        function loadFriends() {
            $.get("fetch_friends.php", function(data) {
                $("#friends-list").html(data);
            }).fail(function() {
                console.error("Failed to load friends.");
            });
        }

        // Load messages for the selected friend
        function loadMessages() {
            if (!selectedFriendId) return;

            $.get(`fetch_messages.php?friend_id=${selectedFriendId}`, function(data) {
                $("#chat-box").html(data);
                $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight); // Scroll to bottom
            }).fail(function() {
                console.error("Failed to load messages.");
            });
        }

        $(document).ready(function() {
            loadFriends(); // Load friends list

            setInterval(loadMessages, 1000); // Auto-refresh messages every second

            // Handle friend selection
            $(document).on("click", ".friend", function() {
                $(".friend").removeClass("active-friend");
                $(this).addClass("active-friend");

                selectedFriendId = $(this).data("id");
                const friendName = $(this).text();
                $("#message-header").text(friendName);
                loadMessages(); // Load messages for this friend
            });

            // Handle send message form submission
            $("#send-message").on("submit", function(e) {
                e.preventDefault();
                if (!selectedFriendId) {
                    alert("Please select a friend.");
                    return;
                }

                $.post("send_message.php", 
                    { message: $("#message").val(), friend_id: selectedFriendId }, 
                    function() {
                        $("#message").val(""); // Clear input
                        loadMessages(); // Reload messages
                    }
                ).fail(function() {
                    console.error("Failed to send message.");
                });
            });

            // Handle user search
            $("#search-button").on("click", function(e) {
                const searchTerm = $("#search").val().trim();
                if (searchTerm === "") return;

                $.get(`search.php?username=${encodeURIComponent(searchTerm)}`, function(data) {
                    const results = JSON.parse(data);
                    const resultsDiv = $("#searchResults");
                    resultsDiv.empty(); // Clear previous results

                    if (results.length === 0) {
                        resultsDiv.append("<div>No users found.</div>");
                    } else {
                        results.forEach(user => {
                            resultsDiv.append(`
                                <div class="list-group-item">
                                    ${user.username}
                                    <button class="btn btn-primary btn-sm add-friend" data-id="${user.id}">Add Friend</button>
                                </div>
                            `);
                        });
                    }
                }).fail(function() {
                    console.error("Failed to search users.");
                });
            });

            // Handle add friend button
            $(document).on("click", ".add-friend", function() {
                const friendId = $(this).data("id");
                $.post("add_friend.php", { friend_id: friendId }, function() {
                    alert("Friend request sent!");
                }).fail(function() {
                    console.error("Failed to send friend request.");
                });
            });
        });
    </script>
</head>
<body>
    <div class="chat-container">
        <!-- Friends List -->
        <div id="friends-list">
            <h4>Friends</h4>
        </div>

        <!-- Chat Section -->
        <div id="chat-section">
            <!-- Search Bar -->
            <div class="input-group mb-3">
                <input type="text" id="search" class="form-control" placeholder="Search users by name">
                <button id="search-button" class="btn btn-outline-secondary">Search</button>
            </div>

            <!-- Search Results -->
            <div id="searchResults" class="list-group search-results">
                <!-- Search results will appear here -->
            </div>

            <!-- Chat Header -->
            <h4 id="message-header">Select a friend to chat</h4>

            <!-- Chat Messages -->
            <div id="chat-box">
                <!-- Messages will load here -->
            </div>

            <!-- Send Message Form -->
            <form id="send-message" class="input-group">
                <input type="text" id="message" class="form-control" placeholder="Type your message" required>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</body>
</html>
