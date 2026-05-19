<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once __DIR__ . "/DatabaseConnection.php";
require_once __DIR__ . "/../models/UserModel.php";

function getConnectionObject(){
    $db = new DatabaseConnection();
    return $db->openConnection();
}

function restoreRememberSession(){

    if(isset($_SESSION["user_id"])){
        return;
    }

    if(isset($_COOKIE["remember_token"])){

        $connection = getConnectionObject();
        $userModel = new UserModel();

        $result = $userModel->getUserByRememberToken($connection, $_COOKIE["remember_token"]);

        if($result->num_rows == 1){

            $user = $result->fetch_assoc();

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["role"] = $user["role"];
        }
    }
}

function requireLogin(){

    restoreRememberSession();

    if(!isset($_SESSION["user_id"])){
        Header("Location: ../auth/login.php");
        exit();
    }
}

function requireAdmin(){

    restoreRememberSession();

    if(!isset($_SESSION["user_id"])){
        Header("Location: ../auth/login.php");
        exit();
    }

    if($_SESSION["role"] != "admin"){
        Header("Location: ../guest/profile.php");
        exit();
    }
}

?>
