<?php

include_once "../config/DatabaseConnection.php";
include_once "../models/UserModel.php";

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$db = new DatabaseConnection();
$connection = $db->openConnection();

$userModel = new UserModel();

$action = $_POST["action"] ?? $_GET["action"] ?? "";

if($action == "" && $_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["email"]) && isset($_POST["password"])){
        $action = "login";
    }
}

if($action == "register"){

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";
    $phone = trim($_POST["phone"] ?? "");
    $nationality = trim($_POST["nationality"] ?? "");

    $hasError = false;

    if(!$name){
        $_SESSION["nameError"] = "Name is required";
        $hasError = true;
    }

    if(!$email){
        $_SESSION["emailError"] = "Email is required";
        $hasError = true;
    }

    if(!$password){
        $_SESSION["passwordError"] = "Password is required";
        $hasError = true;
    }

    if($password != $confirm_password){
        $_SESSION["confirmError"] = "Password does not match";
        $hasError = true;
    }

    if(!$phone){
        $_SESSION["phoneError"] = "Phone is required";
        $hasError = true;
    }

    if(!$nationality){
        $_SESSION["nationalityError"] = "Nationality is required";
        $hasError = true;
    }

    if($hasError){
        Header("Location: ../views/auth/register.php");
        exit();
    }

    $check = $userModel->getUserByEmail($connection, $email);

    if($check->num_rows > 0){
        $_SESSION["emailError"] = "Email already exists";
        Header("Location: ../views/auth/register.php");
        exit();
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $result = $userModel->register($connection, $name, $email, $password_hash, $phone, $nationality);

    if($result){
        Header("Location: ../views/auth/login.php");
    }else{
        echo "Registration failed";
    }
}

if($action == "login"){

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $remember = $_POST["remember"] ?? $_POST["remember_me"] ?? "";

    if($email == "" || $password == ""){
        $_SESSION["loginError"] = "Email and password are required";
        Header("Location: ../views/auth/login.php");
        exit();
    }

    $result = $userModel->getUserByEmail($connection, $email);

    if($result->num_rows == 1){

        $user = $result->fetch_assoc();

        if(password_verify($password, $user["password_hash"])){

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["role"] = $user["role"];

            if($remember){
                $token = bin2hex(random_bytes(20));
                $userModel->updateRememberToken($connection, $user["id"], $token);
                setcookie("remember_token", $token, time() + (86400 * 30), "/");
            }

            if($user["role"] == "admin"){
                Header("Location: ../views/admin/dashboard.php");
            }else{
                Header("Location: ../views/guest/profile.php");
            }

            exit();
        }
    }

    $_SESSION["loginError"] = "Email or password incorrect";
    Header("Location: ../views/auth/login.php");
    exit();
}

if($action == "logout"){

    session_destroy();
    setcookie("remember_token", "", time() - 3600, "/");
    Header("Location: ../views/auth/login.php");
    exit();
}

?>
