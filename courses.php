<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COURSES</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .course-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            align-items: center;
            }

            .course {
            border: 1px solid #ccc;
            padding: 20px;
            text-align: center;
            }

            .course img {
            max-width: 300px;
            height: 200px;
            }

            .course h2 {
            margin: 20px 0;
            }

            .course p {
            margin-bottom: 10px;
            }
            .h1 {
                background-color: red;
            }

    </style>
</head>
<body>
    <?php
    include 'header.php';
    echo '<br /><br /><br /><br>';

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "entrance";

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch courses from the database
    $sql = "SELECT course_photo, course_title, description, eligibility FROM course";
    $result = $conn->query($sql);
    ?>
        <br><br><center><h1>Available Courses</h1></center>
    <div class="course-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="course">';
                echo '<img src="include/' . $row["course_photo"] . '" alt="' . $row["course_title"] . '">';
                echo '<h2>' . $row["course_title"] . '</h2>';
                echo '<p>' . $row["description"] . '</p>';
                echo '<p><strong>Eligibility:</strong> ' . $row["eligibility"] . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No courses available.</p>';
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
