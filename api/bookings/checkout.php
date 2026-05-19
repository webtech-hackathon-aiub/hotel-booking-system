<?php

header("Content-Type: application/json");

include_once "../../config/DatabaseConnection.php";
include_once "../../models/BookingModel.php";

$booking_id = $_POST["booking_id"] ?? "";

$db = new DatabaseConnection();
$connection = $db->openConnection();

$bookingModel = new BookingModel();

$result = $bookingModel->checkOut($connection, $booking_id);

if($result && $connection->affected_rows > 0){

    echo json_encode(array(
        "status" => "success",
        "new_status" => "Checked-Out"
    ));

}else{

    echo json_encode(array(
        "status" => "error",
        "message" => "Check out not allowed"
    ));
}

?>
