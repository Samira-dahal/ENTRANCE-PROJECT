<?php
// Connect to the database (replace with your database credentials)
$mysqli = new mysqli("localhost", "root", "", "entrance");

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if the password and confirm password match
    if ($password !== $confirmPassword) {
        echo "<p>Passwords do not match. Please try again.</p>";
    } else {
        // Check if the email already exists in the database
        $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
        $stmtCheckEmail = $mysqli->prepare($checkEmailQuery);
        $stmtCheckEmail->bind_param("s", $email);
        $stmtCheckEmail->execute();
        $stmtCheckEmail->store_result();

        if ($stmtCheckEmail->num_rows > 0) {
            // Email already exists, show an alert message
            echo '<script>alert("You are already registered. Please log in to continue."); window.location.href = "login.php";</script>';
        } else {
            // Hash the password before storing it in the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Default role value
            $role = "member";

            // Insert user information into the 'users' table (including the default role)
            $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);

            if ($stmt->execute()) {
                echo "<p>Registration successful. You can now <a href='login.php'>login</a>.</p>";
            } else {
                echo "<p>Registration failed. Please try again later.</p>";
            }

            $stmt->close();
        }

        $stmtCheckEmail->close();
    }
} else {
    echo "<p>Invalid request.</p>";
}

// Close the database connection
$mysqli->close();
?>
