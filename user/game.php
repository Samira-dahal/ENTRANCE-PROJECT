<?php
session_start(); // Start or resume the session

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['email'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Initialize variables
$selectedSubject = '';
$questions = []; // Initialize an empty array to store questions

// Initialize the score
$score = 0;

// Connect to the database (replace with your database credentials)
$mysqli = new mysqli("localhost", "root", "", "entrance");

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject'])) {
    $selectedSubject = $_POST['subject']; // Get the selected subject

    // Retrieve and store questions based on the selected subject
    $sql = "SELECT * FROM questions WHERE subject = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $selectedSubject);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $questionId = $row['id'];
            $questionText = $row['question_text'];

            // Retrieve options for this question from the database
            $sql = "SELECT id, option_text, is_correct FROM options WHERE question_id = ?";
            $optionsStmt = $mysqli->prepare($sql);
            $optionsStmt->bind_param("i", $questionId);
            $optionsStmt->execute();
            $optionsResult = $optionsStmt->get_result();

            $question = [
                'id' => $questionId,
                'text' => $questionText,
                'options' => []
            ];

            while ($optionRow = $optionsResult->fetch_assoc()) {
                $optionId = $optionRow['id'];
                $optionText = $optionRow['option_text'];
                $isCorrect = $optionRow['is_correct'];

                $question['options'][] = [
                    'id' => $optionId,
                    'text' => $optionText,
                    'isCorrect' => $isCorrect
                ];
            }

            $questions[] = $question;
        }
    }
}

// Handle quiz submission and calculate the score
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answers'])) {
    $answers = $_POST['answers']; // Get the selected answers

    foreach ($answers as $questionId => $selectedOptionId) {
        // Retrieve the correct option ID from the database
        $sql = "SELECT id, is_correct FROM options WHERE question_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $questionId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $correctOptionId = null;

            while ($row = $result->fetch_assoc()) {
                if ($row['is_correct'] == 1) {
                    $correctOptionId = $row['id'];
                    break;
                }
            }

            // Check if the selected option matches the correct option
            if ($selectedOptionId == $correctOptionId) {
                $score++; // Increase the score if the answer is correct
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Game</title>
    <link rel="stylesheet" href="quiz.css">
    <style>
        .correct {
            background-color: #a0ea94; /* Green background for correct option */
        }

        .incorrect {
            background-color: #ea9e9e; /* Red background for incorrect option */
        }

        .score {
            display: none; /* Hide the score by default */
        }
    </style>
</head>
<body>
    <h1>Test your skill</h1>
    <form method="POST" action="">
        <div class="select">
        <label for="subject">Select Subject:</label>
        <select name="subject" id="subject">
            <option value="+2">+2</option>
            <option value="BCA">BCA</option>
            <option value="BBS">BBS</option>
            <option value="BBA">BBA</option>
        </select>
        
        <input type="submit" name="submit" value="Start Quiz">
        </div>
    </form>

    <!-- Display questions if subject is selected -->
    <?php if (!empty($selectedSubject) && !empty($questions)): ?>
        <form method="POST" action="" onsubmit="return showScore();">
            <?php foreach ($questions as $question): ?>
                <h2><?php echo $question['text']; ?></h2>
                <?php foreach ($question['options'] as $option): ?>
                    <label class="option">
                        <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo $option['id']; ?>"
                            data-is-correct="<?php echo $option['isCorrect']; ?>">
                        <?php echo $option['text']; ?>
                    </label><br>
                <?php endforeach; ?>
                <br>
            <?php endforeach; ?>
            <input type="submit" name="submit" value="Submit">
        </form>

        <!-- Display the score -->
        <div class="score">
            <h2>Your Score: <span id="scoreDisplay">Calculating...</span></h2>
        </div>
        
        <script>
            // JavaScript function to display the score in an alert
            function showScore() {
                const selectedOptions = document.querySelectorAll('input[type="radio"]:checked');
                let score = 0;

                selectedOptions.forEach((option) => {
                    const questionId = option.name;
                    const optionId = option.value;
                    const isCorrect = option.dataset.isCorrect;

                    // Disable all options for the current question
                    const options = document.querySelectorAll(`input[name="${questionId}"]`);
                    options.forEach((opt) => {
                        opt.disabled = true;
                        if (opt.value === optionId) {
                            if (isCorrect === '1') {
                                opt.parentElement.classList.add("correct"); // Highlight correct option
                                score++;
                            } else {
                                opt.parentElement.classList.add("incorrect"); // Highlight incorrect option
                            }
                        }
                    });
                });

                const scoreDisplay = document.getElementById("scoreDisplay");
                scoreDisplay.textContent = score;

                const scoreElement = document.querySelector(".score");
                scoreElement.style.display = "block"; // Display the score
                
                return false; // Prevent the default form submission
            }
        </script>
    <?php endif; ?>

    <div class="button-container">
    <a href="dashboard.php" class="button">Return to Dashboard</a>
</div>
</body>
</html>
