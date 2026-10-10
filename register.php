<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "database.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($name == "" || $email == "" || $password == "" || $confirm_password == "") {

        $message = "Please fill all fields.";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $message_type = "error";

    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";
        $message_type = "error";

    } elseif ($password != $confirm_password) {

        $message = "Passwords do not match.";
        $message_type = "error";

    } else {

        /* Check existing email */
        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {

            $message = "This email is already registered.";
            $message_type = "warning";

        } else {

            /* Hash password */
            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            /* Insert student */
            $insert = $conn->prepare(
                "INSERT INTO users (name, email, password, role)
                 VALUES (?, ?, ?, 'student')"
            );

            $insert->bind_param(
                "sss",
                $name,
                $email,
                $hashed_password
            );

            if ($insert->execute()) {

                $message = "Account created successfully!";
                $message_type = "success";

                $name = "";
                $email = "";

            } else {

                $message = "Account creation failed: " . $insert->error;
                $message_type = "error";
            }

            $insert->close();
        }

        $check->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Create Account | College Events</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    min-height: 100vh;

    font-family: Arial, sans-serif;

    background: linear-gradient(
        135deg,
        #667eea,
        #764ba2
    );

    display: flex;
    justify-content: center;
    align-items: center;

    padding: 30px 15px;
}

.card {

    width: 100%;
    max-width: 480px;

    background: white;

    padding: 40px;

    border-radius: 25px;

    box-shadow:
        0 25px 60px
        rgba(0,0,0,0.20);
}

.logo {

    width: 75px;
    height: 75px;

    margin: auto;

    border-radius: 50%;

    background: linear-gradient(
        135deg,
        #667eea,
        #764ba2
    );

    display: flex;
    justify-content: center;
    align-items: center;

    font-size: 35px;
}

h1 {

    text-align: center;

    color: #222;

    margin: 18px 0 8px;
}

.subtitle {

    text-align: center;

    color: #777;

    margin-bottom: 28px;

    line-height: 1.5;
}

.message {

    padding: 14px;

    border-radius: 12px;

    text-align: center;

    font-weight: bold;

    margin-bottom: 20px;
}

.success {

    background: #e8fff1;

    color: #168044;
}

.error {

    background: #ffe8e8;

    color: #c0392b;
}

.warning {

    background: #fff5d8;

    color: #9a7100;
}

.form-group {

    margin-bottom: 18px;
}

label {

    display: block;

    font-weight: bold;

    color: #333;

    margin-bottom: 7px;
}

input {

    width: 100%;

    padding: 14px;

    border: 1px solid #ddd;

    border-radius: 11px;

    font-size: 15px;

    outline: none;
}

input:focus {

    border-color: #667eea;

    box-shadow:
        0 0 0 3px
        rgba(102,126,234,0.12);
}

.button {

    width: 100%;

    padding: 15px;

    border: none;

    border-radius: 12px;

    background: linear-gradient(
        135deg,
        #667eea,
        #764ba2
    );

    color: white;

    font-size: 16px;

    font-weight: bold;

    cursor: pointer;

    margin-top: 5px;
}

.login {

    text-align: center;

    margin-top: 22px;

    color: #777;
}

.login a {

    color: #667eea;

    text-decoration: none;

    font-weight: bold;
}

.home {

    display: block;

    text-align: center;

    margin-top: 14px;

    color: #999;

    text-decoration: none;

    font-size: 14px;
}

</style>

</head>

<body>

<div class="card">

    <div class="logo">
        🎓
    </div>

    <h1>Create Account</h1>

    <p class="subtitle">
        Join CollegeEvents and participate in college events.
    </p>


    <?php if ($message != "") { ?>

        <div class="message <?php echo $message_type; ?>">

            <?php echo htmlspecialchars($message); ?>

            <?php if ($message_type == "success") { ?>

                <br><br>

                <a href="login.php">
                    Login Now →
                </a>

            <?php } ?>

        </div>

    <?php } ?>


    <form method="POST" action="register.php">

        <div class="form-group">

            <label>Full Name</label>

            <input
                type="text"
                name="name"
                placeholder="Enter your full name"
                value="<?php echo htmlspecialchars($name ?? ""); ?>"
                required
            >

        </div>


        <div class="form-group">

            <label>Email Address</label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                value="<?php echo htmlspecialchars($email ?? ""); ?>"
                required
            >

        </div>


        <div class="form-group">

            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Create password"
                required
            >

        </div>


        <div class="form-group">

            <label>Confirm Password</label>

            <input
                type="password"
                name="confirm_password"
                placeholder="Enter password again"
                required
            >

        </div>


        <button type="submit" class="button">
            🚀 Create Student Account
        </button>

    </form>


    <div class="login">

        Already have an account?

        <a href="login.php">
            Login here
        </a>

    </div>


    <a href="index.php" class="home">
        ← Back to Home
    </a>

</div>

</body>

</html>