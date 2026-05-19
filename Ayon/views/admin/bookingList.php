<?php
include_once "../../config/auth.php";
include_once "../../models/BookingModel.php";
requireAdmin();

$connection = getConnectionObject();
$bookingModel = new BookingModel();

$status = $_GET["status"] ?? "";
$fromDate = $_GET["from_date"] ?? "";
$toDate = $_GET["to_date"] ?? "";

$result = $bookingModel->getBookings($connection, $status, $fromDate, $toDate);
?>
<html>
<head>
<title>Booking List</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="menu">
<a href="dashboard.php">Dashboard</a>
<a href="roomTypeList.php">Room Types</a>
<a href="roomList.php">Rooms</a>
<a href="bookingList.php">Bookings</a>
<a href="../../controllers/AuthController.php?action=logout">Logout</a>
</div>

<div class="container">
<h2>Admin Booking List</h2>

<form method="get" action="bookingList.php">

<table>
<tr>
<td>Status</td>
<td>
<select name="status">
<option value="">All</option>
<option value="Pending" <?php if($status=="Pending"){echo "selected";}?>>Pending</option>
<option value="Confirmed" <?php if($status=="Confirmed"){echo "selected";}?>>Confirmed</option>
<option value="Checked-In" <?php if($status=="Checked-In"){echo "selected";}?>>Checked-In</option>
<option value="Checked-Out" <?php if($status=="Checked-Out"){echo "selected";}?>>Checked-Out</option>
<option value="Cancelled" <?php if($status=="Cancelled"){echo "selected";}?>>Cancelled</option>
</select>
</td>
</tr>

<tr>
<td>From Check-in Date</td>
<td>
<input type="date" name="from_date" value="<?php echo $fromDate; ?>">
</td>
</tr>

<tr>
<td>To Check-in Date</td>
<td>
<input type="date" name="to_date" value="<?php echo $toDate; ?>">
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Filter">
<a class="btn" href="bookingList.php">Reset</a>
</td>
</tr>
</table>

</form>
<table>
<tr>
<th>Booking ID</th>
<th>Guest</th>
<th>Room</th>
<th>Room Type</th>
<th>Check-in</th>
<th>Check-out</th>
<th>Total</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        echo "<tr>
        <td>".$row["id"]."</td>
        <td>".$row["guest_name"]."</td>
        <td>".$row["room_number"]."</td>
        <td>".$row["room_type_name"]."</td>
        <td>".$row["checkin_date"]."</td>
        <td>".$row["checkout_date"]."</td>
        <td>".$row["total_price"]."</td>
        <td><span class='badge checked'>".$row["status"]."</span></td>
        <td><a class='edit' href='bookingDetail.php?id=".$row["id"]."'>Details</a></td>
        </tr>";
    }
}
?>
</table>
</div>
</body>
</html>
