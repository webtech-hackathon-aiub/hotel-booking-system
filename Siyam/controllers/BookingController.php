<?php

include_once "../config/DatabaseConnection.php";
include_once "../models/RoomModel.php";
include_once "../models/BookingModel.php";

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION["user_id"])){
    Header("Location: ../views/auth/login.php");
    exit();
}

$db = new DatabaseConnection();
$connection = $db->openConnection();

$roomModel = new RoomModel();
$bookingModel = new BookingModel();

/* CHECK OUT PART */
if(isset($_POST["action"]) && $_POST["action"] == "checkout"){

    $booking_id = $_POST["booking_id"];
    $room_id = $_POST["room_id"];

    $sql1 = "UPDATE bookings 
             SET status='Checked-Out' 
             WHERE id='$booking_id'";

    $sql2 = "UPDATE rooms 
             SET status='available' 
             WHERE id='$room_id'";

    if($connection->query($sql1) && $connection->query($sql2)){
        $_SESSION["success"] = "Guest checked out successfully";
    }else{
        $_SESSION["error"] = "Checkout failed";
    }

    Header("Location: ../views/admin/manageBookings.php");
    exit();
}

if(isset($_POST["room_type_id"]))
{
    $room_type_id = $_POST["room_type_id"];
    $checkin_date = $_POST["checkin_date"];
    $checkout_date = $_POST["checkout_date"];
    $total_price = $_POST["total_price"];
    $user_id = $_SESSION["user_id"];

    $roomResult = $roomModel->getAvailableRoom(
        $connection,
        $room_type_id,
        $checkin_date,
        $checkout_date
    );

    if($roomResult->num_rows > 0){

        $room = $roomResult->fetch_assoc();
        $room_id = $room["id"];

        $result = $bookingModel->addBooking(
            $connection,
            $user_id,
            $room_id,
            $checkin_date,
            $checkout_date,
            $total_price
        );

        if($result){

            $booking = $bookingModel->getLastBooking($connection);
            $data = $booking->fetch_assoc();

            Header("Location: ../views/guest/confirmation.php?id=".$data["id"]);
            exit();
        }

    }else{
        echo "Room not available";
    }
}

?>