<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <link rel="stylesheet" href="table.css">
</head>
<body>
    <h1>Edit Question</h1>

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

    // Check if the 'id' parameter is set in the URL
    if (isset($_GET['id'])) {
        $questionId = $_GET['id'];

        // Fetch question data from the database based on the 'id'
        $sql = "SELECT * FROM questions WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $questionId);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                // Fetch question details
                $questionData = $result->fetch_assoc();
            } else {
                echo "Question not found.";
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
    } else {
        echo "Question ID not provided.";
        $mysqli->close();
        exit;
    }

    // Check if the form was submitted for updating the question
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_text'], $_POST['options'], $_POST['correct_option'])) {
        $newQuestionText = $_POST['question_text'];
        $newOptions = explode("\n", $_POST['options']);
        $correctOption = $_POST['correct_option'];

        // Update the question text in the database
        $updateSql = "UPDATE questions SET question_text = ? WHERE id = ?";
        $updateStmt = $mysqli->prepare($updateSql);
        $updateStmt->bind_param("si", $newQuestionText, $questionId);

        if ($updateStmt->execute()) {
            // Delete existing options for the question
            $deleteOptionsSql = "DELETE FROM options WHERE question_id = ?";
            $deleteOptionsStmt = $mysqli->prepare($deleteOptionsSql);
            $deleteOptionsStmt->bind_param("i", $questionId);
            $deleteOptionsStmt->execute();
            $deleteOptionsStmt->close();

            // Insert the new options
            foreach ($newOptions as $optionText) {
                // Trim each option to remove leading/trailing white spaces
                $optionText = trim($optionText);

                if (!empty($optionText)) {
                    $isCorrect = ($optionText == $newOptions[$correctOption - 1]) ? 1 : 0;
                    $insertOptionSql = "INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)";
                    $insertOptionStmt = $mysqli->prepare($insertOptionSql);
                    $insertOptionStmt->bind_param("isi", $questionId, $optionText, $isCorrect);
                    $insertOptionStmt->execute();
                    $insertOptionStmt->close();
                }
            }

            echo "<p>Question and options updated successfully.</p>";
            // You can add a redirect here if needed
        } else {
            echo "<p>Failed to update question. Please try again later.</p>";
        }

        $updateStmt->close();
    }
    ?>

    <form method="POST">
        <label for="questionText">Question Text:</label>
        <textarea id="questionText" name="question_text" rows="4" required><?php echo $questionData['question_text']; ?></textarea>
        <br><br>
        <label for="options">Options (one per line):</label>
        <textarea id="options" name="options" rows="4" cols="50" required><?php
            // Fetch options for this question from the "options" table
            $optionsSql = "SELECT option_text FROM options WHERE question_id = ?";
            $optionsStmt = $mysqli->prepare($optionsSql);
            $optionsStmt->bind_param("i", $questionId);
            $optionsStmt->execute();
            $optionsResult = $optionsStmt->get_result();

            // Collect options as newline-separated string
            $optionsArray = [];
            while ($optionRow = $optionsResult->fetch_assoc()) {
                $optionsArray[] = $optionRow['option_text'];
            }
            echo implode("\n", $optionsArray);

            $optionsStmt->close();
        ?></textarea>
        <br><br>
        <label for="correctOption">Correct Option (1 to <?php echo count($optionsArray); ?>):</label>
        <input type="number" id="correctOption" name="correct_option" min="1" max="<?php echo count($optionsArray); ?>" value="<?php echo $correctOption; ?>" required>
        <br><br>
        <button type="submit">Update Question</button>
    </form>

    <br><br>
    <button class="return-button"><a href="viewquestions.php">Back to Questions</a></button>

    <?php
    // Close the database connection
    $mysqli->close();
    ?>
</body>
</html>
