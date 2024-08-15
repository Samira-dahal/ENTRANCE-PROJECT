<?php
// Start or resume the session
session_start();

if (!isset($_SESSION['email'])) {
    // If the email session variable is not set, the user is not logged in
    // You can add a redirect to the login page or display an error message here
    header("Location: ../auth/login.php"); // Redirect to the login page
    exit;
}

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $questionId = $_GET['id'];

    // Connect to the database (replace with your database credentials)
    $mysqli = new mysqli("localhost", "root", "", "entrance");

    if ($mysqli->connect_error) {
        die("Database connection failed: " . $mysqli->connect_error);
    }

    // Check if the user has confirmed the deletion
    if (isset($_GET['confirmed']) && $_GET['confirmed'] === 'true') {
        // Delete the question and its options from the database
        $deleteOptionsSql = "DELETE FROM options WHERE question_id = ?";
        $deleteOptionsStmt = $mysqli->prepare($deleteOptionsSql);
        $deleteOptionsStmt->bind_param("i", $questionId);
        $deleteOptionsStmt->execute();
        $deleteOptionsStmt->close();

        $deleteQuestionSql = "DELETE FROM questions WHERE id = ?";
        $deleteQuestionStmt = $mysqli->prepare($deleteQuestionSql);
        $deleteQuestionStmt->bind_param("i", $questionId);

        if ($deleteQuestionStmt->execute()) {
            echo "<p>Question deleted successfully.</p>";
        } else {
            echo "<p>Failed to delete question. Please try again later.</p>";
        }

        $deleteQuestionStmt->close();
    } else {
        // JavaScript code to display a confirmation prompt
        echo "
        <script>
            var confirmed = confirm('Are you sure you want to delete this question?');

            if (confirmed) {
                // If user clicks 'OK', proceed with deletion
                window.location.href = 'delete_question.php?id=$questionId&confirmed=true';
            } else {
                // If user clicks 'Cancel', redirect back to the view questions page
                window.location.href = 'view_questions.php';
            }
        </script>
        ";
    }

    $mysqli->close();
} else {
    echo "Question ID not provided.";
}
?>

<button class="return-button"><a href="view_questions.php">Back to Questions</a></button>
