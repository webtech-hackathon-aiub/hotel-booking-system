<?php

class BookingModel{

    function addBooking($connection, $user_id, $room_id, $checkin_date, $checkout_date, $total_price){

        $sql = "INSERT INTO bookings(user_id, room_id, checkin_date, checkout_date, total_price, status)
        VALUES(?, ?, ?, ?, ?, 'Pending')";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("iissd", $user_id, $room_id, $checkin_date, $checkout_date, $total_price);

        return $stmt->execute();
    }

    function getLastBooking($connection){

        $sql = "SELECT * FROM bookings ORDER BY id DESC LIMIT 1";

        return $connection->query($sql);
    }

    function getUserBookings($connection, $user_id){

        $sql = "SELECT
            bookings.*,
            rooms.room_number,
            room_types.name AS room_type_name
        FROM bookings
        INNER JOIN rooms ON bookings.room_id = rooms.id
        INNER JOIN room_types ON rooms.room_type_id = room_types.id
        WHERE bookings.user_id=?
        ORDER BY bookings.id DESC";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        return $stmt->get_result();
    }

    function cancelBooking($connection, $booking_id, $user_id){

        $sql = "UPDATE bookings
        SET status='Cancelled'
        WHERE id=?
        AND user_id=?
        AND status IN('Pending','Confirmed')
        AND checkin_date > DATE_ADD(CURDATE(), INTERVAL 1 DAY)";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ii", $booking_id, $user_id);

        return $stmt->execute();
    }

    function getBookings($connection, $status, $fromDate, $toDate){

    $sql = "SELECT
        bookings.id,
        bookings.user_id,
        bookings.room_id,
        bookings.checkin_date,
        bookings.checkout_date,
        bookings.total_price,
        bookings.status,
        bookings.actual_checkin,
        users.name AS guest_name,
        rooms.room_number,
        room_types.name AS room_type_name
    FROM bookings
    INNER JOIN users ON bookings.user_id = users.id
    INNER JOIN rooms ON bookings.room_id = rooms.id
    INNER JOIN room_types ON rooms.room_type_id = room_types.id
    WHERE 1";

    if($status != ""){
        $sql .= " AND bookings.status='$status'";
    }

    if($fromDate != ""){
        $sql .= " AND bookings.checkin_date >= '$fromDate'";
    }

    if($toDate != ""){
        $sql .= " AND bookings.checkin_date <= '$toDate'";
    }

    $sql .= " ORDER BY bookings.checkin_date DESC";

    return $connection->query($sql);
}

function checkIn($connection, $booking_id){

    $sql = "UPDATE bookings
    SET status='Checked-In', actual_checkin=NOW()
    WHERE id=?
    AND status='Confirmed'";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $booking_id);

    return $stmt->execute();
}

function checkOut($connection, $booking_id){

    $sql = "UPDATE bookings
    SET status='Checked-Out'
    WHERE id=?
    AND status='Checked-In'";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $booking_id);

    return $stmt->execute();
}

function updateBookingStatus($connection, $booking_id, $status){

    $sql = "UPDATE bookings SET status=? WHERE id=?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("si", $status, $booking_id);

    return $stmt->execute();
}
    function getBookingById($connection, $id){

        $sql = "SELECT
            bookings.*,
            users.name AS guest_name,
            users.email,
            users.phone,
            rooms.room_number,
            rooms.floor,
            room_types.name AS room_type_name
        FROM bookings
        INNER JOIN users ON bookings.user_id = users.id
        INNER JOIN rooms ON bookings.room_id = rooms.id
        INNER JOIN room_types ON rooms.room_type_id = room_types.id
        WHERE bookings.id=?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result();
    }


    function getTodayArrivals($connection){

        $sql = "SELECT
            bookings.id,
            users.name AS guest_name,
            rooms.room_number,
            room_types.name AS room_type_name,
            bookings.checkin_date,
            bookings.status
        FROM bookings
        INNER JOIN users ON bookings.user_id = users.id
        INNER JOIN rooms ON bookings.room_id = rooms.id
        INNER JOIN room_types ON rooms.room_type_id = room_types.id
        WHERE bookings.checkin_date = CURDATE()
        AND bookings.status='Confirmed'";

        return $connection->query($sql);
    }

    function getTodayDepartures($connection){

        $sql = "SELECT
            bookings.id,
            users.name AS guest_name,
            rooms.room_number,
            room_types.name AS room_type_name,
            bookings.checkout_date,
            bookings.status
        FROM bookings
        INNER JOIN users ON bookings.user_id = users.id
        INNER JOIN rooms ON bookings.room_id = rooms.id
        INNER JOIN room_types ON rooms.room_type_id = room_types.id
        WHERE bookings.checkout_date = CURDATE()
        AND bookings.status='Checked-In'";

        return $connection->query($sql);
    }

    function getBookingHistoryByGuest($connection, $user_id){

        $sql = "SELECT
            bookings.*,
            rooms.room_number,
            room_types.name AS room_type_name
        FROM bookings
        INNER JOIN rooms ON bookings.room_id = rooms.id
        INNER JOIN room_types ON rooms.room_type_id = room_types.id
        WHERE bookings.user_id=?
        ORDER BY bookings.checkin_date DESC";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        return $stmt->get_result();
    }
}

?>
