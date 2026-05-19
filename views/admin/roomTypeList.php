<?php
include_once "../../config/auth.php";
include_once "../../models/RoomTypeModel.php";
requireAdmin();

$connection = getConnectionObject();
$roomTypeModel = new RoomTypeModel();
$result = $roomTypeModel->getAllRoomTypes($connection);
?>
<html>
<head>
<title>Room Type List</title>
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

<h2>Room Type List</h2>

<p class="error">
<?php
echo $_SESSION["deleteRoomTypeError"] ?? "";
unset($_SESSION["deleteRoomTypeError"]);
?>
</p>

<table>
<tr>
<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Description</th>
<th>Price</th>
<th>Capacity</th>
<th>Amenities</th>
<th>Action</th>
</tr>

<?php
if($result->num_rows > 0){

    while($row = $result->fetch_assoc()){

        $amenities = json_decode($row["amenities"], true);
        $amenityText = "";

        if($amenities){
            foreach($amenities as $amenity){
                $amenityText .= "<span class='badge checked'>".$amenity."</span> ";
            }
        }
?>

<tr>
<td><?php echo $row["id"]; ?></td>

<td>
<<img
src="<?php echo $row['thumbnail_path']; ?>"
width="100"
height="70"
style="object-fit:cover; border-radius:8px;"
>
</td>

<td><?php echo $row["name"]; ?></td>
<td><?php echo $row["description"]; ?></td>
<td><?php echo $row["price_per_night"]; ?></td>
<td><?php echo $row["max_capacity"]; ?></td>
<td><?php echo $amenityText; ?></td>

<td>
<a class="edit" href="editRoomType.php?id=<?php echo $row["id"]; ?>">Edit</a> |
<a class="delete" href="../../controllers/RoomTypeController.php?action=delete&id=<?php echo $row["id"]; ?>">Delete</a>
</td>
</tr>

<?php
    }
}
?>

</table>
</div>
</body>
</html>