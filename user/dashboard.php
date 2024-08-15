<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Overview</title>
    <link rel="stylesheet" href="../admin/dashboard.css">
</head>
<body>
    <header>
        <h1>User Dashboard</h1>
    </header>
    <main>
        <div class="content">
            <div class="action-buttons">
                <a class="button" href="courses.php">
                    <img src="../include/course.png" alt="Manage Courses" class="button-icon">
                    <span>View Courses</span>
                </a>
                <a class="button" href="game.php">
                    <img src="../include/questions.png" alt="Manage Questions" class="button-icon">
                    <span>Play Quiz</span>
                </a><br>
                <a class="button" href="#" onclick="confirmLogout();">
                    <img src="../include/logout.jpeg" alt="Logout" class="button-icon">
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </main>

    <script>
        function confirmLogout() {
            var confirmLogout = confirm("Are you sure you want to logout?");
            if (confirmLogout) {
                // User clicked "OK," perform logout and redirect
                window.location.href = "../index.php";
            } else {
                // User clicked "Cancel," do nothing (stay on the same page)
            }
        }
    </script>
</body>
</html>
