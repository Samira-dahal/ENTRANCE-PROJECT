<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "entrance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    // Check if the email already exists in the database
    $emailExistsSql = "SELECT * FROM contact WHERE email = ?";
    $emailExistsStmt = $conn->prepare($emailExistsSql);
    $emailExistsStmt->bind_param("s", $email);
    $emailExistsStmt->execute();
    $result = $emailExistsStmt->get_result();

    if ($result->num_rows > 0) {
        echo '<script>';
        echo 'alert("Email address already exists. Please use a different email.");';
        echo 'window.location.href = "contact.php";';
        echo '</script>';
    } else {
        $insertSql = "INSERT INTO contact (name, email, subject, message) VALUES (?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ssss", $name, $email, $subject, $message);

        if ($insertStmt->execute()) {
            echo '<script>';
            echo 'alert("Form submitted successfully!");';
            echo 'window.location.href = "index.php";';
            echo '</script>';
        } else {
            echo "Error inserting data: " . $insertStmt->error;
        }

        $insertStmt->close();
    }

    $emailExistsStmt->close();
} else {
    echo "Form submission error!";
}

$conn->close();
?>
