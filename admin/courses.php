<?php
// Connect to the database (replace with your database credentials)
$mysqli = new mysqli("localhost", "root", "", "entrance");

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Fetch course data from the database
$sql = "SELECT * FROM course";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Start building the table to display the course data
    echo "<html>";
    echo "<head>";
    echo "<title>Course List</title>";
    echo "<link rel='stylesheet' href='table.css'>";
    echo "</head>";
    echo "<body>";
    echo "<h1>Course List</h1>";
    echo "<a class='ret-button' href='dashboard.php'>Return to Dashboard</a>";
    echo "<a class='add-button' href='addcourse.php'>Add Course</a>";
    echo "<br>";
    echo "<br>";
    echo "<table border='1'>";
    echo "<tr>";
    echo "<th>Course Photo</th>";
    echo "<th>Course Title</th>";
    echo "<th>Description</th>";
    echo "<th>Eligibility</th>";
    echo "</tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><img src='" . $row["course_photo"] . "' alt='Course Photo' height='80px' width='140px'></td>";
        echo "<td>" . $row["course_title"] . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        echo "<td>" . $row["eligibility"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    echo "</body>";
    echo "</html>";
} else {
    echo "No courses found.";
}

// Close the database connection
$mysqli->close();
?>
