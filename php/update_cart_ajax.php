<?php
session_start();

if (isset($_POST['item_id']) && isset($_POST['action'])) {
    $item_id = intval($_POST['item_id']);
    $action = $_POST['action'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if ($action === "add") {
        if (isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id]++;
        } else {
            $_SESSION['cart'][$item_id] = 1;
        }
    }

    if ($action === "remove") {
        if (isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id]--;
            if ($_SESSION['cart'][$item_id] <= 0) {
                unset($_SESSION['cart'][$item_id]);
            }
        }
    }

    // Return updated quantity back to JS
    $new_qty = isset($_SESSION['cart'][$item_id]) ? $_SESSION['cart'][$item_id] : 0;

    echo json_encode([
        'success' => true,
        'quantity' => $new_qty
    ]);
} else {
    echo json_encode([
        'success' => false
    ]);
}
?>
