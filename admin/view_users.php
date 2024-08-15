<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    <link rel="stylesheet" href="table.css"> <!-- You can link your table styles here -->
</head>
<body>
    <h1>View Users</h1>
    <a class ="return-button" href="dashboard.php">Return to Dashboard</a><br><br>

    <?php

    // Connect to the database (replace with your database credentials)
    $mysqli = new mysqli("localhost", "root", "", "entrance");

    if ($mysqli->connect_error) {
        die("Database connection failed: " . $mysqli->connect_error);
    }

    // Fetch user data from your database (replace with your query)
    $sql = "SELECT id, username, email, role FROM users";
    $result = $mysqli->query($sql);

    if ($result) {
        // Check if there are any users
        if ($result->num_rows > 0) {
            // Display the users in a table
            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>User Name</th>";
            echo "<th>Email</th>";
            echo "<th>Role</th>";
            echo "<th>Action</th>"; // New column for Edit and Delete buttons
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['role'] . "</td>";
                echo "<td>";

                // Edit button
                echo "<a class='edit-button' href='edit_user.php?id=" . $row['id'] . "'>Edit</a>";

                // Delete button
                echo "<a class='delete-button' href='delete_user.php?id=" . $row['id'] . "'>Delete</a>";

                echo "</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No users available.</p>";
        }
    } else {
        echo "<p>Database query failed. Please try again later.</p>";
    }

    // Close the database connection
    $mysqli->close();
    ?>
</body>
</html>
