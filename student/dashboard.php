<?php
session_start();

require_once "../database.php";

/* Student name */
$user_name = $_SESSION["user_name"] ?? "Student";
$user_id = $_SESSION["user_id"] ?? 0;


/* Total events */
$total_events = 0;

$result = $conn->query("SELECT COUNT(*) AS total FROM events");

if ($result) {
    $row = $result->fetch_assoc();
    $total_events = $row["total"];
}


/* My registrations */
$total_registrations = 0;

if ($user_id > 0) {

    $stmt = $conn->prepare(
        "SELECT COUNT(*) AS total
         FROM registrations
         WHERE user_id = ?"
    );

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        $total_registrations = $row["total"];
    }

    $stmt->close();
}


/* Recent upcoming events */
$upcoming_events = [];

$result = $conn->query(
    "SELECT *
     FROM events
     WHERE event_date >= CURDATE()
     ORDER BY event_date ASC
     LIMIT 3"
);

if ($result) {

    while ($row = $result->fetch_assoc()) {
        $upcoming_events[] = $row;
    }
}


/* First letter of student's name */
$first_letter = strtoupper(substr($user_name, 0, 1));

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard | CollegeEvents</title>

    <link rel="stylesheet"
          href="../assets/css/style.css">

</head>


<body class="dashboard-body">


<!-- ================= SIDEBAR ================= -->

<aside class="sidebar">

    <div class="dashboard-logo">
        ✦ CollegeEvents
    </div>


    <!-- PROFILE -->

    <div class="student-profile">

        <div class="profile-circle">
            <?php echo $first_letter; ?>
        </div>

        <h3>
            <?php echo htmlspecialchars($user_name); ?>
        </h3>

        <span>
            Student
        </span>

    </div>


    <!-- MENU -->

    <div class="sidebar-menu">

        <a href="dashboard.php"
           class="side-active">
            🏠 Dashboard
        </a>

        <a href="events.php">
            🎉 Browse Events
        </a>

        <a href="my_registrations.php">
            🎟 My Registrations
        </a>

        <a href="feedback.php">
            ⭐ Feedback
        </a>

        <a href="../index.php">
            🌐 Home
        </a>

    </div>


    <!-- LOGOUT -->

    <a href="../logout.php"
       class="logout-btn">
        🚪 Logout
    </a>

</aside>



<!-- ================= MAIN CONTENT ================= -->

