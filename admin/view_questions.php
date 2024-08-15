<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Questions</title>
    <link rel="stylesheet" href="table.css">
    <!-- Add JavaScript to handle question deletion -->
    <script>
        function deleteQuestion(questionId) {
            if (confirm("Are you sure you want to delete this question?")) {
                // Redirect to the delete_question.php script with the question ID
                window.location.href = 'delete_question.php?id=' + questionId;
            }
        }
    </script>
</head>
<body>
    <h1>View Questions by Subject</h1>
    <button class="return-button"><a href="questions.php">Add Questions</a></button>
    <button class="return-button"><a href="dashboard.php">Return to Dashboard</a></button><br><br>

    <!-- HTML form -->
    <form method="POST">
        <label for="subject">Select Subject:</label>
        <select name="subject" id="subject">
            <option value="+2">+2</option>
            <option value="bca">BCA</option>
            <option value="bbs">BBS</option>
            <option value="bba">BBA</option>
            <!-- Add more options for other subjects -->
        </select>
        <button type="submit">Show Questions</button>
    </form>

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

    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject'])) {
        $selectedSubject = $_POST['subject'];

        // Fetch questions for the selected subject
        $sql = "SELECT * FROM questions WHERE subject = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $selectedSubject);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Display the questions in a table
                echo "<h2>Questions</h2>";
                echo "<table>";
                echo "<thead>";
                echo "<tr>";

                echo "<th>Question Text</th>";
                echo "<th>Options</th>"; // Add a column for options
                echo "<th>Correct Option</th>"; // Add a column for the correct option
                echo "<th>Delete Question</th>"; // Add a column for deleting the question
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";

                    echo "<td>" . $row['question_text'] . "</td>";
                    echo "<td>"; // Start a new column for options

                    // Fetch options for this question from the "options" table
                    $optionsSql = "SELECT id, option_text, is_correct FROM options WHERE question_id = ?";
                    $optionsStmt = $mysqli->prepare($optionsSql);
                    $optionsStmt->bind_param("i", $row['id']);
                    $optionsStmt->execute();
                    $optionsResult = $optionsStmt->get_result();

                    // Display options in a list
                    echo "<ul>";
                    while ($optionRow = $optionsResult->fetch_assoc()) {
                        echo "<li>" . $optionRow['option_text'];
                        if ($optionRow['is_correct'] == 1) {
                            echo " (Correct)";
                        }
                        echo "</li>";
                    }
                    echo "</ul>";

                    $optionsStmt->close();

                    echo "</td>"; // End the column for options

                    // Fetch and display the correct option from the "options" table
                    $correctOptionSql = "SELECT option_text FROM options WHERE question_id = ? AND is_correct = 1";
                    $correctOptionStmt = $mysqli->prepare($correctOptionSql);
                    $correctOptionStmt->bind_param("i", $row['id']);
                    $correctOptionStmt->execute();
                    $correctOptionResult = $correctOptionStmt->get_result();
                    $correctOptionRow = $correctOptionResult->fetch_assoc();

                    echo "<td>" . $correctOptionRow['option_text'] . "</td>";

                    $correctOptionStmt->close();

                    // Add Delete button for the question
                    echo '<td><button class="del-button" onclick="deleteQuestion(' . $row['id'] . ')">Delete Question</button></td>';

                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p>No questions available for $selectedSubject.</p>";
            }
        } else {
            echo "<p>Database query failed. Please try again later.</p>";
        }

        $stmt->close();
    }

    // Close the database connection
    $mysqli->close();
    ?>
</body>
</html>
