<?php
include_once "../../config/auth.php";
include_once "../../models/RoomModel.php";
requireAdmin();

$connection = getConnectionObject();
$roomModel = new RoomModel();
$result = $roomModel->getAllRooms($connection);
?>
<html>
<head>
<title>Room List</title>
<link rel="stylesheet" href="../style.css">
<script src="../../controllers/JS/toggleRoomStatus.js"></script>
</head>
<body>
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
<h2>Room List</h2>
<p class="error"><?php echo $_SESSION["deleteError"] ?? ""; unset($_SESSION["deleteError"]); ?></p>

<table>
<tr>
<th>ID</th>
<th>Room Number</th>
<th>Floor</th>
<th>Room Type</th>
<th>Occupancy</th>
<th>Status Toggle</th>
<th>Action</th>
</tr>

<?php
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){

        $id = $row["id"];

        if($row["status"] == "maintenance"){
            $occupancy = "Maintenance";
            $class = "maintenance";
        }else if($row["booking_id"]){
            $occupancy = "Booked";
            $class = "booked";
        }else{
            $occupancy = "Available";
            $class = "available";
        }

        if($occupancy == "Booked"){
            $toggleButton = "<button class='badge booked' disabled>Booked</button> <span class='error'>Cannot change</span>";
        }else{
            $toggleButton = "<button id='statusBtn$id' class='badge $class' onclick='toggleRoomStatus($id)'>".$row["status"]."</button> <span id='statusResponse$id'></span>";
        }

        echo "<tr>
        <td>".$id."</td>
        <td>".$row["room_number"]."</td>
        <td>".$row["floor"]."</td>
        <td>".$row["room_type_name"]."</td>
        <td><span class='badge ".$class."'>".$occupancy."</span></td>
        <td>".$toggleButton."</td>
        <td>
            <a class='edit' href='editRoom.php?id=".$id."'>Edit</a> |
            <a class='delete' href='../../controllers/RoomController.php?action=delete&id=".$id."'>Delete</a>
        </td>
        </tr>";
    }
}
?>
</table>
</div>
</body>
</html>
