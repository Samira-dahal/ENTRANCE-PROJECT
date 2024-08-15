<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_title = $_POST["course_title"];
    $description = $_POST["description"];
    $eligibility = $_POST["eligibility"];

    // File upload handling
    $target_dir = "../include/upload/";
    $target_file = $target_dir . basename($_FILES["course_photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["course_photo"]["tmp_name"]);
    if($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // ... (rest of the upload checks)

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["course_photo"]["tmp_name"], $target_file)) {
            // Replace with your database connection details
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "entrance";

            // Create a connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare and execute the SQL statement
            $sql = "INSERT INTO course (course_photo, course_title, description, eligibility) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $target_file, $course_title, $description, $eligibility);

            if ($stmt->execute()) {
                echo '<script>alert("Course added successfully."); window.location.replace("addcourse.php");</script>';
            } else {
                echo "Error adding course: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
