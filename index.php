<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrance Preparation System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="hero">
    <?php include 'header.php'; ?>
        <div class="hero-content">
            <h1>Welcome to Entrance Preparation</h1>
            <p>Your gateway to successful entrance exam preparation</p>
            <a href="courses.php" class="cta-button">Get Started</a>
            
            <div class="course-boxes">
                <div class="course-box">
                    <img src="include/+2.png" alt="">
                    <h2>+2 Entrance</h2>
                    <p>Prepare for your +2 level entrance exams with comprehensive study materials.</p>
                    <button class="test-now" onclick="location.href='auth/login.php'">Test Now</button>
                </div>
                <div class="course-box">
                    <img src="include/BBS.png" alt="">
                    <h2>BBS Entrance</h2>
                    <p>Get ready for your BBS entrance exams with our expertly designed courses.</p>
                    <button class="test-now" onclick="location.href='auth/login.php'">Test Now</button>
                </div>
                <div class="course-box">
                    <img src="include/bca.png" alt="">
                    <h2>BCA Entrance</h2>
                    <p>Excel in your BCA entrance exams through our specialized preparation program.</p>
                    <button class="test-now" onclick="location.href='auth/login.php'">Test Now</button>
                </div>
            </div>
        </div>
    </header>
</body>
</html>
