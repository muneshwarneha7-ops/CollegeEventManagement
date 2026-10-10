<?php

error_reporting(E_ALL);
ini_set("display_errors", "1");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

$admin_name = $_SESSION["user_name"] ?? "Admin";

/* Get all events */
$sql = "SELECT * FROM events ORDER BY event_date ASC, event_time ASC";

$result = $conn->query($sql);

if (!$result) {
    die("Database Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Events | Admin</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            color: #1f2937;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */

        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #111827;
            padding: 25px 15px;
            color: white;
        }

        .logo {
            text-align: center;
            font-size: 21px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 13px 14px;
            margin: 7px 0;
            border-radius: 8px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #2563eb;
        }

        .logout {
            background: #dc2626;
            margin-top: 25px !important;
        }

        /* MAIN */

        .main {
            flex: 1;
            padding: 30px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .topbar h1 {
            margin: 0;
            font-size: 30px;
        }

        .admin {
            background: white;
            padding: 12px 18px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }

        /* HEADER */

        .page-header {
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            color: white;
            padding: 25px;
            border-radius: 18px;
            margin-bottom: 25px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h2 {
            margin: 0 0 8px;
        }

        .page-header p {
            margin: 0;
            opacity: 0.9;
        }

        .add-btn {
            background: white;
            color: #2563eb;
            text-decoration: none;
            padding: 13px 18px;
            border-radius: 9px;
            font-weight: bold;
        }

        .add-btn:hover {
            background: #f3f4f6;
        }

        /* EVENTS */

        .events-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }

        .event-card {
            background: white;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.07);
            transition: 0.3s;
        }

        .event-card:hover {
            transform: translateY(-4px);
        }

        .category {
            display: inline-block;
            background: #eef2ff;
            color: #4338ca;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .event-card h3 {
            margin: 5px 0 12px;
            font-size: 20px;
        }

        .description {
            color: #6b7280;
            line-height: 1.5;
            min-height: 45px;
        }

        .info {
            margin-top: 15px;
            line-height: 1.8;
            font-size: 14px;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 18px;
        }

        .edit-btn,
        .delete-btn {
            flex: 1;
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        .edit-btn {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .delete-btn {
            background: #fee2e2;
            color: #dc2626;
        }

        .no-events {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 15px;
            color: #6b7280;
        }

        /* MOBILE */

        @media (max-width: 1000px) {

            .events-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 700px) {

            .container {
                display: block;
            }

            .sidebar {
                width: 100%;
                min-height: auto;
            }

            .main {
                padding: 20px;
            }

            .topbar {
                display: block;
            }

            .admin {
                margin-top: 15px;
            }

            .page-header {
                display: block;
            }

            .add-btn {
                display: inline-block;
                margin-top: 18px;
            }

            .events-grid {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <!-- SIDEBAR -->

    <div class="sidebar">

        <div class="logo">
            🎓 College Events
        </div>

        <a href="dashboard.php">
            🏠 Dashboard
        </a>

        <a href="events.php" class="active">
            📅 Manage Events
        </a>

        <a href="add_events.php">
            ➕ Add Event
        </a>

        <a href="registrations.php">
            📝 Registrations
        </a>

        <a href="students.php">
            👨‍🎓 Students
        </a>

        <a href="../index.php">
            🌐 View Website
        </a>

        <a href="../logout.php" class="logout">
            🚪 Logout
        </a>

    </div>


    <!-- MAIN -->

    <div class="main">

        <!-- TOP -->

        <div class="topbar">

            <h1>
                Manage Events
            </h1>

            <div class="admin">
                👤 <?php echo htmlspecialchars($admin_name); ?>
            </div>

        </div>


        <!-- PAGE HEADER -->

        <div class="page-header">

            <div>

                <h2>
                    📅 College Events
                </h2>

                <p>
                    View, edit and manage all college events.
                </p>

            </div>

            <a href="add_events.php" class="add-btn">
                ➕ Add New Event
            </a>

        </div>


        <!-- EVENT LIST -->

        <?php if ($result->num_rows > 0): ?>

            <div class="events-grid">

                <?php while ($event = $result->fetch_assoc()): ?>

                    <div class="event-card">

                        <div class="category">

                            <?php
                            echo htmlspecialchars(
                                $event["category"]
                            );
                            ?>

                        </div>


                        <h3>

                            <?php
                            echo htmlspecialchars(
                                $event["title"]
                            );
                            ?>

                        </h3>


                        <div class="description">

                            <?php

                            $description =
                                $event["description"];

                            if (strlen($description) > 120) {
                                echo htmlspecialchars(
                                    substr($description, 0, 120)
                                ) . "...";
                            } else {
                                echo htmlspecialchars(
                                    $description
                                );
                            }

                            ?>

                        </div>


                        <div class="info">

                            📅

                            <?php
                            echo date(
                                "d M Y",
                                strtotime($event["event_date"])
                            );
                            ?>

                            <br>

                            ⏰

                            <?php
                            echo date(
                                "h:i A",
                                strtotime($event["event_time"])
                            );
                            ?>

                            <br>

                            📍

                            <?php
                            echo htmlspecialchars(
                                $event["venue"]
                            );
                            ?>

                        </div>


                        <div class="actions">

                            <a
                                href="edit_event.php?id=<?php echo $event["id"]; ?>"
                                class="edit-btn"
                            >
                                ✏️ Edit
                            </a>


                            <a
                                href="delete_event.php?id=<?php echo $event["id"]; ?>"
                                class="delete-btn"
                                onclick="return confirm('Are you sure you want to delete this event?');"
                            >
                                🗑️ Delete
                            </a>

                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

        <?php else: ?>

            <div class="no-events">

                <h2>
                    No Events Found
                </h2>

                <p>
                    Click "Add New Event" to create your first event.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>

</body>

</html>