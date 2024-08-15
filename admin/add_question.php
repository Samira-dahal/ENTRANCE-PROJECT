<?php
// Connect to the database (replace with your database credentials)
$mysqli = new mysqli("localhost", "root", "", "entrance");

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject'], $_POST['question_text'], $_POST['options'], $_POST['correct_option'])) {
    $subject = $_POST['subject'];
    $questionText = $_POST['question_text'];
    $options = explode(',', $_POST['options']);
    $correctOption = $_POST['correct_option'];

    // Insert the question into the 'questions' table
    $sql = "INSERT INTO questions (subject, question_text) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $subject, $questionText);
    $stmt->execute();
    $questionId = $stmt->insert_id;
    $stmt->close();

    // Insert the options into the 'options' table and mark the correct option
    foreach ($options as $index => $optionText) {
        $isCorrect = ($index + 1 == $correctOption) ? 1 : 0;
        $sql = "INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("isi", $questionId, $optionText, $isCorrect);
        $stmt->execute();
        $stmt->close();
    }

    // Close the database connection
    $mysqli->close();

    // Redirect the user to the question page
    header("Location: questions.php");
    exit; // Ensure that the script stops executing after redirection
} else {
    echo "<p>Invalid request.</p>";
}

// Close the database connection
$mysqli->close();
?>
