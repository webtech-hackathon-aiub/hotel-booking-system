<?php

header("Content-Type: application/json");

include_once "../../config/DatabaseConnection.php";
include_once "../../models/BookingModel.php";

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$booking_id = $_POST["booking_id"];
$user_id = $_SESSION["user_id"];

$db = new DatabaseConnection();
$connection = $db->openConnection();

$bookingModel = new BookingModel();

$result = $bookingModel->cancelBooking($connection, $booking_id, $user_id);

if($result && $connection->affected_rows > 0){
    echo json_encode(array("status"=>"success"));
}else{
    echo json_encode(array("status"=>"error", "message"=>"Cancel not allowed"));
}

?>
