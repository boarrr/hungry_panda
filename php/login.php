<?php
session_start();
include 'db_connect.php'; // include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the input values
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE phone_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if user exists and password matches
    if ($user && password_verify($password, $user['password'])) {
        // Store user info in session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];

        // Redirect to menu page
        header("Location: ../menu.php");
        exit();
    } else {
        // Login failed
        echo "<script>alert('Invalid phone number or password!'); window.history.back();</script>";
    }
}
?>
