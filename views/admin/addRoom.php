<?php
include_once "../../config/auth.php";
include_once "../../models/RoomTypeModel.php";
requireAdmin();

$connection = getConnectionObject();
$roomTypeModel = new RoomTypeModel();
$roomTypes = $roomTypeModel->getAllRoomTypes($connection);
?>
<html>
<head>
<title>Add Room</title>
<link rel="stylesheet" href="../style.css">
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
<h2>Add Room</h2>

<form method="post" action="../../controllers/RoomController.php">
<input type="hidden" name="action" value="add">

<table>
<tr>
<td>Room Number</td>
<td><input type="text" name="room_number"></td>
<td class="error"><?php echo $_SESSION["roomNumberError"] ?? ""; unset($_SESSION["roomNumberError"]); ?></td>
</tr>

<tr>
<td>Floor</td>
<td><input type="number" name="floor"></td>
</tr>

<tr>
<td>Room Type</td>
<td>
<select name="room_type_id">
<?php
if($roomTypes->num_rows > 0){
    while($row = $roomTypes->fetch_assoc()){
        echo "<option value='".$row["id"]."'>".$row["name"]."</option>";
    }
}
?>
</select>
</td>
</tr>

<tr>
<td>Status</td>
<td>
<select name="status">
<option value="available">available</option>
<option value="maintenance">maintenance</option>
</select>
</td>
</tr>

<tr>
<td></td>
<td><input type="submit" value="Add Room"></td>
</tr>
</table>
</form>
</div>
</body>
</html>
