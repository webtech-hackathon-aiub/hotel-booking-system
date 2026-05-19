<?php

include_once "../config/DatabaseConnection.php";
include_once "../models/RoomTypeModel.php";

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$db = new DatabaseConnection();
$connection = $db->openConnection();

$roomTypeModel = new RoomTypeModel();

$action = $_POST["action"] ?? $_GET["action"] ?? "";

/* =========================
   ADD ROOM TYPE
========================= */

if($action == "add"){

    $name = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $price_per_night = $_POST["price_per_night"] ?? "";
    $max_capacity = $_POST["max_capacity"] ?? "";
    $amenitiesArray = $_POST["amenities"] ?? array();

    $hasError = false;

    if(!$name){
        $_SESSION["nameError"] = "Name is required";
        $hasError = true;
    }

    if(!$price_per_night || $price_per_night <= 0){
        $_SESSION["priceError"] = "Price must be positive";
        $hasError = true;
    }

    if(!$max_capacity || $max_capacity <= 0){
        $_SESSION["capacityError"] = "Capacity must be positive";
        $hasError = true;
    }

    if($hasError){
        Header("Location: ../views/admin/addRoomType.php");
        exit();
    }

    $thumbnail_path = "";

    if(isset($_FILES["thumbnail"]) && $_FILES["thumbnail"]["name"] != ""){

        $allowedTypes = array("image/jpeg", "image/png");
        $maxSize = 2 * 1024 * 1024;

        if(!in_array($_FILES["thumbnail"]["type"], $allowedTypes)){
            $_SESSION["uploadError"] = "Only JPEG and PNG files are allowed";
            Header("Location: ../views/admin/addRoomType.php");
            exit();
        }

        if($_FILES["thumbnail"]["size"] > $maxSize){
            $_SESSION["uploadError"] = "Image size must be less than or equal 2 MB";
            Header("Location: ../views/admin/addRoomType.php");
            exit();
        }

        $fileName = time() . "_" . str_replace(" ", "_", basename($_FILES["thumbnail"]["name"]));

        $targetFolder = "../public/uploads/rooms/";

        if(!is_dir($targetFolder)){
            mkdir($targetFolder, 0777, true);
        }

        $targetFile = $targetFolder . $fileName;

        if(move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetFile)){
            $thumbnail_path = "/Hotel_Room_Booking_System/public/uploads/rooms/" . $fileName;
        }
    }

    $amenities = json_encode($amenitiesArray);

    $result = $roomTypeModel->addRoomType(
        $connection,
        $name,
        $description,
        $price_per_night,
        $max_capacity,
        $thumbnail_path,
        $amenities
    );

    if($result){
        Header("Location: ../views/admin/roomTypeList.php");
        exit();
    }else{
        echo "Room Type Not Added";
    }
}

/* =========================
   UPDATE ROOM TYPE
========================= */

if($action == "update"){

    $id = $_POST["id"];
    $name = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $price_per_night = $_POST["price_per_night"] ?? "";
    $max_capacity = $_POST["max_capacity"] ?? "";
    $amenitiesArray = $_POST["amenities"] ?? array();

    $thumbnail_path = "";

    if(isset($_FILES["thumbnail"]) && $_FILES["thumbnail"]["name"] != ""){

        $allowedTypes = array("image/jpeg", "image/png");
        $maxSize = 2 * 1024 * 1024;

        if(!in_array($_FILES["thumbnail"]["type"], $allowedTypes)){
            echo "Only JPEG and PNG files are allowed";
            exit();
        }

        if($_FILES["thumbnail"]["size"] > $maxSize){
            echo "Image size must be less than or equal 2 MB";
            exit();
        }

        $fileName = time() . "_" . str_replace(" ", "_", basename($_FILES["thumbnail"]["name"]));

        $targetFolder = "../public/uploads/rooms/";

        if(!is_dir($targetFolder)){
            mkdir($targetFolder, 0777, true);
        }

        $targetFile = $targetFolder . $fileName;

        if(move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetFile)){
            $thumbnail_path = "/Hotel_Room_Booking_System/public/uploads/rooms/" . $fileName;
        }
    }

    $amenities = json_encode($amenitiesArray);

    $result = $roomTypeModel->updateRoomType(
        $connection,
        $id,
        $name,
        $description,
        $price_per_night,
        $max_capacity,
        $thumbnail_path,
        $amenities
    );

    if($result){
        Header("Location: ../views/admin/roomTypeList.php");
        exit();
    }else{
        echo "Room Type Not Updated";
    }
}

/* =========================
   DELETE ROOM TYPE
========================= */

if($action == "delete"){

    $id = $_GET["id"];

    $usedResult = $roomTypeModel->checkRoomTypeUsed($connection, $id);

    if($usedResult->num_rows > 0){
        $_SESSION["deleteRoomTypeError"] = "This room type is used by rooms. Cannot delete.";
        Header("Location: ../views/admin/roomTypeList.php");
        exit();
    }

    $roomTypeModel->deleteRoomType($connection, $id);

    Header("Location: ../views/admin/roomTypeList.php");
    exit();
}

?>