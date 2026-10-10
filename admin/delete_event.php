<?php

session_start();
require_once "../database.php";

/* Admin login check */
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

/* Get all events from MySQL */
$sql = "SELECT * FROM events ORDER BY event_date ASC, event_time ASC";
$result = $conn->query($sql);

if (!$result) {
    die("Database Error: " . htmlspecialchars($conn->error));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Events | CollegeEvents</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6ff;
        }

        .navbar {
            background: white;
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
        }

        .nav-links {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 8px;
            color: #333;
        }

        .dashboard-btn {
            background: #4f46e5;
            color: white !important;
        }

        .logout-btn {
            background: #dc2626;
            color: white !important;
        }

        .container {
            width: 92%;
            max-width: 1200px;
            margin: 40px auto;
        }

        .heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .heading h1 {
            color: #222;
        }

        .heading p {
            color: #777;
            margin-top: 7px;
        }

        .add-btn {
            text-decoration: none;
            background: linear-gradient(135deg, #4f46e5, #db2777);
            color: white;
            padding: 12px 20px;
            border-radius