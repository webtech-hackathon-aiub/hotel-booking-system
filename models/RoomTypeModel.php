<?php

class RoomTypeModel{

    function addRoomType($connection, $name, $description, $price_per_night, $max_capacity, $thumbnail_path, $amenities){

        $sql = "INSERT INTO room_types(name, description, price_per_night, max_capacity, thumbnail_path, amenities)
        VALUES(?, ?, ?, ?, ?, ?)";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssdiss", $name, $description, $price_per_night, $max_capacity, $thumbnail_path, $amenities);

        return $stmt->execute();
    }

    function getAllRoomTypes($connection){

        $sql = "SELECT * FROM room_types ORDER BY id DESC";

        return $connection->query($sql);
    }

    function getRoomTypeById($connection, $id){

        $sql = "SELECT * FROM room_types WHERE id=?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result();
    }

    function updateRoomType($connection, $id, $name, $description, $price_per_night, $max_capacity, $thumbnail_path, $amenities){

        if($thumbnail_path){

            $sql = "UPDATE room_types
            SET name=?, description=?, price_per_night=?, max_capacity=?, thumbnail_path=?, amenities=?
            WHERE id=?";

            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ssdissi", $name, $description, $price_per_night, $max_capacity, $thumbnail_path, $amenities, $id);

        }else{

            $sql = "UPDATE room_types
            SET name=?, description=?, price_per_night=?, max_capacity=?, amenities=?
            WHERE id=?";

            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ssdisi", $name, $description, $price_per_night, $max_capacity, $amenities, $id);
        }

        return $stmt->execute();
    }

    function deleteRoomType($connection, $id){

        $sql = "DELETE FROM room_types WHERE id=?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    function checkRoomTypeUsed($connection, $id){

        $sql = "SELECT * FROM rooms WHERE room_type_id=?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result();
    }
}

?>
