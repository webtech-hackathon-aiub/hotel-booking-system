<?php

header("Content-Type: application/json");

include_once "../../config/DatabaseConnection.php";
include_once "../../models/BookingModel.php";

$booking_id = $_POST["booking_id"] ?? "";

$db = new DatabaseConnection();
$connection = $db->openConnection();

$bookingModel = new BookingModel();

$result = $bookingModel->checkIn($connection, $booking_id);

if($result && $connection->affected_rows > 0){
    echo json_encode(array("status"=>"success", "new_status"=>"Checked-In"));
}else{
    echo json_encode(array("status"=>"error", "message"=>"Check in not allowed"));
}

?>
