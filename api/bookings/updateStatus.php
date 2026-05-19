<?php

header("Content-Type: application/json");

include_once "../../config/DatabaseConnection.php";
include_once "../../models/BookingModel.php";

$booking_id = $_POST["booking_id"];
$status = $_POST["status"];

$db = new DatabaseConnection();
$connection = $db->openConnection();

$bookingModel = new BookingModel();

$result = $bookingModel->updateBookingStatus($connection, $booking_id, $status);

if($result){
    echo json_encode(array("status"=>"success"));
}else{
    echo json_encode(array("status"=>"error"));
}

?>