<main class="dashboard-main">


    <!-- HEADER -->

    <header class="dashboard-header">

        <div>

            <p>
                STUDENT PORTAL
            </p>

            <h1>
                Hello,
                <?php echo htmlspecialchars($user_name); ?>! 👋
            </h1>

        </div>


        <div class="header-avatar">

            <?php echo $first_letter; ?>

        </div>

    </header>



    <!-- ================= WELCOME BANNER ================= -->

    <section class="welcome-banner">

        <div>

            <span>
                MAKE YOUR COLLEGE LIFE MEMORABLE
            </span>

            <h2>
                Discover your next
                <strong>campus experience.</strong>
            </h2>

            <p>
                Explore exciting events, register for activities
                and create unforgettable college memories.
            </p>


            <a href="events.php">
                Explore Events →
            </a>

        </div>


        <div class="banner-emoji">
            🎓
        </div>

    </section>



    <!-- ================= STATISTICS ================= -->

    <section class="dashboard-stats">


        <!-- EVENTS -->

        <div class="stat-card">

            <div class="stat-icon purple">
                🎉
            </div>

            <div>

                <span>
                    Available Events
                </span>

                <h2>
                    <?php echo $total_events; ?>
                </h2>

            </div>

        </div>



        <!-- REGISTRATIONS -->

        <div class="stat-card">

            <div class="stat-icon pink">
                🎟
            </div>

            <div>

                <span>
                    My Registrations
                </span>

                <h2>
                    <?php echo $total_registrations; ?>
                </h2>

            </div>

        </div>



        <!-- EXPERIENCE -->

        <div class="stat-card">

            <div class="stat-icon blue">
                ⭐
            </div>

            <div>

                <span>
                    Campus Experience
                </span>

                <h2>
                    <?php
                    echo ($total_registrations > 0)
                        ? "Active"
                        : "New";
                    ?>
                </h2>

            </div>

        </div>

    </section>



    <!-- ================= UPCOMING EVENTS ================= -->

    <section class="dashboard-section">


        <div class="dashboard-section-title">

            <div>

                <span>
                    DON'T MISS OUT
                </span>

                <h2>
                    Upcoming Events
                </h2>

            </div>

        </div>



        <div class="events-grid">


            <?php if (count($upcoming_events) > 0): ?>


                <?php foreach ($upcoming_events as $event): ?>


                    <div class="event-card">


                        <!-- EVENT IMAGE -->

                        <div class="event-image">


                            <?php if (!empty($event["image"])): ?>

                                <img
                                    src="../assets/images/<?php
                                    echo htmlspecialchars($event["image"]);
                                    ?>"
                                    alt="Event Image">

                            <?php else: ?>

                                <div class="event-placeholder">
                                    🎉
                                </div>

                            <?php endif; ?>


                            <span>
                                <?php
                                echo htmlspecialchars(
                                    $event["category"]
                                );
                                ?>
                            </span>


                        </div>



                        <!-- EVENT DETAILS -->

                        <div class="event-content">


                            <h3>
                                <?php
                                echo htmlspecialchars(
                                    $event["title"]
                                );
                                ?>
                            </h3>


                            <p>
                                <?php

                                $description =
                                    $event["description"];

                                echo htmlspecialchars(
                                    substr(
                                        $description,
                                        0,
                                        90
                                    )
                                );

                                ?>...
                            </p>


                            <div class="event-info">

                                📅
                                <?php
                                echo htmlspecialchars(
                                    $event["event_date"]
                                );
                                ?>

                            </div>


                            <div class="event-info">

                                📍
                                <?php
                                echo htmlspecialchars(
                                    $event["venue"]
                                );
                                ?>

                            </div>


                            <a
                                href="event_details.php?id=<?php echo $event["id"]; ?>"
                                class="event-btn">

                                View Event →

                            </a>


                        </div>

                    </div>


                <?php endforeach; ?>


            <?php else: ?>


                <div class="empty-events">

                    <div>
                        🎪
                    </div>

                    <h3>
                        No Upcoming Events
                    </h3>

                    <p>
                        Exciting college events will appear here.
                    </p>

                    <br>

                    <a
                        href="events.php"
                        class="event-btn">

                        Browse Events →

                    </a>

                </div>


            <?php endif; ?>


        </div>

    </section>



    <!-- ================= QUICK ACTIONS ================= -->

    <section class="dashboard-section">


        <div class="dashboard-section-title">

            <div>

                <span>
                    QUICK ACCESS
                </span>

                <h2>
                    What would you like to do?
                </h2>

            </div>

        </div>



        <div class="quick-grid">


            <!-- FIND EVENTS -->

            <a
                href="events.php"
                class="quick-card">

                <div>
                    🎪
                </div>

                <h3>
                    Find Events
                </h3>

                <p>
                    Discover technical, cultural,
                    sports and academic events.
                </p>

                <strong>
                    Explore →
                </strong>

            </a>



            <!-- REGISTRATIONS -->

            <a
                href="my_registrations.php"
                class="quick-card">

                <div>
                    🎫
                </div>

                <h3>
                    My Tickets
                </h3>

                <p>
                    View all the events you have
                    registered for.
                </p>

                <strong>
                    View Tickets →
                </strong>

            </a>



            <!-- FEEDBACK -->

            <a
                href="feedback.php"
                class="quick-card">

                <div>
                    💬
                </div>

                <h3>
                    Give Feedback
                </h3>

                <p>