<?php

class UserModel{

    function register($connection, $name, $email, $password_hash, $phone, $nationality){

        $sql = "INSERT INTO users(name, email, password_hash, phone, nationality, role)
        VALUES(?, ?, ?, ?, ?, 'guest')";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $password_hash, $phone, $nationality);

        return $stmt->execute();
    }

    function getUserByEmail($connection, $email){

        $sql = "SELECT * FROM users WHERE email=?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        return $stmt->get_result();
    }

    function getUserById($connection, $id){

        $sql = "SELECT * FROM users WHERE id=?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result();
    }

    function updateRememberToken($connection, $id, $token){

        $sql = "UPDATE users SET remember_token=? WHERE id=?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("si", $token, $id);

        return $stmt->execute();
    }

    function getUserByRememberToken($connection, $token){

        $sql = "SELECT * FROM users WHERE remember_token=?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();

        return $stmt->get_result();
    }

    function updateProfile($connection, $id, $name, $email, $phone, $nationality, $preferred_room_type_id, $special_requests){

        if($preferred_room_type_id == ""){
            $preferred_room_type_id = null;
        }

        $sql = "UPDATE users
        SET name=?, email=?, phone=?, nationality=?, preferred_room_type_id=?, special_requests=?
        WHERE id=?";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssssisi", $name, $email, $phone, $nationality, $preferred_room_type_id, $special_requests, $id);

        return $stmt->execute();
    }

    function getUpcomingBooking($connection, $user_id){

        $sql = "SELECT
            bookings.*,
            room_types.name AS room_type_name
        FROM bookings
        INNER JOIN rooms ON bookings.room_id = rooms.id
        INNER JOIN room_types ON rooms.room_type_id = room_types.id
        WHERE bookings.user_id=?
        AND bookings.checkin_date >= CURDATE()
        AND bookings.status NOT IN('Cancelled','Checked-Out')
        ORDER BY bookings.checkin_date ASC
        LIMIT 1";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        return $stmt->get_result();
    }
}

?>
