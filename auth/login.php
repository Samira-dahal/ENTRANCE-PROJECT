<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENTRANCE PREPARTION SYSTEM - LOGIN</title>
    <link rel="stylesheet" href="../styles.css">
    <script>
        function openRegisterPopup() {
            const registerPageUrl = "register.php";
            const width = 600;
            const height = 400;
            const left = (screen.width - width) / 2;
            const top = (screen.height - height) / 2;
            window.open(registerPageUrl, "Register", `width=${width}, height=${height}, top=${top}, left=${left}`);
        }
    </script>
</head>
<body>
    <div class="form-body">
        <div class="form-container">
            <img src="../include/logo.png" alt="Entrance Photo" class="entrance-photo">
            <p class="title">ENTRANCE SYSTEM - USER LOGIN</p>
            <form class="form" action="login_process.php" method="post">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="text" name="username" id="username" placeholder="Email@email.com">
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password">
                    <div class="input-group-role">
                        <label for="role">Role</label>
                        <select name="role" id="role">
                            <option value="admin">Admin</option>
                            <option value="member">Member</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="sign">Login</button>
            </form>
            <br>
            <button class="sign" onclick="window.location.href='register.php'">Register</button>             
        </div>
    </div> 
</body>
</html>
