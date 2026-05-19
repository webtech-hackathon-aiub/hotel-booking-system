<?php
include_once "../../config/auth.php";
requireLogin();

$id = $_GET["id"];
$connection = getConnectionObject();

$sql = "SELECT bookings.*, room_types.name AS room_type_name
FROM bookings
INNER JOIN rooms ON bookings.room_id = rooms.id
INNER JOIN room_types ON rooms.room_type_id = room_types.id
WHERE bookings.id='$id'";

$result = $connection->query($sql);
$data = $result->fetch_assoc();
?>
<html>
<head>
<title>Booking Confirmation</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="menu">
<a href="profile.php">Profile</a>
<a href="searchRoom.php">Search Room</a>
<a href="myBookings.php">My Bookings</a>
</div>

<div class="container">
<h2>Booking Confirmation</h2>
<div class="stat-card">
<h3>Booking #<?php echo $data["id"]; ?></h3>
<p>Room Type: <?php echo $data["room_type_name"]; ?></p>
<p>Check-in: <?php echo $data["checkin_date"]; ?></p>
<p>Check-out: <?php echo $data["checkout_date"]; ?></p>
<p>Total Price: <?php echo $data["total_price"]; ?></p>
<span class="badge pending"><?php echo $data["status"]; ?></span>
</div>
</div>
</body>
</html>
