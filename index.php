<?php
require_once "database.php";

$events = [];
$result = $conn->query("SELECT * FROM events ORDER BY event_date ASC LIMIT 6");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CollegeEvents | College Event Management</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">

    <div class="logo">
        <span>✦</span> CollegeEvents
    </div>

    <div class="nav-links">
        <a href="index.php" class="active">Home</a>
        <a href="#events">Events</a>
        <a href="#features">Features</a>
        <a href="#about">About</a>
        <a href="login.php" class="nav-login">Login</a>
        <a href="register.php" class="nav-register">Register</a>
    </div>

</nav>


<!-- HERO -->
<section class="hero">

    <div class="hero-content">

        <div class="hero-badge">
            ✨ Your Campus. Your Events. Your Experience.
        </div>

        <h1>
            Discover.
            <span>Connect.</span>
            Celebrate.
        </h1>

        <p>
            Explore exciting college events, register instantly,
            connect with students and make your campus life unforgettable.
        </p>

        <div class="hero-buttons">

            <a href="#events" class="btn-primary">
                Explore Events →
            </a>

            <a href="register.php" class="btn-secondary">
                Join CollegeEvents
            </a>

        </div>

        <div class="hero-stats">

            <div>
                <strong>50+</strong>
                <small>Events</small>
            </div>

            <div>
                <strong>500+</strong>
                <small>Students</small>
            </div>

            <div>
                <strong>20+</strong>
                <small>Clubs</small>
            </div>

        </div>

    </div>


    <div class="hero-card">

        <div class="floating-icon">🎉</div>

        <div class="event-preview">

            <small>UPCOMING EVENT</small>

            <h3>Annual College Fest</h3>

            <p>📅 25 September 2026</p>
            <p>📍 College Auditorium</p>

            <button>View Event</button>

        </div>

        <div class="mini-card mini-one">
            🎵 Music
        </div>

        <div class="mini-card mini-two">
            💻 Tech
        </div>

        <div class="mini-card mini-three">
            🏆 Sports
        </div>

    </div>

</section>


<!-- FEATURES -->
<section class="section" id="features">

    <div class="section-heading">

        <span>WHY COLLEGEEVENTS?</span>

        <h2>Everything Your Campus Needs</h2>

        <p>
            A simple and powerful platform to discover,
            manage and participate in college events.
        </p>

    </div>


    <div class="feature-grid">

        <div class="feature-card">
            <div class="feature-icon">🔎</div>
            <h3>Discover Events</h3>
            <p>
                Find technical, cultural, sports and academic
                events happening around your campus.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">⚡</div>
            <h3>Quick Registration</h3>
            <p>
                Register for your favourite events quickly
                without complicated forms.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">📱</div>
            <h3>Easy Management</h3>
            <p>
                Students and administrators can manage
                events from one platform.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">🌟</div>
            <h3>Better Experience</h3>
            <p>
                Get a modern digital experience for
                your complete college event journey.
            </p>
        </div>

    </div>

</section>


<!-- EVENTS -->
<section class="section events-section" id="events">

    <div class="section-heading">

        <span>EXPLORE</span>

        <h2>Upcoming Events</h2>

        <p>
            Don't miss what's happening on your campus.
        </p>

    </div>


    <div class="events-grid">

        <?php if (count($events) > 0): ?>

            <?php foreach ($events as $event): ?>

                <div class="event-card">

                    <div class="event-image">

                        <?php if (!empty($event['image'])): ?>

                            <img src="assets/images/<?php echo htmlspecialchars($event['image']); ?>">

                        <?php else: ?>

                            <div class="event-placeholder">
                                🎉
                            </div>

                        <?php endif; ?>

                        <span>
                            <?php echo htmlspecialchars($event['category']); ?>
                        </span>

                    </div>


                    <div class="event-content">

                        <h3>
                            <?php echo htmlspecialchars($event['title']); ?>
                        </h3>

                        <p>
                            <?php echo htmlspecialchars(substr($event['description'], 0, 100)); ?>...
                        </p>

                        <div class="event-info">
                            📅 <?php echo htmlspecialchars($event['event_date']); ?>
                        </div>

                        <div class="event-info">
                            📍 <?php echo htmlspecialchars($event['venue']); ?>
                        </div>

                        <a href="login.php" class="event-btn">
                            View Details →
                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="empty-events">

                <div>🎪</div>

                <h3>Events Coming Soon</h3>

                <p>
                    Exciting college events will appear here.
                </p>

            </div>

        <?php endif; ?>

    </div>

</section>


<!-- ABOUT -->
<section class="about-section" id="about">

    <div class="about-content">

        <div>

            <span>ABOUT US</span>

            <h2>
                Making College Life
                <strong>More Exciting.</strong>
            </h2>

        </div>

        <p>
            CollegeEvents is a digital event management platform
            designed to make college events easier to discover,
            organize and participate in.
        </p>

    </div>

</section>


<!-- CTA -->
<section class="cta">

    <div>

        <h2>Ready to make your next college event memorable?</h2>

        <p>
            Join students who are already discovering amazing events.
        </p>

    </div>

    <a href="register.php">
        Get Started →
    </a>

</section>


<!-- FOOTER -->
<footer>

    <div class="footer-logo">
        ✦ CollegeEvents
    </div>

    <p>
        College Event Management System
    </p>

    <div class="footer-contact">
        📧 muneshwarneha@sipnaengg.ac.in
        &nbsp; | &nbsp;
        📞 +91 860571XXXX
    </div>

    <small>
        © 2026 CollegeEvents. All Rights Reserved.
    </small>

</footer>

</body>
</html>