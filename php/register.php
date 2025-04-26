<?php
session_start();
include 'db_connect.php'; // Connect to the database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form input values
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user into the database
    $sql = "INSERT INTO users (username, password, email, phone_number) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // For now, use the phone number as username and a dummy email
    $dummy_email = $phone_number . "@example.com"; // TODO: Improve this later

    if ($stmt) {
        $stmt->bind_param("ssss", $phone_number, $hashed_password, $dummy_email, $phone_number);
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! You can now log in.'); window.location.href='../login-register.html';</script>";
        } else {
            echo "<script>alert('Registration failed: " . $stmt->error . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Something went wrong!'); window.history.back();</script>";
    }
}
?>
