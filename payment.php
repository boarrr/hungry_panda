<?php
session_start();
include 'php/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login-register.html");
    exit();
}

// Fetch user address if available
$address1 = $address2 = $eircode = "";
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT address FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && !empty($user['address'])) {
    $address_parts = explode(",", $user['address']);
    $address1 = isset($address_parts[0]) ? trim($address_parts[0]) : "";
    $address2 = isset($address_parts[1]) ? trim($address_parts[1]) : "";
    $eircode = isset($address_parts[2]) ? trim($address_parts[2]) : "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hungry Panda - Checkout</title>
    <link rel="stylesheet" href="css/main.css">
    <script defer src="js/main.js"></script>
</head>

<body>
<div class="screen-divide container-x">
    <div class="navbar">
        <div class="logo">
            <a href="./">
                <div class="img">
                    <img class="logo-img" src="assets/hungry-panda-logo.png" alt="Logo">
                </div>
                <h3>Hungry Panda</h3>
            </a>
        </div>

        <div class="navbar-items">
            <a href="./"><button class="navbar-button">Home</button></a>
            <a href="menu.php"><button class="navbar-button">Menu</button></a>
            <a href="contact-us.php"><button class="navbar-button">Contact Us</button></a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="account.php"><button class="navbar-button">Account</button></a>
                <a href="php/logout.php"><button class="navbar-button">Logout</button></a>
            <?php else: ?>
                <a href="login-register.html"><button class="navbar-button">Login</button></a>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-content payment-content">
        <a href="menu.php"><button class="button right-tp-corner">Return</button></a>

        <h2>Checkout</h2>

        <div class="order">
            <h3>Order Receipt</h3>

            <table class="order-table">
                <thead>
                    <tr>
                        <th class="qty-col" style="text-align: center;">QTY.</th>
                        <th class="item-col" style="text-align: left;">Order Item</th>
                        <th class="price-col" style="text-align: center;">€</th>
                        <th class="controls-col"></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($_SESSION['cart'])) {
                    $total = 0;
                    foreach ($_SESSION['cart'] as $item_id => $quantity) {
                        $stmt = $conn->prepare("SELECT name, price FROM menu_items WHERE item_id = ?");
                        $stmt->bind_param("i", $item_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $item = $result->fetch_assoc();

                        if ($item) {
                            $item_name = htmlspecialchars($item['name']);
                            $item_price = $item['price'];
                            $item_total = $item_price * $quantity;
                            $total += $item_total;

                            echo "<tr>
                                <td class='qty-col' style='text-align: center;'>$quantity</td>
                                <td class='item-col' style='text-align: left;'>$item_name</td>
                                <td class='price-col' style='text-align: center;'>&euro;" . number_format($item_total, 2) . "</td>
                                <td class='controls-col'>
                                    <div class='quantity-btn' style='justify-content: flex-end;'>
                                        <button type='button' class='button add-btn' data-id='$item_id'>+</button>
                                        <button type='button' class='button remove-btn' data-id='$item_id'>-</button>
                                    </div>
                                </td>
                            </tr>";
                        }
                    }
                    echo "</tbody></table>";

                    echo "<div class='order-total'>
                        Total: <strong>€" . number_format($total, 2) . "</strong>
                    </div>";
                } else {
                    echo "<tr><td colspan='4' style='text-align:center;'>Your cart is empty!</td></tr></tbody></table>";
                }
                ?>
            
            <div class="payment-methods">
                <h3>Payment Methods</h3>
                <div class="btn-group">
                    <button id="cash-btn" class="left-btn selected-payment">Cash</button>
                    <button id="card-btn" class="right-btn">Card</button>
                </div>

                <div id="notification-modal" class="custom-modal">
                    Card payment is currently unavailable.
                </div>
            </div>

            <?php if (!empty($_SESSION['cart'])): ?>
                <form action="php/place_order.php" method="POST" class="delivery-address">
                    <input type="text" id="address1" name="address1" placeholder="Address Line 1" value="<?php echo htmlspecialchars($address1); ?>" required><br />
                    <input type="text" id="address2" name="address2" placeholder="Address Line 2" value="<?php echo htmlspecialchars($address2); ?>"><br />
                    <input type="text" id="eircode" name="eircode" placeholder="D01 XXXX" value="<?php echo htmlspecialchars($eircode); ?>" required><br />
                    <button type="submit" class="button btn-payment">Order</button>
                </form>
            <?php else: ?>
                <div class="delivery-address">
                    <p style="color: red; font-weight: bold; text-align: center;">You cannot place an order with an empty cart!</p>
                    <a href="menu.php"><button class="button" style="margin-top: 1rem;">Return</button></a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<footer>
    <h6>Ryan Pitman (C23741429) | Hungry Panda</h6>
</footer>

</body>
</html>
