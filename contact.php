<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONTACT</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="formcss.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="hero">
    <h2>Feel free to submit your queries!</h2><br>
    <form action="process_contact.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" required><br><br>

        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="4" required></textarea><br><br>

        <button type="submit" class="cta-button">Submit</button>
    </form>
    </div>
</body>
</html>
