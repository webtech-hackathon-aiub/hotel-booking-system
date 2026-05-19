<?php
include_once "../../config/auth.php";
requireAdmin();
?>

<html>
<head>
<title>Add Room Type</title>
<link rel="stylesheet" href="../style.css">
<script src="../../controllers/JS/roomTypeValidation.js"></script>
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

<h2>Add Room Type</h2>

<p class="error" id="error"></p>

<form method="post"
action="../../controllers/RoomTypeController.php"
enctype="multipart/form-data"
onsubmit="return validateRoomType()">

<input type="hidden" name="action" value="add">

<table>

<tr>
<td>Name</td>
<td><input type="text" id="name" name="name"></td>
<td class="error">
<?php echo $_SESSION["nameError"] ?? ""; unset($_SESSION["nameError"]); ?>
</td>
</tr>

<tr>
<td>Description</td>
<td><textarea name="description" spellcheck="false"></textarea></td>
</tr>

<tr>
<td>Price Per Night</td>
<td><input type="number" id="price" name="price_per_night"></td>
<td class="error">
<?php echo $_SESSION["priceError"] ?? ""; unset($_SESSION["priceError"]); ?>
</td>
</tr>

<tr>
<td>Max Capacity</td>
<td><input type="number" id="capacity" name="max_capacity"></td>
<td class="error">
<?php echo $_SESSION["capacityError"] ?? ""; unset($_SESSION["capacityError"]); ?>
</td>
</tr>

<tr>
<td>Thumbnail</td>
<td><input type="file" name="thumbnail"></td>
<td class="error">
<?php echo $_SESSION["uploadError"] ?? ""; unset($_SESSION["uploadError"]); ?>
</td>
</tr>

<tr>
<td>Amenities</td>
<td>
<label><input type="checkbox" name="amenities[]" value="WiFi"> WiFi</label>
<label><input type="checkbox" name="amenities[]" value="AC"> AC</label>
<label><input type="checkbox" name="amenities[]" value="TV"> TV</label>
<label><input type="checkbox" name="amenities[]" value="Mini-bar"> Mini-bar</label>
<label><input type="checkbox" name="amenities[]" value="Safe"> Safe</label>
<label><input type="checkbox" name="amenities[]" value="Bathtub"> Bathtub</label>
<label><input type="checkbox" name="amenities[]" value="Balcony"> Balcony</label>
</td>
</tr>

<tr>
<td></td>
<td><input type="submit" value="Add Room Type"></td>
</tr>

</table>

</form>

</div>

</body>
</html>