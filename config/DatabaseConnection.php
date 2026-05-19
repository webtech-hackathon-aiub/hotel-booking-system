<?php

class DatabaseConnection{

    function openConnection(){

        $connection = new mysqli("localhost", "root", "", "hotel_db");

        if($connection->connect_error){
            die("Connection Error: ".$connection->connect_error);
        }

        return $connection;
    }
}

?>
