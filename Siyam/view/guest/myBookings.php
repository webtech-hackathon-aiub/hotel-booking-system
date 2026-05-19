<?php
include_once "../../config/auth.php";
include_once "../../models/BookingModel.php";
requireLogin();

$connection = getConnectionObject();
$bookingModel = new BookingModel();
$result = $bookingModel->getUserBookings($connection, $_SESSION["user_id"]);
?>
<html>
<head>
<title>My Bookings</title>
<link rel="stylesheet" href="../style.css">
<script src="../../controllers/JS/cancelBooking.js"></script>
</head>
<body>
<div class="menu">
<a href="profile.php">Profile</a>
<a href="searchRoom.php">Search Room</a>
<a href="myBookings.php">My Bookings</a>
<a href="../../controllers/AuthController.php?action=logout">Logout</a>
</div>

<div class="container">
<h2>My Bookings</h2>

<table>
<tr>
<th>ID</th>
<th>Room Type</th>
<th>Room Number</th>
<th>Check-in</th>
<th>Check-out</th>
<th>Total</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $id = $row["id"];
        echo "<tr>
        <td>".$id."</td>
        <td>".$row["room_type_name"]."</td>
        <td>".$row["room_number"]."</td>
        <td>".$row["checkin_date"]."</td>
        <td>".$row["checkout_date"]."</td>
        <td>".$row["total_price"]."</td>
        <td><span id='status".$id."' class='badge checked'>".$row["status"]."</span></td>
        <td>";

        if(($row["status"] == "Pending" || $row["status"] == "Confirmed") && $row["checkin_date"] > date("Y-m-d", strtotime("+1 day"))){
            echo "<button id='btn".$id."' onclick='cancelBooking(".$id.")'>Cancel</button>";
        }else{
            echo "No action";
        }

        echo "</td></tr>";
    }
}
?>
</table>
</div>
</body>
</html>
