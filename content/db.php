<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";

    $dbname = "KinoMan";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if (!$conn){
        die("connection_failed" . mysqli_connect_error());
    }