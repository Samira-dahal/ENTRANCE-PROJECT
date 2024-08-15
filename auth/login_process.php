<?php
// Start or resume the session
session_start();

// Connect to the database (replace with your database credentials)
$mysqli = new mysqli("localhost", "root", "", "entrance");

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'], $_POST['role'])) {
    $email = $_POST['username']; // Use email as the username
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate user credentials (you should add proper validation and hashing during registration)
    $sql = "SELECT email, password, role FROM users WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($dbEmail, $dbPassword, $dbRole);
            $stmt->fetch();

            // Verify the password using password_verify()
            if (password_verify($password, $dbPassword)) {
                // Password is correct
                $_SESSION['email'] = $email; // Store the email in a session variable

                if ($dbRole === "admin") {
                    // Set user role in the session
                    $_SESSION['role'] = 'admin';

                    // Redirect to the admin dashboard
                    header("Location: ../admin/dashboard.php");
                    exit;
                } elseif ($dbRole === "member") {
                    // Set user role in the session
                    $_SESSION['role'] = 'member';

                    // Redirect to the user dashboard or another page
                    header("Location: ../user/dashboard.php");
                    exit;
                } else {
                    echo "<p>Invalid role. Please try again.</p>";
                }
            } else {
                echo "<p>Invalid password. Please try again.</p>";
            }
        } else {
            echo "<p>Invalid email. Please try again.</p>";
        }
    } else {
        echo "<p>Database query failed. Please try again later.</p>";
    }

    $stmt->close();
}

// Close the database connection
$mysqli->close();
?>
