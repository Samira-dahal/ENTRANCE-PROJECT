<!DOCTYPE html>
<html>
<head>
    <title>Add Question</title>
    <link rel="stylesheet" href="edituser.css">
</head>
<body>
    <h1>Add Question</h1>
    <form method="post" action="add_question.php">
        <label for="subject">Select Subject:</label>
        <select id="subject" name="subject">
            <option value="+2">+2</option>
            <option value="BCA">BCA</option>
            <option value="BBA">BBA</option>
            <option value="BBS">BBS</option>
        </select><br><br>

        <label for="question_text">Enter Question:</label><br>
        <textarea id="question_text" name="question_text" rows="4" cols="50" required></textarea><br><br>

        <label for="options">Enter Answer Options (comma-separated):</label><br>
        <textarea id="options" name="options" rows="4" cols="50" required></textarea><br><br>

        <label for="correct_option">Select Correct Option:</label>
        <select id="correct_option" name="correct_option">
            <option value="1">Option 1</option>
            <option value="2">Option 2</option>
            <option value="3">Option 3</option>
            <option value="4">Option 4</option>
        </select><br><br>

        <input type="submit" name="submit" value="Add Question">
        <button class="ret-button"><a href="view_questions.php">Return to Questions</a></button>
    </form>
</body>
</html>
