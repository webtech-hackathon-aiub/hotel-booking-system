<?php
include_once "../../config/auth.php";
requireLogin();
?>
<html>
<head>
<title>Search Room</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="menu">
<a href="profile.php">Profile</a>
<a href="searchRoom.php">Search Room</a>
<a href="myBookings.php">My Bookings</a>
<a href="../../controllers/AuthController.php?action=logout">Logout</a>
</div>

<div class="container">
<h2>Search Available Rooms</h2>
<form method="get" action="availableRooms.php">
<table>
<tr><td>Check-in Date</td><td><input type="date" name="checkin" required></td></tr>
<tr><td>Check-out Date</td><td><input type="date" name="checkout" required></td></tr>
<tr><td>Guests</td><td><input type="number" name="guests" value="1" min="1" required></td></tr>
<tr><td></td><td><input type="submit" value="Search"></td></tr>
</table>
</form>
</div>
</body>
</html>
