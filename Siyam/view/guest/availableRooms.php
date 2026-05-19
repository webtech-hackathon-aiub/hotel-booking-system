<?php
include_once "../../config/auth.php";
requireLogin();
?>
<html>
<head>
<title>Available Rooms</title>
<link rel="stylesheet" href="../style.css">
<script src="../../controllers/JS/loadRooms.js"></script>
</head>
<body onload="loadRooms()">
<div class="menu">
<a href="profile.php">Profile</a>
<a href="searchRoom.php">Search Room</a>
<a href="myBookings.php">My Bookings</a>
<a href="../../controllers/AuthController.php?action=logout">Logout</a>
</div>

<div class="container">
<h2>Available Rooms</h2>
<div id="rooms"></div>
</div>
</body>
</html>
