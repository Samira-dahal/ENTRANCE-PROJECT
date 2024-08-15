<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = "bbs"; // Set the appropriate category based on your logic
    $question = $_POST["question"];
    $options = $_POST["options"];
    $correctOption = $_POST["correct_option"];
    $marks = $_POST["marks"];

    // Validate correct option
    if ($correctOption < 0 || $correctOption >= count($options)) {
        echo "Invalid correct option selected.";
        exit;
    }

    // Calculate total marks
    $totalMarks = $marks;

    // Process and store the question in the database
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
    $sql = "INSERT INTO questions (category, question, options, correct_option, marks) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    $optionsJson = json_encode($options);
    $stmt->bind_param("ssssi", $category, $question, $optionsJson, $correctOption, $marks);

    if ($stmt->execute()) {
        echo "Question added successfully. Total marks: $totalMarks";
    } else {
        echo "Error adding question: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

?>
