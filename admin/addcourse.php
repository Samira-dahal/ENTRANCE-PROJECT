<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="../formcss.css">
</head>
<body>
    <?php
    include '../admin/header.php';
    ?>
    <br><br><br><br><br>

    <h1>Add New Course</h1>
    <form action="process_add_course.php" method="post" enctype="multipart/form-data">
        <label for="course_photo">Course Photo:</label>
        <input type="file" id="course_photo" name="course_photo" accept="image/*" required><br><br>

        <label for="course_title">Course Title:</label>
        <input type="text" id="course_title" name="course_title" required><br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea><br><br>

        <label for="eligibility">Eligibility:</label>
        <input type="text" id="eligibility" name="eligibility" required><br><br>

        <button type="submit">Add Course</button>
        <a class="return-button" href="courses.php">Return to courses</a>
    </form>
</body>
</html>
