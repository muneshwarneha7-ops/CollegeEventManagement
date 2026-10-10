<?php
session_start();
require_once "../database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$event_id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($event_id <= 0) {
    header("Location: events.php");
    exit;
}

/* Get event details */
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();

if (!$event) {
    header("Location: events.php");
    exit;
}

$message = "";
$message_type = "";

/* Register student */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /* Check duplicate registration */
    $check = $conn->prepare(
        "SELECT id FROM registrations 
         WHERE user_id = ? AND event_id = ? 
         LIMIT 1"
    );

    $check->bind_param("ii", $user_id, $event_id);
    $check->execute();

    $already_registered = $check->get_result()->fetch_assoc();
    $check->close();

    if ($already_registered) {

        $message = "You are already registered for this event.";
        $message_type = "warning";

    } else {

        $insert = $conn->prepare(
            "INSERT INTO registrations (user_id, event_id) 
             VALUES (?, ?)"
        );

        $insert->bind_param("ii", $user_id, $event_id);

        if ($insert->execute()) {
            $message = "Registration successful! 🎉";
            $message_type = "success";
        } else {
            $message = "Registration failed. Please try again.";
            $message_type = "error";
        }

        $insert->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register Event | College Events</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body {
    background: linear-gradient(135deg, #f4f6ff, #eef0ff);
}

.register-container {
    min-height: calc(100vh - 80px);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
}

.register-card {
    width: 100%;
    max-width: 650px;
    background: white;
    padding: 40px;
    border-radius: 25px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.12);
}

.register-icon {
    width: 85px;
    height: 85px;
    border-radius: 50%;
    background: linear-gradient(135deg,#667eea,#764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    margin: auto;
}

.register-card h1 {
    text-align: center;
    color: #222;
    margin: 20px 0 8px;
}

.register-card .subtitle {
    text-align: center;
    color: #777;
    margin-bottom: 30px;
}

.event-box {
    background: #f6f7ff;
    padding: 25px;
    border-radius: 18px;
    margin-bottom: 25px;
}

.event-box h2 {
    color: #333;
    margin-bottom: 20px;
}

.event-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.info {
    background: white;
    padding: 13px;
    border-radius: 10px;
    color: #555;
}

.info strong {
    display: block;
    color: #222;
    margin-bottom: 5px;
}

.message {
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: 600;
}

.success {
    background: #e8fff1;
    color: #168044;
}

.warning {
    background: #fff5d8;
    color: #9a7100;
}

.error {
    background: #ffe8e8;
    color: #c0392b;
}

.register-button {
    width: 100%;
    padding: 16px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg,#667eea,#764ba2);
    color: white;
    font-size: 17px;
    font-weight: bold;
    cursor: pointer;
}

.register-button:hover {
    transform: translateY(-2px);
}

.back-link {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

@media(max-width:600px) {

    .register-card {
        padding: 25px;
    }

    .event-info {
        grid-template-columns: 1fr;
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

<div class="register-container">

<div class="register-card">

    <div class="register-icon">🎟️</div>

    <h1>Register for Event</h1>

    <p class="subtitle">
        Confirm your participation in this college event.
    </p>

    <?php if ($message != ""): ?>

        <div class="message <?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

    <div class="event-box">

        <h2>
            <?php echo htmlspecialchars($event["title"]); ?>
        </h2>

        <div class="event-info">

            <div class="info">
                <strong>📅 Date</strong>
                <?php echo date("d M Y", strtotime($event["event_date"])); ?>
            </div>

            <div class="info">
                <strong>⏰ Time</strong>
                <?php echo date("h:i A", strtotime($event["event_time"])); ?>
            </div>

            <div class="info">
                <strong>📍 Venue</strong>
                <?php echo htmlspecialchars($event["venue"]); ?>
            </div>

            <div class="info">
                <strong>🏷️ Category</strong>
                <?php echo htmlspecialchars($event["category"]); ?>
            </div>

        </div>

    </div>

    <?php if ($message_type != "success"): ?>

        <form method="POST">

            <button type="submit" class="register-button">
                🎉 Confirm Registration
            </button>

        </form>

    <?php else: ?>

        <a href="my_registrations.php"
           class="register-button"
           style="display:block;text-align:center;text-decoration:none;">
            📋 View My Registrations
        </a>

    <?php endif; ?>

    <a href="events.php" class="back-link">
        ← Back to Events
    </a>

</div>

</div>

</body>
</html>