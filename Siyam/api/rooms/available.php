<?php

header("Content-Type: application/json");

include_once "../../config/DatabaseConnection.php";
include_once "../../models/RoomModel.php";

$checkin = $_GET["checkin"] ?? "";
$checkout = $_GET["checkout"] ?? "";
$guests = $_GET["guests"] ?? "";

$db = new DatabaseConnection();
$connection = $db->openConnection();

$roomModel = new RoomModel();

$data = array();

if($checkin && $checkout && $guests){

    $result = $roomModel->getAvailableRoomTypes($connection, $checkin, $checkout, $guests);

    if($result->num_rows > 0){

        while($row = $result->fetch_assoc()){

            $price = $row["price_per_night"];
            $days = (strtotime($checkout) - strtotime($checkin)) / (60 * 60 * 24);

            if($days < 1){
                $days = 1;
            }

            $total = $price * $days;

            $data[] = array(
                "id" => $row["id"],
                "name" => $row["name"],
                "description" => $row["description"],
                "price" => $price,
                "total_price" => $total,
                "amenities" => json_decode($row["amenities"], true),
                "thumbnail" => $row["thumbnail_path"]
            );
        }
    }
}

echo json_encode($data);

?>
