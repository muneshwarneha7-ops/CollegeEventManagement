<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}


/* =========================
   DASHBOARD COUNTS
   ========================= */

$result = $conn->query("SELECT COUNT(*) AS total FROM events");
$total_events = $result->fetch_assoc()["total"];

$result = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='student'");
$total_students = $result->fetch_assoc()["total"];

$result = $conn->query("SELECT COUNT(*) AS total FROM registrations");
$total_registrations = $result->fetch_assoc()["total"];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - College Events</title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6fb;
        }

        /* SIDEBAR */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: #111827;
            color: white;
            padding-top: 25px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .admin-text {
            text-align: center;
            color: #9ca3af;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            font-size: 15px;
        }

        .sidebar a:hover {
            background: #374151;
        }

        /* MAIN */

        .main {
            margin-left: 250px;
            padding: 40px;
        }

        .welcome {
            margin-bottom: 30px;
        }

        .welcome h1 {
            margin-bottom: 8px;
            color: #1f2937;
        }

        .welcome p {
            color: #6b7280;
        }

        /* STATS */

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
        }

        .stat-card h3 {
            color: #6b7280;
            margin: 0 0 10px;
        }

        .stat-card .number {
            font-size: 35px;
            font-weight: bold;
            color: #4f46e5;
        }

        /* MANAGEMENT */

        .section-title {
            margin-bottom: 20px;
            color: #1f2937;
        }

        .management {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .management-card {
            background: white;
            padding: 30px;
            border-radius: 18px;
            text-decoration: none;
            color: #222;
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
            transition: 0.2s;
        }

        .management-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }

        .icon {
            font-size: 42px;
            margin-bottom: 15px;
        }

        .management-card h2 {
            margin: 5px 0 10px;
        }

        .management-card p {
            color: #6b7280;
            line-height: 1.5;
        }

        .open-link {
            display: inline-block;
            margin-top: 10px;
            color: #4f46e5;
            font-weight: bold;
        }

        /* RESPONSIVE */

        @media (max-width: 900px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }

            .stats,
            .management {
                grid-template-columns: 1fr;
            }
        }

    </style>

</head>

<body>


<!-- =========================
     SIDEBAR
     ========================= -->

<div class="sidebar">

    <h2>🎓 College Events</h2>

    <div class="admin-text">
        Admin Panel
    </div>


    <a href="dashboard.php">
        🏠 Dashboard
    </a>


    <a href="events.php">
        📅 Manage Events
    </a>


    <a href="add_events.php">
        ➕ Add New Event
    </a>


    <a href="registrations.php">
        📝 Registrations
    </a>


    <a href="students.php">
        👨‍🎓 Student Information
    </a>


    <a href="../index.php">
        🌐 View Website
    </a>


    <a href="../logout.php">
        🚪 Logout
    </a>

</div>


<!-- =========================
     MAIN CONTENT
     ========================= -->

<div class="main">


    <div class="welcome">

        <h1>
            Welcome, Admin! 👋
        </h1>

        <p>
            Manage college events, students and registrations from one place.
        </p>

    </div>


    <!-- =========================
         STATISTICS
         ========================= -->

    <div class="stats">

        <div class="stat-card">

            <h3>📅 Total Events</h3>

            <div class="number">
                <?php echo $total_events; ?>
            </div>

        </div>


        <div class="stat-card">

            <h3>👨‍🎓 Total Students</h3>

            <div class="number">
                <?php echo $total_students; ?>
            </div>

        </div>


        <div class="stat-card">

            <h3>📝 Total Registrations</h3>

            <div class="number">
                <?php echo $total_registrations; ?>
            </div>

        </div>

    </div>


    <!-- =========================
         MANAGEMENT
         ========================= -->

    <h2 class="section-title">
        Admin Management
    </h2>


    <div class="management">


        <!-- MANAGE EVENTS -->

        <a href="events.php"
           class="management-card">

            <div class="icon">
                📅
            </div>

            <h2>
                Manage Events
            </h2>

            <p>
                View all events, add new events,
                edit event details or delete events.
            </p>

            <span class="open-link">
                Open Events →
            </span>

        </a>


        <!-- REGISTRATIONS -->

        <a href="registrations.php"
           class="management-card">

            <div class="icon">
                📝
            </div>

            <h2>
                Registrations
            </h2>

            <p>
                Check which students have registered
                for each college event.
            </p>

            <span class="open-link">
                View Registrations →
            </span>

        </a>


        <!-- STUDENTS -->

        <a href="students.php"
           class="management-card">

            <div class="icon">
                👨‍🎓
            </div>

            <h2>
                Student Information
            </h2>

            <p>
                View registered students and their
                basic account information.
            </p>

            <span class="open-link">
                View Students →
            </span>

        </a>


    </div>

</div>


</body>

</html>