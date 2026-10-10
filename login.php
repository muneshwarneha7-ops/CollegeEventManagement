<?php
session_start();
require_once "database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $error = "Please enter email and password.";
    } else {

        $stmt = $conn->prepare(
            "SELECT id, name, email, password, role
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["user_email"] = $user["email"];
                $_SESSION["role"] = $user["role"];

                /* Save login history */
                $history = $conn->prepare(
                    "INSERT INTO login_history (user_id)
                     VALUES (?)"
                );

                if ($history) {
                    $history->bind_param("i", $user["id"]);
                    $history->execute();
                    $history->close();
                }

                if ($user["role"] === "admin") {
                    header("Location: admin/dashboard.php");
                    exit;
                } else {
                    header("Location: student/dashboard.php");
                    exit;
                }

            } else {
                $error = "Incorrect password.";
            }

        } else {
            $error = "Account not found. Please register first.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | CollegeEvents</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(
                135deg,
                #eef2ff,
                #fdf2f8,
                #ecfeff
            );
        }

        /* NAVBAR */

        .navbar {
            width: 100%;
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
        }

        .nav-links {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .login-btn {
            background: linear-gradient(135deg, #4f46e5, #db2777);
            color: white !important;
            padding: 10px 20px;
            border-radius: 25px;
        }

        /* MAIN */

        .main {
            min-height: calc(100vh - 75px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .login-container {
            width: 950px;
            max-width: 100%;
            background: white;
            border-radius: 25px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }

        /* LEFT */

        .left-panel {
            padding: 55px 45px;
            color: white;
            background: linear-gradient(
                135deg,
                #4f46e5,
                #7c3aed,
                #db2777
            );
        }

        .left-panel h1 {
            font-size: 36px;
            margin-bottom: 18px;
        }

        .left-panel p {
            line-height: 1.7;
            margin-bottom: 30px;
            opacity: 0.95;
        }

        .features {
            list-style: none;
        }

        .features li {
            margin: 18px 0;
            font-size: 16px;
        }

        /* RIGHT */

        .right-panel {
            padding: 55px 45px;
        }

        .right-panel h2 {
            font-size: 30px;
            color: #222;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #777;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 14px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
        }

        .form-group input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
        }

        .password-box {
            position: relative;
        }

        .password-box input {
            padding-right: 55px;
        }

        .show-password {
            position: absolute;
            right: 15px;
            top: 13px;
            cursor: pointer;
            color: #666;
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #4f46e5, #db2777);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .submit-btn:hover {
            opacity: 0.9;
        }

        .register-text {
            text-align: center;
            margin-top: 22px;
            color: #666;
        }

        .register-text a {
            color: #4f46e5;
            font-weight: bold;
            text-decoration: none;
        }

        .contact {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #777;
            line-height: 1.8;
        }

        /* MOBILE */

        @media (max-width: 800px) {

            .nav-links a:not(.login-btn) {
                display: none;
            }

            .login-container {
                grid-template-columns: 1fr;
            }

            .left-panel {
                padding: 35px 30px;
            }

            .right-panel {
                padding: 35px 30px;
            }

            .left-panel h1 {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">

    <div class="logo">
        🎓 CollegeEvents
    </div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="index.php#events">Events</a>
        <a href="index.php#about">About</a>
        <a href="index.php#contact">Contact</a>
        <a href="login.php" class="login-btn">Login</a>
    </div>

</nav>


<div class="main">

    <div class="login-container">

        <!-- LEFT SIDE -->

        <div class="left-panel">

            <h1>Welcome Back to CollegeEvents! 🎉</h1>

            <p>
                Login to explore exciting college events,
                register for activities and manage your
                event participation.
            </p>

            <ul class="features">

                <li>🎯 Discover upcoming college events</li>

                <li>📝 Register for your favourite events</li>

                <li>📅 Manage your event registrations</li>

                <li>🏆 Participate in competitions</li>

                <li>💡 Explore workshops and activities</li>

            </ul>

        </div>


        <!-- RIGHT SIDE -->

        <div class="right-panel">

            <h2>Login</h2>

            <p class="subtitle">
                Enter your account details to continue.
            </p>


            <?php if ($error !== ""): ?>

                <div class="error">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>


            <form method="POST" action="">

                <div class="form-group">

                    <label>Email Address</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Password</label>

                    <div class="password-box">

                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Enter your password"
                            required
                        >

                        <span
                            class="show-password"
                            onclick="togglePassword()"
                        >
                            👁
                        </span>

                    </div>

                </div>


                <button type="submit" class="submit-btn">
                    Login to CollegeEvents →
                </button>

            </form>


            <div class="register-text">

                Don't have an account?
                <a href="register.php">Create Account</a>

            </div>


            <div class="contact">

                
                🏫 Sipna College of Engineering<br>
                📍 Amravati, Maharashtra<br>
                📧 college@example.com
                📞 +91 86057 XXXXX

            </div>

        </div>

    </div>

</div>


<script>

function togglePassword() {

    const password = document.getElementById("password");

    if (password.type === "password") {

        password.type = "text";

    } else {

        password.type = "password";

    }
}

</script>

</body>
</html>