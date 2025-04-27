<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone_number = trim($_POST['phone_number']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        header("Location: ../login-register.html?error=password_mismatch");
        exit();
    }

    // Check if phone number already exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE phone_number = ?");
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: ../login-register.html?error=phone_taken");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (phone_number, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $phone_number, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['phone_number'] = $phone_number;
        header("Location: ../menu.php");
        exit();
    } else {
        header("Location: ../login-register.html?error=register_failed");
        exit();
    }
}
?>
