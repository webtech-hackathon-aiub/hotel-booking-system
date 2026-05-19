<?php

include_once "../config/DatabaseConnection.php";
include_once "../models/RoomModel.php";

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$db = new DatabaseConnection();
$connection = $db->openConnection();

$roomModel = new RoomModel();

$action = $_POST["action"] ?? $_GET["action"] ?? "";

if($action == "add"){

    $room_type_id = $_POST["room_type_id"] ?? "";
    $room_number = trim($_POST["room_number"] ?? "");
    $floor = $_POST["floor"] ?? "";
    $status = $_POST["status"] ?? "available";

    if(!$room_type_id || !$room_number || !$floor){
        $_SESSION["roomNumberError"] = "All fields are required";
        Header("Location: ../views/admin/addRoom.php");
        exit();
    }

    $checkResult = $roomModel->checkRoomNumber($connection, $room_number);

    if($checkResult->num_rows > 0){
        $_SESSION["roomNumberError"] = "Room number already exists";
        Header("Location: ../views/admin/addRoom.php");
        exit();
    }

    $roomModel->addRoom($connection, $room_type_id, $room_number, $floor, $status);

    Header("Location: ../views/admin/roomList.php");
}

if($action == "update"){

    $id = $_POST["id"];
    $room_type_id = $_POST["room_type_id"];
    $room_number = trim($_POST["room_number"] ?? "");
    $floor = $_POST["floor"];

    $checkResult = $roomModel->checkRoomNumberForUpdate($connection, $room_number, $id);

    if($checkResult->num_rows > 0){
        $_SESSION["roomNumberError"] = "Room number already exists";
        Header("Location: ../views/admin/editRoom.php?id=$id");
        exit();
    }

    $roomModel->updateRoom($connection, $id, $room_type_id, $room_number, $floor);

    Header("Location: ../views/admin/roomList.php");
}

if($action == "delete"){

    $id = $_GET["id"];

    $bookingResult = $roomModel->checkFutureBooking($connection, $id);

    if($bookingResult->num_rows > 0){
        $_SESSION["deleteError"] = "This room has future booking. Cannot delete.";
        Header("Location: ../views/admin/roomList.php");
        exit();
    }

    $roomModel->deleteRoom($connection, $id);

    Header("Location: ../views/admin/roomList.php");
}

?>
