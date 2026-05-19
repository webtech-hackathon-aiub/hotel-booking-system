<?php

class DashboardModel{

    function getTotalRooms($connection){

        $sql = "SELECT COUNT(*) AS total_rooms FROM rooms";

        return $connection->query($sql);
    }

    function getOccupiedRooms($connection){

        $sql = "SELECT COUNT(DISTINCT room_id) AS occupied_rooms
        FROM bookings
        WHERE status IN('Confirmed','Checked-In')
        AND checkin_date <= CURDATE()
        AND checkout_date >= CURDATE()";

        return $connection->query($sql);
    }

    function getMaintenanceRooms($connection){

        $sql = "SELECT COUNT(*) AS maintenance_rooms
        FROM rooms
        WHERE status='maintenance'";

        return $connection->query($sql);
    }

    function getAvailableRooms($connection){

        $sql = "SELECT COUNT(*) AS available_rooms
        FROM rooms
        WHERE status='available'
        AND id NOT IN(
            SELECT room_id
            FROM bookings
            WHERE status IN('Confirmed','Checked-In')
            AND checkin_date <= CURDATE()
            AND checkout_date >= CURDATE()
        )";

        return $connection->query($sql);
    }

    function getRevenuePastEightWeeks($connection){

        $sql = "SELECT
            DATE_FORMAT(checkin_date, '%Y-%u') AS week_no,
            SUM(total_price) AS revenue
        FROM bookings
        WHERE checkin_date >= DATE_SUB(CURDATE(), INTERVAL 8 WEEK)
        AND status IN('Confirmed','Checked-In','Checked-Out')
        GROUP BY DATE_FORMAT(checkin_date, '%Y-%u')
        ORDER BY week_no ASC";

        return $connection->query($sql);
    }
}

?>
