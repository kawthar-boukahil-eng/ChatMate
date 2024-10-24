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
$("#search-button").on("click", function () {
    const searchTerm = $("#search").val().trim();
    if (searchTerm === "") {
        $("#searchResults").empty();
        return;
    }

    $.get(`search.php?username=${encodeURIComponent(searchTerm)}`, function (data) {
        const resultsDiv = $("#searchResults");
        resultsDiv.empty();  // Clear previous results

        try {
            const results = JSON.parse(data);  // Parse the JSON response

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
        } catch (error) {
            console.error("Failed to parse JSON:", error);  // Handle JSON errors
            resultsDiv.append("<div>Error fetching search results.</div>");
        }
    }).fail(function () {
        console.error("Failed to search users.");
        $("#searchResults").html("<div>Error fetching search results.</div>");
    });
});
$("#search-button").on("click", function () {
    const searchTerm = $("#search").val().trim();
    if (searchTerm === "") {
        $("#searchResults").empty();
        return;
    }

    $.get(`search.php?username=${encodeURIComponent(searchTerm)}`)
        .done(function (data) {
            const resultsDiv = $("#searchResults");
            resultsDiv.empty();

            try {
                const results = JSON.parse(data);  // Parse JSON response

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
            } catch (error) {
                console.error("JSON parsing error:", error);
                resultsDiv.append("<div>Error fetching search results.</div>");
            }
        })
        .fail(function () {
            console.error("Failed to search users.");
            $("#searchResults").html("<div>Error fetching search results.</div>");
        });
});
