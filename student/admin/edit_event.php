<?php
require_once "../database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $event_date = $_POST["event_date"];
    $event_time = $_POST["event_time"];

    if ($title == "" || $description == "" || $event_date == "" || $event_time == "") {

        $message = "Please fill all fields.";

    } else {

        $sql = "INSERT INTO events 
                (title, description, event_date, event_time)
                VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            die("SQL Error: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $title,
            $description,
            $event_date,
            $event_time
        );

        if (mysqli_stmt_execute($stmt)) {

            $message = "Event added successfully!";

        } else {

            $message = "Error: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Add Event</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 40px;
        }

        .container {
            width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        h2 {
            text-align: center;
        }

        label {
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>Add Event</h2>

    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php } ?>

    <form method="POST">

        <label>Event Name</label>

        <input
            type="text"
            name="title"
            required
        >

        <label>Description</label>

        <textarea
            name="description"
            required
        ></textarea>

        <label>Event Date</label>

        <input
            type="date"
            name="event_date"
            required
        >

        <label>Event Time</label>

        <input
            type="time"
            name="event_time"
            required
        >

        <button type="submit">
            Add Event
        </button>

    </form>

</div>

</body>
</html>