<?php

header("Content-Type: application/json");

include_once "../../config/DatabaseConnection.php";
include_once "../../models/DashboardModel.php";

$db = new DatabaseConnection();
$connection = $db->openConnection();

$dashboardModel = new DashboardModel();

$result = $dashboardModel->getRevenuePastEightWeeks($connection);

$labels = array();
$revenues = array();

if($result->num_rows > 0){

    while($row = $result->fetch_assoc()){

        $labels[] = $row["week_no"];
        $revenues[] = (float)$row["revenue"];
    }
}

echo json_encode(array(
    "labels" => $labels,
    "revenues" => $revenues
));

?>
