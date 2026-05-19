<?php

class RoomModel{

    function addRoom($connection, $room_type_id, $room_number, $floor, $status){

        $sql = "INSERT INTO rooms(room_type_id, room_number, floor, status)
        VALUES(?, ?, ?, ?)";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("isis", $room_type_id, $room_number, $floor, $status);

        return $stmt->execute();
    }

    function checkRoomNumber($connection, $room_number){

        $sql = "SELECT * FROM rooms WHERE room_number=?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $room_number);
        $stmt->execute();

        return $stmt->get_result();
    }

    function checkRoomNumberForUpdate($connection, $room_number, $id){

        $sql = "SELECT * FROM rooms WHERE room_number=? AND id != ?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("si", $room_number, $id);
        $stmt->execute();

        return $stmt->get_result();
    }

    function getAllRooms($connection){

    $sql = "SELECT
        rooms.id,
        rooms.room_number,
        rooms.floor,
        rooms.status,
        room_types.name AS room_type_name,
        bookings.id AS booking_id
    FROM rooms
    INNER JOIN room_types ON rooms.room_type_id = room_types.id
    LEFT JOIN bookings ON rooms.id = bookings.room_id
    AND bookings.status IN('Confirmed','Checked-In')
    AND bookings.checkin_date <= CURDATE()
    AND bookings.checkout_date >= CURDATE()
    ORDER BY rooms.id DESC";

    return $connection->query($sql);
}

    function getRoomById($connection, $id){

        $sql = "SELECT * FROM rooms WHERE id=?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result();
    }

    function updateRoom($connection, $id, $room_type_id, $room_number, $floor){

        $sql = "UPDATE rooms SET room_type_id=?, room_number=?, floor=? WHERE id=?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("isii", $room_type_id, $room_number, $floor, $id);

        return $stmt->execute();
    }

    function deleteRoom($connection, $id){

        $sql = "DELETE FROM rooms WHERE id=?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    function checkFutureBooking($connection, $room_id){

        $sql = "SELECT * FROM bookings
        WHERE room_id=?
        AND checkin_date >= CURDATE()
        AND status IN('Confirmed','Checked-In')";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $room_id);
        $stmt->execute();

        return $stmt->get_result();
    }

    function toggleRoomStatus($connection, $id){

        $bookingResult = $this->checkFutureBooking($connection, $id);

        if($bookingResult->num_rows > 0){
            return "booked";
        }

        $sql = "SELECT status FROM rooms WHERE id=?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows > 0){

            $row = $result->fetch_assoc();

            if($row["status"] == "available"){
                $newStatus = "maintenance";
            }else{
                $newStatus = "available";
            }

            $updateSql = "UPDATE rooms SET status=? WHERE id=?";
            $updateStmt = $connection->prepare($updateSql);
            $updateStmt->bind_param("si", $newStatus, $id);
            $updateStmt->execute();

            return $newStatus;
        }

        return "";
    }

    function getAvailableRoomTypes($connection, $checkin_date, $checkout_date, $guests){

        $sql = "SELECT DISTINCT
            room_types.id,
            room_types.name,
            room_types.description,
            room_types.price_per_night,
            room_types.max_capacity,
            room_types.thumbnail_path,
            room_types.amenities
        FROM room_types
        INNER JOIN rooms ON room_types.id = rooms.room_type_id
        WHERE room_types.max_capacity >= ?
        AND rooms.status = 'available'
        AND rooms.id NOT IN(
            SELECT bookings.room_id
            FROM bookings
            WHERE bookings.status IN('Confirmed','Checked-In','Pending')
            AND (
                (? BETWEEN bookings.checkin_date AND bookings.checkout_date)
                OR
                (? BETWEEN bookings.checkin_date AND bookings.checkout_date)
                OR
                (bookings.checkin_date BETWEEN ? AND ?)
            )
        )";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("issss", $guests, $checkin_date, $checkout_date, $checkin_date, $checkout_date);
        $stmt->execute();

        return $stmt->get_result();
    }

    function getAvailableRoom($connection, $room_type_id, $checkin_date, $checkout_date){

        $sql = "SELECT rooms.*
        FROM rooms
        WHERE rooms.room_type_id=?
        AND rooms.status='available'
        AND rooms.id NOT IN(
            SELECT bookings.room_id
            FROM bookings
            WHERE bookings.status IN('Confirmed','Checked-In','Pending')
            AND (
                (? BETWEEN bookings.checkin_date AND bookings.checkout_date)
                OR
                (? BETWEEN bookings.checkin_date AND bookings.checkout_date)
                OR
                (bookings.checkin_date BETWEEN ? AND ?)
            )
        )
        LIMIT 1";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("issss", $room_type_id, $checkin_date, $checkout_date, $checkin_date, $checkout_date);
        $stmt->execute();

        return $stmt->get_result();
    }
}

?>
