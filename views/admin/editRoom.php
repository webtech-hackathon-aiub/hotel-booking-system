<?php
include_once "../../config/auth.php";
include_once "../../models/RoomModel.php";
include_once "../../models/RoomTypeModel.php";
requireAdmin();

$connection = getConnectionObject();
$roomModel = new RoomModel();
$typeModel = new RoomTypeModel();

$id = $_GET["id"];
$result = $roomModel->getRoomById($connection, $id);
$row = $result->fetch_assoc();
$roomTypes = $typeModel->getAllRoomTypes($connection);
?>
<html>
<head>
<title>Edit Room</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="menu">
<a href="dashboard.php">Dashboard</a>
<a href="roomList.php">Rooms</a>
</div>

<div class="container">
<h2>Edit Room</h2>

<form method="post" action="../../controllers/RoomController.php">
<input type="hidden" name="action" value="update">
<input type="hidden" name="id" value="<?php echo $id; ?>">

<table>
<tr>
<td>Room Number</td>
<td><input type="text" name="room_number" value="<?php echo $row["room_number"]; ?>"></td>
<td class="error"><?php echo $_SESSION["roomNumberError"] ?? ""; unset($_SESSION["roomNumberError"]); ?></td>
</tr>

<tr>
<td>Floor</td>
<td><input type="number" name="floor" value="<?php echo $row["floor"]; ?>"></td>
</tr>

<tr>
<td>Room Type</td>
<td>
<select name="room_type_id">
<?php
if($roomTypes->num_rows > 0){
    while($type = $roomTypes->fetch_assoc()){
        if($type["id"] == $row["room_type_id"]){
            echo "<option value='".$type["id"]."' selected>".$type["name"]."</option>";
        }else{
            echo "<option value='".$type["id"]."'>".$type["name"]."</option>";
        }
    }
}
?>
</select>
</td>
</tr>

<tr>
<td>Current Status</td>
<td><?php echo $row["status"]; ?></td>
</tr>

<tr>
<td></td>
<td><input type="submit" value="Update Room"></td>
</tr>
</table>
</form>
</div>
</body>
</html>
