<?php
session_start();
require_once "../database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"] ?? "Student";

/* Get student's registrations */
$stmt = $conn->prepare("
    SELECT 
        registrations.id AS registration_id,
        registrations.registered_at,
        events.id AS event_id,
        events.title,
        events.description,
        events.event_date,
        events.event_time,
        events.venue,
        events.category,
        events.image
    FROM registrations
    INNER JOIN events 
        ON registrations.event_id = events.id
    WHERE registrations.user_id = ?
    ORDER BY events.event_date ASC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$registrations = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Registrations | College Events</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body {
    background: #f5f7ff;
}

.registration-page {
    padding: 40px 6%;
}

.page-heading {
    margin-bottom: 30px;
}

.page-heading h1 {
    font-size: 34px;
    color: #222;
    margin-bottom: 8px;
}

.page-heading p {
    color: #777;
}

.registration-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(300px,1fr));
    gap: 25px;
}

.registration-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 35px rgba(0,0,0,0.08);
    transition: 0.3s;
}

.registration-card:hover {
    transform: translateY(-5px);
}

.event-image {
    height: 180px;
    background: linear-gradient(135deg,#667eea,#764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 65px;
}

.event-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.registration-content {
    padding: 22px;
}

.category {
    display: inline-block;
    background: #eef0ff;
    color: #667eea;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: bold;
    margin-bottom: 12px;
}

.registration-content h2 {
    color: #222;
    margin-bottom: 15px;
}

.event-details {
    color: #666;
    line-height: 1.8;
    margin-bottom: 15px;
}

.registration-status {
    background: #e9fff1;
    color: #168044;
    padding: 10px;
    border-radius: 10px;
    text-align: center;
    font-weight: bold;
}

.view-button {
    display: block;
    text-align: center;
    text-decoration: none;
    margin-top: 15px;
    padding: 12px;
    border-radius: 10px;
    background: linear-gradient(135deg,#667eea,#764ba2);
    color: white;
    font-weight: bold;
}

.empty-box {
    background: white;
    border-radius: 20px;
    padding: 60px 20px;
    text-align: center;
    box-shadow: 0 10px 35px rgba(0,0,0,0.07);
}

.empty-box .emoji {
    font-size: 65px;
    margin-bottom: 15px;
}

.empty-box h2 {
    color: #333;
    margin-bottom: 10px;
}

.empty-box p {
    color: #777;
    margin-bottom: 25px;
}

.browse-button {
    display: inline-block;
    padding: 13px 25px;
    border-radius: 10px;
    background: linear-gradient(135deg,#667eea,#764ba2);
    color: white;
    text-decoration: none;
    font-weight: bold;
}

@media(max-width:600px) {

    .registration-page {
        padding: 30px 20px;
    }

    .page-heading h1 {
        font-size: 28px;
    }
}

</style>

</head>

<body>

<nav class="navbar">

    <div class="logo">🎓 CollegeEvents</div>

    <div class="nav-links">

        <a href="dashboard.php">Dashboard</a>

        <a href="events.php">Events</a>

        <a href="my_registrations.php">My Registrations</a>

        <a href="feedback.php">Feedback</a>

        <a href="../logout.php">Logout</a>

    </div>

</nav>


<div class="registration-page">

    <div class="page-heading">

        <h1>📋 My Registrations</h1>

        <p>
            Welcome <?php echo htmlspecialchars($user_name); ?>!
            Here you can see all the events you have registered for.
        </p>

    </div>


    <?php if ($registrations->num_rows > 0): ?>

        <div class="registration-grid">

            <?php while ($event = $registrations->fetch_assoc()): ?>

                <div class="registration-card">

                    <div class="event-image">

                        <?php if (!empty($event["image"])): ?>

                            <img src="../assets/images/<?php echo htmlspecialchars($event["image"]); ?>"
                                 alt="Event Image">

                        <?php else: ?>

                            🎉

                        <?php endif; ?>

                    </div>


                    <div class="registration-content">

                        <span class="category">
                            <?php echo htmlspecialchars($event["category"]); ?>
                        </span>

                        <h2>
                            <?php echo htmlspecialchars($event["title"]); ?>
                        </h2>

                        <div class="event-details">

                            📅 <strong>Date:</strong>
                            <?php echo date("d M Y", strtotime($event["event_date"])); ?>
                            <br>

                            ⏰ <strong>Time:</strong>
                            <?php echo date("h:i A", strtotime($event["event_time"])); ?>
                            <br>

                            📍 <strong>Venue:</strong>
                            <?php echo htmlspecialchars($event["venue"]); ?>

                        </div>


                        <div class="registration-status">
                            ✅ Registered Successfully
                        </div>


                        <a href="event_details.php?id=<?php echo $event["event_id"]; ?>"
                           class="view-button">
                            View Event Details
                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <div class="empty-box">

            <div class="emoji">🎟️</div>

            <h2>No Registrations Yet</h2>

            <p>
                You haven't registered for any event yet.
                Explore the available college events and join your favourite ones!
            </p>

            <a href="events.php" class="browse-button">
                🎉 Browse Events
            </a>

        </div>

    <?php endif; ?>

</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>