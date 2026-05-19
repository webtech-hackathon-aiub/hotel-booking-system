<?php

include_once "../../config/auth.php";
include_once "../../models/BookingModel.php";

requireAdmin();

$connection = getConnectionObject();
$bookingModel = new BookingModel();

$id = $_GET["id"];

$result = $bookingModel->getBookingById($connection, $id);
$row = $result->fetch_assoc();

$history = $bookingModel->getBookingHistoryByGuest($connection, $row["user_id"]);

?>

<html>
<head>
<title>Booking Detail</title>
<link rel="stylesheet" href="../style.css">
<script src="../../controllers/JS/bookingActions.js"></script>
</head>

<body>

<div class="menu">
<a href="dashboard.php">Dashboard</a>
<a href="bookingList.php">Booking List</a>
</div>

<div class="container">

<h2>Booking Detail</h2>

<p id="message"></p>

<table>

<tr>
<td>Booking ID</td>
<td><?php echo $row["id"]; ?></td>
</tr>

<tr>
<td>Guest</td>
<td><?php echo $row["guest_name"]; ?></td>
</tr>

<tr>
<td>Email</td>
<td><?php echo $row["email"]; ?></td>
</tr>

<tr>
<td>Phone</td>
<td><?php echo $row["phone"]; ?></td>
</tr>

<tr>
<td>Room</td>
<td><?php echo $row["room_number"]; ?></td>
</tr>

<tr>
<td>Room Type</td>
<td><?php echo $row["room_type_name"]; ?></td>
</tr>

<tr>
<td>Check-in</td>
<td><?php echo $row["checkin_date"]; ?></td>
</tr>

<tr>
<td>Check-out</td>
<td><?php echo $row["checkout_date"]; ?></td>
</tr>

<tr>
<td>Total Price</td>
<td><?php echo $row["total_price"]; ?></td>
</tr>

<tr>
<td>Status</td>
<td id="bookingStatus"><?php echo $row["status"]; ?></td>
</tr>

<tr>
<td>Actual Check-in</td>
<td><?php echo $row["actual_checkin"]; ?></td>
</tr>

<tr>
<td>Action</td>
<td id="actionArea">

<?php

if($row["status"] == "Pending"){

    echo "<button class='btn' onclick='updateBookingStatus(".$row["id"].", \"Confirmed\")'>Confirm</button> ";

    echo "<button class='btn danger-btn' onclick='updateBookingStatus(".$row["id"].", \"Cancelled\")'>Cancel</button>";

}
else if($row["status"] == "Confirmed"){

    echo "<button class='btn' onclick='checkInBooking(".$row["id"].")'>Check In</button> ";

    echo "<button class='btn danger-btn' onclick='updateBookingStatus(".$row["id"].", \"Cancelled\")'>Cancel</button>";

}
else if($row["status"] == "Checked-In"){

    echo "<button class='btn' onclick='checkOutBooking(".$row["id"].")'>Check Out</button>";

}
else{

    echo "No action available";
}

?>

</td>
</tr>

</table>

<h3>Guest Booking History</h3>

<table>
<tr>
<th>ID</th>
<th>Room</th>
<th>Check-in</th>
<th>Check-out</th>
<th>Total</th>
<th>Status</th>
</tr>

<?php

if($history->num_rows > 0){

    while($h = $history->fetch_assoc()){

        echo "<tr>
        <td>".$h["id"]."</td>
        <td>".$h["room_type_name"]." - ".$h["room_number"]."</td>
        <td>".$h["checkin_date"]."</td>
        <td>".$h["checkout_date"]."</td>
        <td>".$h["total_price"]."</td>
        <td>".$h["status"]."</td>
        </tr>";
    }
}

?>

</table>

</div>

</body>
</html>