<?php
session_start();
include 'db_connect.php';

if (isset($_POST['item_id']) && isset($_POST['action'])) {
    $item_id = intval($_POST['item_id']);
    $action = $_POST['action'];

    // Initialize cart if not yet
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add item
    if ($action === "add") {
        if (isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id]++;
        } else {
            $_SESSION['cart'][$item_id] = 1;
        }
    }

    // Remove item
    if ($action === "remove") {
        if (isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id]--;
            if ($_SESSION['cart'][$item_id] <= 0) {
                unset($_SESSION['cart'][$item_id]);
            }
        }
    }
}

// After action, redirect back to menu
header("Location: ../menu.php");
exit();
?>
