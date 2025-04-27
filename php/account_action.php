<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login-register.html');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'update_phone') {
        $new_phone = trim($_POST['new_phone']);

        if (!empty($new_phone)) {
            // Check if phone number already exists
            $stmt = $conn->prepare("SELECT user_id FROM users WHERE phone_number = ? AND user_id != ?");
            $stmt->bind_param("si", $new_phone, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                header('Location: ../account.php?error=phone_taken');
                exit();
            }

            // Update phone number
            $stmt = $conn->prepare("UPDATE users SET phone_number = ? WHERE user_id = ?");
            $stmt->bind_param("si", $new_phone, $user_id);
            $stmt->execute();

            $_SESSION['phone_number'] = $new_phone;
            header('Location: ../account.php?success=phone_updated');
            exit();
        }

    } elseif ($action === 'update_password') {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            header('Location: ../account.php?error=password_mismatch');
            exit();
        }

        // Check current password
        $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($current_password, $user['password'])) {
            $new_hashed = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $stmt->bind_param("si", $new_hashed, $user_id);
            $stmt->execute();

            header('Location: ../account.php?success=password_updated');
            exit();
        } else {
            header('Location: ../account.php?error=wrong_current_password');
            exit();
        }

    } elseif ($action === 'update_address') {
        $address1 = trim($_POST['address1']);
        $address2 = trim($_POST['address2']);
        $eircode = trim($_POST['eircode']);

        if (!empty($address1) && !empty($eircode)) {
            $full_address = $address1 . ', ' . $address2 . ', ' . $eircode;

            $stmt = $conn->prepare("UPDATE users SET address = ? WHERE user_id = ?");
            $stmt->bind_param("si", $full_address, $user_id);
            $stmt->execute();

            header('Location: ../account.php?success=address_updated');
            exit();
        } else {
            header('Location: ../account.php?error=address_fields_missing');
            exit();
        }

    } elseif ($action === 'delete_account') {
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        session_destroy();
        header('Location: ../index.html');
        exit();
    }
}

// fallback
header('Location: ../account.php');
exit();
?>
