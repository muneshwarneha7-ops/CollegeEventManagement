<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}


/* =========================================
   TOTAL COUNTS
========================================= */

// Total Events
$result = $conn->query("SELECT COUNT(*) AS total FROM events");
$total_events = $result->fetch_assoc()["total"];

// Total Students
$result = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='student'");
$total_students = $result->fetch_assoc()["total"];

// Total Registrations
$result = $conn->query("SELECT COUNT(*) AS total FROM registrations");
$total_registrations = $result->fetch_assoc()["total"];

// Total Feedback
$result = $conn->query("SELECT COUNT(*) AS total FROM feedback");
$total_feedback = $result->fetch_assoc()["total"];


/* =========================================
   STUDENT-WISE REGISTRATION COUNT
   Kis student ne kitne events me registration kiya
========================================= */

$student_registration_query = "
    SELECT
        u.id,
        u.name,
        u.email,
        COUNT(r.id) AS total_events_registered
    FROM users u
    LEFT JOIN registrations r
        ON u.id = r.user_id
    WHERE u.role = 'student'
    GROUP BY u.id, u.name, u.email
    ORDER BY total_events_registered DESC, u.name ASC
";

$student_registration_result =
    $conn->query($student_registration_query);


/* =========================================
   EVENT-WISE REGISTRATION COUNT
   Kis event me kitne students registered
========================================= */

$event_registration_query = "
    SELECT
        e.id,
        e.title,
        e.event_date,
        e.venue,
        e.category,
        COUNT(r.id) AS total_registered
    FROM events e
    LEFT JOIN registrations r
        ON e.id = r.event_id
    GROUP BY
        e.id,
        e.title,
        e.event_date,
        e.venue,
        e.category
    ORDER BY total_registered DESC
";

$event_registration_result =
    $conn->query($event_registration_query);


/* =========================================
   COMPLETE REGISTRATION DETAILS
========================================= */

$registration_details_query = "
    SELECT
        r.id,
        u.name AS student_name,
        u.email AS student_email,
        e.title AS event_title,
        e.event_date,
        e.venue,
        e.category,
        r.registered_at
    FROM registrations r
    INNER JOIN users u
        ON r.user_id = u.id
    INNER JOIN events e
        ON r.event_id = e.id
    ORDER BY r.registered_at DESC
";

