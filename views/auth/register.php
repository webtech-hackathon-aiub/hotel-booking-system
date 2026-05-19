<?php if(session_status() == PHP_SESSION_NONE){
    session_start();
} ?>
<html>
<head>
<title>Register - Hotel Room Booking System</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-left">
        <h1>Create Guest Account</h1>
        <p>Register to search rooms, book stays, and manage your profile.</p>
    </div>
    <div class="auth-right">
        <div class="auth-card">
            <h2>Register</h2>
            <form method="post" action="../../controllers/AuthController.php">
                <input type="hidden" name="action" value="register">

                <label>Name</label>
                <input type="text" name="name">
                <p class="error"><?php echo $_SESSION["nameError"] ?? ""; unset($_SESSION["nameError"]); ?></p>

                <label>Email</label>
                <input type="email" name="email">
                <p class="error"><?php echo $_SESSION["emailError"] ?? ""; unset($_SESSION["emailError"]); ?></p>

                <label>Password</label>
                <input type="password" name="password">
                <p class="error"><?php echo $_SESSION["passwordError"] ?? ""; unset($_SESSION["passwordError"]); ?></p>

                <label>Confirm Password</label>
                <input type="password" name="confirm_password">
                <p class="error"><?php echo $_SESSION["confirmError"] ?? ""; unset($_SESSION["confirmError"]); ?></p>

                <label>Phone</label>
                <input type="text" name="phone">
                <p class="error"><?php echo $_SESSION["phoneError"] ?? ""; unset($_SESSION["phoneError"]); ?></p>

                <label>Nationality</label>
                <input type="text" name="nationality">
                <p class="error"><?php echo $_SESSION["nationalityError"] ?? ""; unset($_SESSION["nationalityError"]); ?></p>

                <input type="submit" value="Register">
            </form>
            <p>Already registered? <a href="login.php">Login</a></p>
        </div>
    </div>
</div>
</body>
</html>
