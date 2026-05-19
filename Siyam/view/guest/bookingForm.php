<?php
include_once "../../config/auth.php";
include_once "../../models/UserModel.php";
requireLogin();

$connection = getConnectionObject();
$userModel = new UserModel();
$user = $userModel->getUserById($connection, $_SESSION["user_id"])->fetch_assoc();
?>
<html>
<head>
<title>Booking Form</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="menu">
<a href="profile.php">Profile</a>
<a href="searchRoom.php">Search Room</a>
<a href="myBookings.php">My Bookings</a>
</div>

<div class="container">
<h2>Confirm Booking</h2>

<form method="post" action="../../controllers/BookingController.php">
<input type="hidden" name="room_type_id" value="<?php echo $_GET["room_type_id"]; ?>">

<table>
<tr><td>Guest Name</td><td><input type="text" value="<?php echo $user["name"]; ?>" readonly></td></tr>
<tr><td>Phone</td><td><input type="text" value="<?php echo $user["phone"]; ?>" readonly></td></tr>
<tr><td>Check-in</td><td><input type="date" name="checkin_date" value="<?php echo $_GET["checkin"]; ?>" readonly></td></tr>
<tr><td>Check-out</td><td><input type="date" name="checkout_date" value="<?php echo $_GET["checkout"]; ?>" readonly></td></tr>
<tr><td>Total Price</td><td><input type="text" name="total_price" value="<?php echo $_GET["total"]; ?>" readonly></td></tr>
<tr><td></td><td><input type="submit" value="Confirm Booking"></td></tr>
</table>
</form>
</div>
</body>
</html>