$registration_details_result =
    $conn->query($registration_details_query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Registration Management</title>

    <link rel="stylesheet"
          href="../assets/css/style.css">

    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 240px;
            background: #1e293b;
            color: white;
            padding: 25px 15px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 13px 15px;
            margin: 7px 0;
            border-radius: 8px;
        }

        .sidebar a:hover {
            background: #334155;
        }

        .main-content {
            flex: 1;
            padding: 30px;
        }

        .page-title {
            margin-bottom: 25px;
        }

        .page-title h1 {
            margin-bottom: 5px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 22px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .stat-card h3 {
            margin: 0;
            color: #64748b;
            font-size: 15px;
        }

        .stat-card p {
            font-size: 30px;
            font-weight: bold;
            margin: 10px 0 0;
        }

        .section {
            background: white;
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        }

        .section h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f1f5f9;
            padding: 13px;
            text-align: left;
        }

        td {
            padding: 13px;
            border-bottom: 1px solid #e2e8f0;
        }

        tr:hover {
            background: #f8fafc;
        }

        .count-badge {
            display: inline-block;
            background: #e0f2fe;
            color: #0369a1;
            padding: 7px 14px;
            border-radius: 20px;
            font-weight: bold;
        }

        .zero {
            color: #94a3b8;
        }

        .main-content {
    margin-left: 255px;
    width: calc(100% - 255px);
    box-sizing: border-box;
    
}
        @media (max-width: 900px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .sidebar {
                width: 190px;
            }

        }

        @media (max-width: 600px) {

            .admin-layout {
                display: block;
            }

            .sidebar {
                width: auto;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .main-content {
                padding: 15px;
            }

        }

    </style>

</head>

<body>

<div class="admin-layout">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <h2>⚙️ Admin Panel</h2>

        <a href="dashboard.php">🏠 Dashboard</a>

        <a href="events.php">📅 Manage Events</a>

        <a href="add_events.php">➕ Add Event</a>

        <a href="registrations.php">📝 Registrations</a>

        <a href="students.php">👨‍🎓 Students</a>

        <a href="../index.php">🌐 View Website</a>

        <a href="../logout.php">🚪 Logout</a>

    </aside>


    <!-- MAIN CONTENT -->

    <main class="main-content">

        <div class="page-title">

            <h1>📝 Registration Management</h1>

            <p>
                View students, registered events and complete registration details.
            </p>

        </div>


        <!-- =================================
             STATISTICS
        ================================== -->

        <div class="stats">

            <div class="stat-card">

                <h3>📅 Total Events</h3>

                <p>
                    <?php echo $total_events; ?>
                </p>

            </div>


            <div class="stat-card">

                <h3>👨‍🎓 Total Students</h3>

                <p>
                    <?php echo $total_students; ?>
                </p>

            </div>


            <div class="stat-card">

                <h3>📝 Total Registrations</h3>

                <p>
                    <?php echo $total_registrations; ?>
                </p>

            </div>


            <div class="stat-card">

                <h3>⭐ Total Feedback</h3>

                <p>
                    <?php echo $total_feedback; ?>
                </p>

            </div>

        </div>


        <!-- =================================
             STUDENT-WISE REGISTRATION
        ================================== -->

        <div class="section">

            <h2>👨‍🎓 Student-wise Event Registration</h2>

            <p>
                This shows how many events each student has registered for.
            </p>

            <div class="table-container">

                <table>

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Student Name</th>

                            <th>Email</th>

                            <th>Events Registered</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php

                    $number = 1;

                    if ($student_registration_result->num_rows > 0):

                        while (
                            $student =
                            $student_registration_result->fetch_assoc()
                        ):

                    ?>

                        <tr>

                            <td>
                                <?php echo $number++; ?>
                            </td>

                            <td>
                                <strong>
                                    <?php
                                    echo htmlspecialchars(
                                        $student["name"]
                                    );
                                    ?>
                                </strong>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $student["email"]
                                );
                                ?>
                            </td>

                            <td>

                                <span class="count-badge">

                                    <?php
                                    echo $student[
                                        "total_events_registered"
                                    ];
                                    ?>

                                    Event(s)

                                </span>

                            </td>

                        </tr>

                    <?php

                        endwhile;

                    else:

                    ?>

                        <tr>

                            <td colspan="4">
                                No students found.
                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- =================================
             EVENT-WISE REGISTRATION
        ================================== -->

        <div class="section">

            <h2>📅 Event-wise Registration Count</h2>

            <p>
                This shows how many students registered for each event.
            </p>

            <div class="table-container">

                <table>

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Event</th>

                            <th>Category</th>

                            <th>Date</th>

                            <th>Venue</th>

                            <th>Total Registered</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php

                    $number = 1;

                    while (
                        $event =
                        $event_registration_result->fetch_assoc()
                    ):

                    ?>

                        <tr>

                            <td>
                                <?php echo $number++; ?>
                            </td>

                            <td>
                                <strong>
                                    <?php
                                    echo htmlspecialchars(
                                        $event["title"]
                                    );
                                    ?>
                                </strong>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $event["category"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo date(
                                    "d M Y",
                                    strtotime(
                                        $event["event_date"]
                                    )
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $event["venue"]
                                );
                                ?>
                            </td>

                            <td>

                                <span class="count-badge">

                                    <?php
                                    echo $event[
                                        "total_registered"
                                    ];
                                    ?>

                                    Student(s)

                                </span>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- =================================
             COMPLETE REGISTRATION DETAILS
        ================================== -->

        <div class="section">

            <h2>📋 Complete Registration Details</h2>

            <p>
                Here you can see exactly which student registered
                for which event and when.
            </p>

            <div class="table-container">

                <table>

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Student</th>

                            <th>Email</th>

                            <th>Event</th>

                            <th>Category</th>

                            <th>Date</th>

                            <th>Registered At</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php

                    $number = 1;

                    if (
                        $registration_details_result->num_rows > 0
                    ):

                        while (
                            $registration =
                            $registration_details_result->fetch_assoc()
                        ):

                    ?>

                        <tr>

                            <td>
                                <?php echo $number++; ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $registration["student_name"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $registration["student_email"]
                                );
                                ?>
                            </td>

                            <td>

                                <strong>

                                    <?php
                                    echo htmlspecialchars(
                                        $registration["event_title"]
                                    );
                                    ?>

                                </strong>

                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $registration["category"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo date(
                                    "d M Y",
                                    strtotime(
                                        $registration["event_date"]
                                    )
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo date(
                                    "d M Y, h:i A",
                                    strtotime(
                                        $registration["registered_at"]
                                    )
                                );
                                ?>
                            </td>

                        </tr>

                    <?php

                        endwhile;

                    else:

                    ?>

                        <tr>

                            <td colspan="7">

                                No registrations found.

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

</body>

</html>