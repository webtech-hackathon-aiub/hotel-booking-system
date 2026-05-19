<?php
include_once "../../config/auth.php";
include_once "../../models/BookingModel.php";
include_once "../../models/DashboardModel.php";
requireAdmin();

$connection = getConnectionObject();
$bookingModel = new BookingModel();
$dashboardModel = new DashboardModel();

$arrivals = $bookingModel->getTodayArrivals($connection);
$departures = $bookingModel->getTodayDepartures($connection);

$totalResult = $dashboardModel->getTotalRooms($connection)->fetch_assoc();
$occupiedResult = $dashboardModel->getOccupiedRooms($connection)->fetch_assoc();
$availableResult = $dashboardModel->getAvailableRooms($connection)->fetch_assoc();
$maintenanceResult = $dashboardModel->getMaintenanceRooms($connection)->fetch_assoc();
?>
<html>
<head>
<title>Admin Dashboard</title>
<link rel="stylesheet" href="../style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../../controllers/JS/revenueChart.js"></script>
</head>
<body onload="loadRevenueChart()">
<div class="menu">
<a href="dashboard.php">Dashboard</a>
<a href="addRoomType.php">Add Room Type</a>
<a href="roomTypeList.php">Room Types</a>
<a href="addRoom.php">Add Room</a>
<a href="roomList.php">Rooms</a>
<a href="bookingList.php">Bookings</a>
<a href="../../controllers/AuthController.php?action=logout">Logout</a>
</div>

<div class="container">
<h2>Admin Occupancy Dashboard</h2>

<div class="card-row">
    <div class="stat-card"><h3>Total Rooms</h3><p><?php echo $totalResult["total_rooms"]; ?></p></div>
    <div class="stat-card"><h3>Occupied</h3><p><?php echo $occupiedResult["occupied_rooms"]; ?></p></div>
    <div class="stat-card"><h3>Available</h3><p><?php echo $availableResult["available_rooms"]; ?></p></div>
    <div class="stat-card"><h3>Maintenance</h3><p><?php echo $maintenanceResult["maintenance_rooms"]; ?></p></div>
</div>

<h3>Today's Arrivals</h3>
<table>
<tr><th>Booking ID</th><th>Guest</th><th>Room</th><th>Room Type</th><th>Status</th></tr>
<?php
if($arrivals->num_rows > 0){
    while($row = $arrivals->fetch_assoc()){
        echo "<tr><td>".$row["id"]."</td><td>".$row["guest_name"]."</td><td>".$row["room_number"]."</td><td>".$row["room_type_name"]."</td><td>".$row["status"]."</td></tr>";
    }
}
?>
</table>

<h3>Today's Departures</h3>
<table>
<tr><th>Booking ID</th><th>Guest</th><th>Room</th><th>Room Type</th><th>Status</th></tr>
<?php
if($departures->num_rows > 0){
    while($row = $departures->fetch_assoc()){
        echo "<tr><td>".$row["id"]."</td><td>".$row["guest_name"]."</td><td>".$row["room_number"]."</td><td>".$row["room_type_name"]."</td><td>".$row["status"]."</td></tr>";
    }
}
?>
</table>

<h3>Revenue Past 8 Weeks</h3>
<div style="height:260px; max-width:900px; margin:auto;">
    <canvas id="revenueChart"></canvas>
</div>
</div>
</body>
</html>
