<?php
session_start();

require_once "../database.php";

$result = $conn->query("SELECT * FROM events ORDER BY event_date ASC, event_time ASC");

if (!$result) {
    die("Events Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Events | College Event Management</title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
        }

        .events-page {
            padding: 40px 7%;
        }

        .events-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .events-header h1 {
            font-size: 38px;
            margin-bottom: 10px;
        }

        .events-header p {
            color: #666;
            font-size: 17px;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .event-card {
            background: white;
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: 0.3s;
        }

        .event-card:hover {
            transform: translateY(-7px);
        }

        .event-icon {
            font-size: 45px;
            margin-bottom: 15px;
        }

        .event-card h2 {
            margin: 8px 0;
        }

        .category {
            display: inline-block;
            background: #eef2ff;
            padding: 7px 13px;
            border-radius: 20px;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .event-info {
            color: #555;
            line-height: 1.8;
            margin: 15px 0;
        }

        .event-btn {
            display: inline-block;
            text-decoration: none;
            background: #5b5bf7;
            color: white;
            padding: 11px 18px;
            border-radius: 10px;
            font-weight: bold;
        }

        .event-btn:hover {
            background: #4141d8;
        }

        .empty {
            text-align: center;
            background: white;
            padding: 50px;
            border-radius: 18px;
        }
    </style>
</head>

<body>



<div class="events-page">

    <div class="events-header">
        <h1>🎉 College Events</h1>
        <p>Explore exciting technical, cultural, sports and academic events.</p>
    </div>

    <?php if ($result->num_rows > 0): ?>

        <div class="events-grid">

            <?php while ($event = $result->fetch_assoc()): ?>

                <?php
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

                <div class="event-card">

                    <div class="event-icon">
                        <?php echo $icon; ?>
                    </div>

                    <span class="category">
                        <?php echo htmlspecialchars($event['category']); ?>
                    </span>

                    <h2>
                        <?php echo htmlspecialchars($event['title']); ?>
                    </h2>

                    <div class="event-info">

                        📅
                        <?php echo date("d M Y", strtotime($event['event_date'])); ?>

                        <br>

                        ⏰
                        <?php echo date("h:i A", strtotime($event['event_time'])); ?>

                        <br>

                        📍
                        <?php echo htmlspecialchars($event['venue']); ?>

                    </div>

                    <a
                        href="event_details.php?id=<?php echo $event['id']; ?>"
                        class="event-btn"
                    >
                        View Details →
                    </a>

                </div>

            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <div class="empty">
            <h2>📭 No Events Available</h2>
            <p>Admin has not added any events yet.</p>
        </div>

    <?php endif; ?>

</div>

</body>
</html>