<?php
// Connect to the database (replace with your database credentials)
$mysqli = new mysqli("localhost", "root", "", "entrance");

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Check if the user ID to be deleted is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];

    // Fetch the user's details to display in the confirmation message
    $fetchSql = "SELECT username, email FROM users WHERE id = ?";
    $stmt = $mysqli->prepare($fetchSql);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $userData = $result->fetch_assoc();
        } else {
            echo "<p>User not found.</p>";
        }
    } else {
        echo "<p>Database query failed. Please try again later.</p>";
    }

    $stmt->close();
} else {
    echo "<p>Invalid user ID.</p>";
}

// Handle user deletion if confirmed
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    if (isset($userId)) {
        // Delete the user from the database
        $deleteSql = "DELETE FROM users WHERE id = ?";
        $deleteStmt = $mysqli->prepare($deleteSql);
        $deleteStmt->bind_param("i", $userId);

        if ($deleteStmt->execute()) {
            echo "<p>User '{$userData['username']}' with email '{$userData['email']}' has been deleted.</p>";
        } else {
            echo "<p>Failed to delete the user. Please try again later.</p>";
        }

        $deleteStmt->close();
    }
}

// Close the database connection
$mysqli->close();
?>

<?php
if (isset($userData)) {
    echo "<h1>Delete User</h1>";
    echo "<p>Are you sure you want to delete user '{$userData['username']}' with email '{$userData['email']}'?</p>";
    echo "<p><a href='delete_user.php?id={$userId}&confirm=yes'>Yes</a> | <a href='view_users.php'>No</a></p>";
}
?>
