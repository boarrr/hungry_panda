<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-register.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if cart and address fields are set
if (!empty($_SESSION['cart']) && isset($_POST['address1']) && isset($_POST['eircode'])) {
    $address1 = trim($_POST['address1']);
    $address2 = trim($_POST['address2']);
    $eircode = trim($_POST['eircode']);

    // Combine full address nicely
    $full_address = $address1;
    if (!empty($address2)) {
        $full_address .= ", " . $address2;
    }
    $full_address .= ", " . $eircode;

    $total_amount = 0;

    // Start DB transaction
    $conn->begin_transaction();

    try {
        // Calculate total amount first
        foreach ($_SESSION['cart'] as $item_id => $quantity) {
            $item_stmt = $conn->prepare("SELECT price FROM menu_items WHERE item_id = ?");
            $item_stmt->bind_param("i", $item_id);
            $item_stmt->execute();
            $result = $item_stmt->get_result();
            $item = $result->fetch_assoc();
            if ($item) {
                $total_amount += $item['price'] * $quantity;
            }
        }

        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, delivery_address, status) VALUES (?, ?, ?, 'pending')");
        $stmt->bind_param("ids", $user_id, $total_amount, $full_address);
        $stmt->execute();

        $order_id = $conn->insert_id;

        // Insert each order item
        foreach ($_SESSION['cart'] as $item_id => $quantity) {
            $item_stmt = $conn->prepare("SELECT price FROM menu_items WHERE item_id = ?");
            $item_stmt->bind_param("i", $item_id);
            $item_stmt->execute();
            $result = $item_stmt->get_result();
            $item = $result->fetch_assoc();
            if ($item) {
                $item_price = $item['price'];

                $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, item_id, quantity, item_price) VALUES (?, ?, ?, ?)");
                $stmt_item->bind_param("iiid", $order_id, $item_id, $quantity, $item_price);
                $stmt_item->execute();
            }
        }

        // Commit transaction
        $conn->commit();

        // Clear cart
        unset($_SESSION['cart']);

        // Redirect to thank you page
        header("Location: ../thankyou.php");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        echo "Failed to place order: " . $e->getMessage();
    }
} else {
    echo "Missing cart data or required address fields.";
}
?>
