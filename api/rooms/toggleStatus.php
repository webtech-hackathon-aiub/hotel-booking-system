<?php

header("Content-Type: text/plain");

include_once "../../config/DatabaseConnection.php";
include_once "../../models/RoomModel.php";

$id = $_POST["id"];

$db = new DatabaseConnection();
$connection = $db->openConnection();

$roomModel = new RoomModel();

echo $roomModel->toggleRoomStatus($connection, $id);

?>
