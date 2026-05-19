<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$loginError = $_SESSION["loginError"] ?? "";
unset($_SESSION["loginError"]);
?>

<html>
<head>

<title>Login - Hotel Room Booking System</title>

<link rel="stylesheet" href="../style.css">

<style>

body{
    margin:0;
    font-family:Arial, sans-serif;
    background:linear-gradient(rgba(0,0,0,.45), rgba(0,0,0,.45)),
    url('https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1200');
    background-size:cover;
    background-position:center;
}

.auth-page{
    width:100%;
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:space-between;
}

.auth-left{
    width:50%;
    padding-left:80px;
    color:white;
}

.auth-left h1{
    font-size:55px;
    margin-bottom:15px;
}

.auth-left p{
    font-size:20px;
    line-height:1.8;
    width:80%;
}

.auth-right{
    width:50%;
    display:flex;
    justify-content:center;
    align-items:center;
}

.auth-card{
    width:380px;
    background:white;
    padding:40px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,.25);
}

.auth-card h2{
    margin-top:0;
    margin-bottom:25px;
    color:#123d5a;
}

.auth-card input[type=email],
.auth-card input[type=password]{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:6px;
}

.auth-card input[type=submit]{
    background:#125c8e;
    color:white;
    border:none;
    padding:12px 25px;
    border-radius:6px;
    cursor:pointer;
    font-weight:bold;
}

.auth-card input[type=submit]:hover{
    background:#0b3f64;
}

.error{
    color:red;
    margin-bottom:15px;
}

.auth-card a{
    color:#125c8e;
    text-decoration:none;
    font-weight:bold;
}

</style>

</head>

<body>

<div class="auth-page">

    <div class="auth-left">

        <h1>Welcome to Our Hotel</h1>

        <p>
            Hotel Room Booking System
        </p>

        <p>

            Book premium rooms with comfort and security.
            Guests can reserve rooms easily and comfortably.
            
        </p>

    </div>

    <div class="auth-right">

        <div class="auth-card">

            <h2>Login</h2>

            <p class="error">
                <?php echo $loginError; ?>
            </p>

            <form method="post" action="../../controllers/AuthController.php">

                <input type="hidden" name="action" value="login">

                <label>Email</label><br>
                <input type="email" name="email" required>
                <br><br>

                <label>Password</label><br>
                <input type="password" name="password" required>
                <br><br>

                <label>
                    <input type="checkbox" name="remember" value="1">
                    Remember Me
                </label>

                <br><br>

                <input type="submit" value="Login">

            </form>

            <p>
                New guest?
                <a href="register.php">Create account</a>
            </p>

        </div>

    </div>

</div>

</body>
</html>