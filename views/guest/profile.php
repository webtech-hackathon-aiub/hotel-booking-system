<?php
include_once "../../config/auth.php";
include_once "../../models/RoomTypeModel.php";
requireLogin();

$connection = getConnectionObject();

$userModel = new UserModel();
$typeModel = new RoomTypeModel();

$user = $userModel->getUserById($connection, $_SESSION["user_id"])->fetch_assoc();
$roomTypes = $typeModel->getAllRoomTypes($connection);
$upcoming = $userModel->getUpcomingBooking($connection, $_SESSION["user_id"]);

$profileSuccess = $_SESSION["profileSuccess"] ?? "";
$profileError = $_SESSION["profileError"] ?? "";
unset($_SESSION["profileSuccess"]);
unset($_SESSION["profileError"]);
?>
<html>
<head>
<title>Guest Profile</title>
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
<h2>Guest Profile</h2>

<p class="success"><?php echo $profileSuccess; ?></p>
<p class="error"><?php echo $profileError; ?></p>

<form method="post" action="../../controllers/ProfileController.php">
<table>
<tr><td>Name</td><td><input type="text" name="name" value="<?php echo $user["name"]; ?>"></td></tr>
<tr><td>Email</td><td><input type="email" name="email" value="<?php echo $user["email"]; ?>"></td></tr>
<tr><td>Phone</td><td><input type="text" name="phone" value="<?php echo $user["phone"]; ?>"></td></tr>
<tr><td>Nationality</td><td><input type="text" name="nationality" value="<?php echo $user["nationality"]; ?>"></td></tr>
<tr>
<td>Preferred Room Type</td>
<td>
<select name="preferred_room_type_id">
<option value="">No preference</option>
<?php
if($roomTypes->num_rows > 0){
    while($type = $roomTypes->fetch_assoc()){
        if($type["id"] == $user["preferred_room_type_id"]){
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
<tr><td>Special Requests</td><td><textarea name="special_requests"><?php echo $user["special_requests"]; ?></textarea></td></tr>
<tr><td>Subscribe Offers</td><td><input type="checkbox" name="subscribe_offers" value="1" <?php if(isset($_COOKIE["subscribe_offers"]) && $_COOKIE["subscribe_offers"]=="1"){echo "checked";}?>> Subscribe</td></tr>
<tr><td></td><td><input type="submit" value="Update Profile"></td></tr>
</table>
</form>

<h3>Upcoming Booking</h3>
<?php
if($upcoming->num_rows > 0){
    $b = $upcoming->fetch_assoc();
    echo "<div class='stat-card'>
    <h3>".$b["room_type_name"]."</h3>
    <p>".$b["checkin_date"]." to ".$b["checkout_date"]."</p>
    <span class='badge confirmed'>".$b["status"]."</span>
    </div>";
}else{
    echo "<p class='empty-message'>No upcoming stays.</p>";
}
?>
</div>
</body>
</html>
