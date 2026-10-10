<?php

session_start();
require_once "../database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$sql = "SELECT id, name, email, created_at
        FROM users
        WHERE role = 'student'
        ORDER BY created_at DESC";

$result = $conn->query($sql);

if (!$result) {
    die("Database Error: " . htmlspecialchars($conn->error));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Students | College Events Admin</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6ff;
        }

        .navbar {
            background: white;
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
        }

        .nav-links {
            display: flex;
            gap: 10px;
        }

        .nav-links a {
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            color: #333;
        }

        .dashboard {
            background: #4f46e5;
            color: white !important;
        }

        .logout {
            background: #dc2626;
            color: white !important;
        }

        .container {
            width: 92%;
            max-width: 1100px;
            margin: 40px auto;
        }

        h1 {
            color: #222;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #777;
            margin-bottom: 25px;
        }

        .table-box {
            background: white;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #4f46e5;
            color: white;
            padding: 14px;
            text-align: left;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            color: #444;
        }

        tr:hover {
            background: #f8f9ff;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #777;
        }

        @media (max-width: 700px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">

    <div class="logo">
        👨‍💼 College Events Admin
    </div>

    <div class="nav-links">

        <a href="dashboard.php" class="dashboard">
            🏠 Dashboard
        </a>

        <a href="../logout.php" class="logout">
            Logout
        </a>

    </div>

</nav>


<div class="container">

    <h1>👥 Student Information</h1>

    <p class="subtitle">
        View all students registered in the College Event Management System.
    </p>


    <div class="table-box">

        <?php if ($result->num_rows > 0): ?>

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Account Created</th>
                    </tr>
                </thead>

                <tbody>

                    <?php while ($student = $result->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php echo (int)$student["id"]; ?>
                            </td>

                            <td>
                                👤
                                <?php echo htmlspecialchars($student["name"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($student["email"]); ?>
                            </td>

                            <td>
                                <?php
                                echo date(
                                    "d M Y, h:i A",
                                    strtotime($student["created_at"])
                                );
                                ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        <?php else: ?>

            <div class="empty">
                <h2>No Students Found</h2>
                <p>No student accounts have been registered yet.</p>
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>