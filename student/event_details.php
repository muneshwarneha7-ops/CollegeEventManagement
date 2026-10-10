<?php
session_start();

require_once "../database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Event ID");
}

$event_id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM events WHERE id = ? LIMIT 1");

if (!$stmt) {
    die("Database Error: " . $conn->error);
}

$stmt->bind_param("i", $event_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Event not found.");
}

$event = $result->fetch_assoc();

$icon = "🎉";

if ($event['category'] == "Technical") {
    $icon = "💻";
} elseif ($event['category'] == "Cultural") {
    $icon = "🎭";
} elseif ($event['category'] == "Sports") {
    $icon = "🏆";
} elseif ($event['category'] == "Festival") {
    $icon = "🎊";
} elseif ($event['category'] == "Wellness") {
    $icon = "🧘";
} elseif ($event['category'] == "Academic") {
    $icon = "📚";
} elseif ($event['category'] == "Career") {
    $icon = "💼";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($event['title']); ?> | College Events
    </title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
        }

        .details-page {
            padding: 50px 8%;
        }

        .details-box {
            max-width: 900px;
            margin: auto;
            background: white;
            border-radius: 22px;
            padding: 40px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.10);
        }

        .event-icon {
            font-size: 70px;
            text-align: center;
        }

        .details-box h1 {
            text-align: center;
            font-size: 38px;
            margin: 15px 0;
        }

        .category {
            display: block;
            width: fit-content;
            margin: 0 auto 25px;
            background: #eef2ff;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
        }

        .description {
            font-size: 17px;
            line-height: 1.8;
            color: #555;
            margin: 30px 0;
        }

        .event-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            margin: 30px 0;
        }

        .info-card {
            background: #f7f8ff;
            padding: 18px;
            border-radius: 14px;
        }

        .info-card strong {
            display: block;
            margin-bottom: 6px;
        }

        .buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn {
            text-decoration: none;
            padding: 13px 22px;
            border-radius: 10px;
            font-weight: bold;
        }

        .register-btn {
            background: #5b5bf7;
            color: white;
        }

        .back-btn {
            background: #e9e9ef;
            color: #333;
        }

        @media (max-width: 600px) {
            .details-box {
                padding: 25px;
            }

            .details-box h1 {
                font-size: 28px;
            }

            .event-info {
                grid-template-columns: 1fr;
            }

            .buttons {
                flex-direction: column;
            }

            .btn {
                text-align: center;
            }
        }
    </style>
</head>

<body>

<div class="details-page">

    <div class="details-box">

        <div class="event-icon">
            <?php echo $icon; ?>
        </div>

        <h1>
            <?php echo htmlspecialchars($event['title']); ?>
        </h1>

        <span class="category">
            <?php echo htmlspecialchars($event['category']); ?>
        </span>

        <div class="event-info">

            <div class="info-card">
                <strong>📅 Date</strong>
                <?php echo date("d M Y", strtotime($event['event_date'])); ?>
            </div>

            <div class="info-card">
                <strong>⏰ Time</strong>
                <?php echo date("h:i A", strtotime($event['event_time'])); ?>
            </div>

            <div class="info-card">
                <strong>📍 Venue</strong>
                <?php echo htmlspecialchars($event['venue']); ?>
            </div>

            <div class="info-card">
                <strong>🏷️ Category</strong>
                <?php echo htmlspecialchars($event['category']); ?>
            </div>

        </div>

        <div class="description">

            <h2>About This Event</h2>

            <p>
                <?php echo nl2br(htmlspecialchars($event['description'])); ?>
            </p>

        </div>

        <div class="buttons">

            <a
                href="register_event.php?id=<?php echo $event['id']; ?>"
                class="btn register-btn"
            >
                ✅ Register Now
            </a>

            <a
                href="events.php"
                class="btn back-btn"
            >
                ← Back to Events
            </a>

        </div>

    </div>

</div>

</body>
</html>