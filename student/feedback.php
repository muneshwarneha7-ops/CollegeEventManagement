<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../database.php";


/* Check login */

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$message = "";
$error = "";


/* =========================
   SUBMIT FEEDBACK
   ========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $event_id = $_POST["event_id"] ?? "";
    $rating = $_POST["rating"] ?? "";
    $feedback_message = trim($_POST["message"] ?? "");

    if (
        !is_numeric($event_id) ||
        !is_numeric($rating) ||
        $feedback_message === ""
    ) {

        $error = "Please fill all fields.";

    } elseif ($rating < 1 || $rating > 5) {

        $error = "Please select a rating between 1 and 5.";

    } else {

        $event_id = (int)$event_id;
        $rating = (int)$rating;


        /* Check whether event exists */

        $check = $conn->prepare(
            "SELECT id FROM events WHERE id = ? LIMIT 1"
        );

        $check->bind_param("i", $event_id);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows === 0) {

            $error = "Selected event does not exist.";

        } else {

            /* Insert feedback */

            $stmt = $conn->prepare(
                "INSERT INTO feedback
                (user_id, event_id, rating, message)
                VALUES (?, ?, ?, ?)"
            );

            if (!$stmt) {

                $error = "Database error: " . $conn->error;

            } else {

                $stmt->bind_param(
                    "iiis",
                    $user_id,
                    $event_id,
                    $rating,
                    $feedback_message
                );

                if ($stmt->execute()) {

                    $message = "Thank you! Your feedback has been submitted successfully.";

                } else {

                    $error = "Feedback submission failed: " . $stmt->error;
                }

                $stmt->close();
            }
        }

        $check->close();
    }
}


/* =========================
   GET EVENTS
   ========================= */

$events = $conn->query(
    "SELECT id, title, event_date
     FROM events
     ORDER BY event_date DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Feedback - College Events</title>

    <link rel="stylesheet"
          href="../assets/css/style.css">

    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6fb;
        }

        .feedback-page {
            max-width: 850px;
            margin: 60px auto;
            padding: 20px;
        }

        .feedback-box {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.08);
        }

        .feedback-box h1 {
            margin-top: 0;
            color: #1f2937;
            font-size: 32px;
        }

        .subtitle {
            color: #6b7280;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #374151;
        }

        select,
        textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 14px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 15px;
        }

        textarea {
            min-height: 140px;
            resize: vertical;
        }

        .rating {
            display: flex;
            gap: 12px;
            margin-top: 10px;
        }

        .rating label {
            display: inline-block;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            cursor: pointer;
            font-weight: normal;
        }

        .rating input {
            margin-right: 5px;
        }

        .submit-btn {
            border: none;
            background: #4f46e5;
            color: white;
            padding: 14px 28px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .back-btn {
            display: inline-block;
            margin-left: 10px;
            padding: 14px 25px;
            background: #e5e7eb;
            color: #374151;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        @media (max-width: 600px) {

            .feedback-box {
                padding: 25px;
            }

            .rating {
                flex-wrap: wrap;
            }

        }

    </style>

</head>

<body>

<div class="feedback-page">

    <div class="feedback-box">

        <h1>⭐ Event Feedback</h1>

        <p class="subtitle">
            Share your experience and help us improve future college events.
        </p>


        <?php if ($message !== ""): ?>

            <div class="success">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php endif; ?>


        <?php if ($error !== ""): ?>

            <div class="error">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php endif; ?>


        <form method="POST">


            <!-- EVENT -->

            <div class="form-group">

                <label for="event_id">
                    Select Event
                </label>

                <select name="event_id"
                        id="event_id"
                        required>

                    <option value="">
                        -- Select an Event --
                    </option>

                    <?php if ($events && $events->num_rows > 0): ?>

                        <?php while ($event = $events->fetch_assoc()): ?>

                            <option value="<?php echo $event["id"]; ?>">

                                <?php echo htmlspecialchars($event["title"]); ?>

                            </option>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <option value="">
                            No events available
                        </option>

                    <?php endif; ?>

                </select>

            </div>


            <!-- RATING -->

            <div class="form-group">

                <label>
                    Give Rating
                </label>

                <div class="rating">

                    <label>
                        <input type="radio"
                               name="rating"
                               value="1"
                               required>
                        ⭐ 1
                    </label>

                    <label>
                        <input type="radio"
                               name="rating"
                               value="2">
                        ⭐⭐ 2
                    </label>

                    <label>
                        <input type="radio"
                               name="rating"
                               value="3">
                        ⭐⭐⭐ 3
                    </label>

                    <label>
                        <input type="radio"
                               name="rating"
                               value="4">
                        ⭐⭐⭐⭐ 4
                    </label>

                    <label>
                        <input type="radio"
                               name="rating"
                               value="5">
                        ⭐⭐⭐⭐⭐ 5
                    </label>

                </div>

            </div>


            <!-- MESSAGE -->

            <div class="form-group">

                <label for="message">
                    Your Feedback
                </label>

                <textarea
                    name="message"
                    id="message"
                    placeholder="Write your experience here..."
                    required
                ></textarea>

            </div>


            <!-- BUTTONS -->

            <button type="submit"
                    class="submit-btn">

                ⭐ Submit Feedback

            </button>


            <a href="dashboard.php"
               class="back-btn">

                ← Dashboard

            </a>

        </form>

    </div>

</div>

</body>

</html>