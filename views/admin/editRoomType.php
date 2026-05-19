<?php
include_once "../../config/auth.php";
include_once "../../models/RoomTypeModel.php";
requireAdmin();

$connection = getConnectionObject();
$roomTypeModel = new RoomTypeModel();

$id = $_GET["id"];
$result = $roomTypeModel->getRoomTypeById($connection, $id);
$row = $result->fetch_assoc();

$amenities = json_decode($row["amenities"], true);
if(!$amenities){
    $amenities = array();
}
?>
<html>
<head>
<title>Edit Room Type</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="menu">
<a href="dashboard.php">Dashboard</a>
<a href="roomTypeList.php">Room Types</a>
<a href="roomList.php">Rooms</a>
</div>

<div class="container">
<h2>Edit Room Type</h2>

<form method="post" action="../../controllers/RoomTypeController.php" enctype="multipart/form-data">
<input type="hidden" name="action" value="update">
<input type="hidden" name="id" value="<?php echo $id; ?>">

<table>
<tr>
<td>Name</td>
<td><input type="text" name="name" value="<?php echo $row["name"]; ?>"></td>
</tr>

<tr>
<td>Description</td>
<td><textarea name="description"><?php echo $row["description"]; ?></textarea></td>
</tr>

<tr>
<td>Price Per Night</td>
<td><input type="number" name="price_per_night" value="<?php echo $row["price_per_night"]; ?>"></td>
</tr>

<tr>
<td>Max Capacity</td>
<td><input type="number" name="max_capacity" value="<?php echo $row["max_capacity"]; ?>"></td>
</tr>

<tr>
<td>Current Image</td>
<td><img src="<?php echo $row["thumbnail_path"]; ?>" height="90" width="120"></td>
</tr>

<tr>
<td>New Image</td>
<td><input type="file" name="thumbnail"></td>
</tr>

<tr>
<td>Amenities</td>
<td>
<label><input type="checkbox" name="amenities[]" value="WiFi" <?php if(in_array("WiFi", $amenities)){echo "checked";}?>> WiFi</label>
<label><input type="checkbox" name="amenities[]" value="AC" <?php if(in_array("AC", $amenities)){echo "checked";}?>> AC</label>
<label><input type="checkbox" name="amenities[]" value="TV" <?php if(in_array("TV", $amenities)){echo "checked";}?>> TV</label>
<label><input type="checkbox" name="amenities[]" value="Mini-bar" <?php if(in_array("Mini-bar", $amenities)){echo "checked";}?>> Mini-bar</label>
<label><input type="checkbox" name="amenities[]" value="Safe" <?php if(in_array("Safe", $amenities)){echo "checked";}?>> Safe</label>
<label><input type="checkbox" name="amenities[]" value="Bathtub" <?php if(in_array("Bathtub", $amenities)){echo "checked";}?>> Bathtub</label>
<label><input type="checkbox" name="amenities[]" value="Balcony" <?php if(in_array("Balcony", $amenities)){echo "checked";}?>> Balcony</label>
</td>
</tr>

<tr>
<td></td>
<td><input type="submit" value="Update Room Type"></td>
</tr>
</table>
</form>
</div>
</body>
</html>
