<?php

session_start();

require_once "database.php";

/* Check if user is logged in */
if (isset($_SESSION["user_id"])) {

    $user_id = (int) $_SESSION["user_id"];

    /* Save logout history in MySQL */
    $stmt = $conn->prepare(
        "INSERT INTO logout_history (user_id)
         VALUES (?)"
    );

    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

/* Destroy session */
$_SESSION = array();

session_destroy();

/* Redirect to login page */
header("Location: login.php");
exit;

?>