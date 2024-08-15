<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Profile</title>
    <link rel="stylesheet" href="edituser.css"> <!-- Include your CSS file here -->
</head>
<body>
    <h1>Edit User Profile</h1>

    <?php
    // Start or resume the session
    session_start();

    if (!isset($_SESSION['email'])) {
        // If the email session variable is not set, the user is not logged in
        // You can add a redirect to the login page or display an error message here
        header("Location: ../auth/login.php"); // Redirect to the login page
        exit;
    }

    // Connect to the database (replace with your database credentials)
    $mysqli = new mysqli("localhost", "root", "", "entrance");

    if ($mysqli->connect_error) {
        die("Database connection failed: " . $mysqli->connect_error);
    }

    // Assuming you have a session variable storing the user's email
    $userEmail = $_SESSION['email'];

    // Fetch user data from the database based on the email
    $sql = "SELECT username, email FROM users WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $userEmail);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            // Fetch user details
            $userData = $result->fetch_assoc();
        } else {
            echo "User not found.";
            $stmt->close();
            $mysqli->close();
            exit;
        }
    } else {
        echo "Database query failed. Please try again later.";
        $stmt->close();
        $mysqli->close();
        exit;
    }

    $stmt->close();

    // Check if the form was submitted for updating user data
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['email'], $_POST['password'])) {
        $newUsername = $_POST['username'];
        $newEmail = $_POST['email'];
        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Update user data in the database
        $updateSql = "UPDATE users SET username = ?, email = ?, password = ? WHERE email = ?";
        $updateStmt = $mysqli->prepare($updateSql);
        $updateStmt->bind_param("ssss", $newUsername, $newEmail, $newPassword, $userEmail);

        if ($updateStmt->execute()) {
            echo "<p>User profile updated successfully.</p>";
        } else {
            echo "<p>Failed to update user profile. Please try again later.</p>";
        }

        $updateStmt->close();
    }
    ?>

    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $userData['username']; ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $userData['email']; ?>" required><br><br>

        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Update Profile</button>
    </form>

    <br><br>
    <button class="return-button"><a href="view_users.php">Back to Users</a></button>

    <?php
    // Close the database connection
    $mysqli->close();
    ?>
</body>
</html>
