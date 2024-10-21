function addFriend(friendId) {
    $.ajax({
        type: "POST",
        url: "add_friend.php",
        data: { friend_id: friendId },
        success: function(response) {
            alert(response); // Notify user of success or failure
            // Optionally, refresh the friend list or update the UI here
        },
        error: function() {
            alert("Error adding friend.");
        }
    });
}

// Example function to load friend list
function loadFriendList() {
    // Use AJAX to fetch friend_list.php content and display it in your UI
}
