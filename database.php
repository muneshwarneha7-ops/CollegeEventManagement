<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "college_event_management_new";

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Set character encoding
$conn->set_charset("utf8mb4");

?>