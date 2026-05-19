<?php

include_once "../config/DatabaseConnection.php";
include_once "../models/UserModel.php";

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION["user_id"])){
    Header("Location: ../views/auth/login.php");
    exit();
}

$db = new DatabaseConnection();
$connection = $db->openConnection();

$userModel = new UserModel();

$id = $_SESSION["user_id"];
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$nationality = trim($_POST["nationality"] ?? "");
$preferred_room_type_id = $_POST["preferred_room_type_id"] ?? "";
$special_requests = trim($_POST["special_requests"] ?? "");
$subscribe = $_POST["subscribe_offers"] ?? "";

if($subscribe){
    setcookie("subscribe_offers", "1", time() + (86400 * 365), "/");
}else{
    setcookie("subscribe_offers", "", time() - 3600, "/");
}

$result = $userModel->updateProfile(
    $connection,
    $id,
    $name,
    $email,
    $phone,
    $nationality,
    $preferred_room_type_id,
    $special_requests
);

if($result){
    $_SESSION["name"] = $name;
    $_SESSION["profileSuccess"] = "Profile updated successfully";
    Header("Location: ../views/guest/profile.php");
}else{
    $_SESSION["profileError"] = "Profile not updated";
    Header("Location: ../views/guest/profile.php");
}

?>
