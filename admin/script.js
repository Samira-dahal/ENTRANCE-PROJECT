$(document).ready(function() {
    let questionCount = 0;

    // Add new question form
    $("#add-question-button").click(function() {
        questionCount++;
        const questionForm = `
            <div class="question">
                <h3>Question ${questionCount}</h3>
                <label for="question-${questionCount}">Question:</label>
                <input type="text" id="question-${questionCount}" name="questions[]" required><br>
                <label>Options:</label>
                <input type="text" name="options[${questionCount}][]" required><br>
                <input type="text" name="options[${questionCount}][]" required><br>
                <input type="text" name="options[${questionCount}][]" required><br>
                <input type="text" name="options[${questionCount}][]" required><br>
                <label for="correct-option-${questionCount}">Correct Option:</label>
                <select id="correct-option-${questionCount}" name="correct_options[]">
                    <option value="0">Option 1</option>
                    <option value="1">Option 2</option>
                    <option value="2">Option 3</option>
                    <option value="3">Option 4</option>
                </select><br><br>
            </div>
        `;
        $("#questions-container").append(questionForm);
    });

    // Handle form submission using AJAX
    $("#question-form").submit(function(event) {
        event.preventDefault();

        const formData = $(this).serialize();
        $.ajax({
            url: "process_questions.php",
            type: "POST",
            data: formData,
            success: function(response) {
                alert(response);
                // Clear the form or take other actions as needed
            },
            error: function() {
                alert("An error occurred while processing the questions.");
            }
        });
    });
});
