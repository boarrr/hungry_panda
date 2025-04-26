<?php 
session_start();
include 'php/db_connect.php';

// Fetch menu items from the database
$sql = "SELECT * FROM menu_items WHERE is_available = 1";
$result = $conn->query($sql);

$menu_items = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $menu_items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <script defer src="js/main.js"></script>
    <title>Hungry Panda - Menu</title>
</head>

<body>
<div class="menu screen-divide container-x">
    <div class="navbar">
        <div class="logo">
            <a href="index.html">
                <div class="img">
                    <img class="logo-img" src="assets/hungry-panda-logo.png" alt="Logo">
                </div>
                <h3>Hungry Panda</h3>
            </a>
        </div>
        <div class="navbar-items">
            <a href="index.html"><button class="navbar-button">Home</button></a>
            <a href="menu.php"><button class="navbar-button">Menu</button></a>
            <a href="contact-us.php"><button class="navbar-button">Contact Us</button></a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="php/logout.php"><button class="navbar-button">Logout</button></a>
            <?php else: ?>
                <a href="login-register.html"><button class="navbar-button">Login</button></a>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-content menu-content">

        <div class="checkout-wrapper">
            <a href="payment.php">
                <button class="button right-btm-corner">Checkout</button>
            </a>

            <div id="checkout-summary" class="checkout-summary-modal">
                <?php
                if (!empty($_SESSION['cart'])) {
                    echo "<h4>Order Summary:</h4>";
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

                            echo "<div style='margin-bottom: 10px;'>
                                    {$item_name} x{$quantity} = €" . number_format($item_total, 2) . "
                                </div>";
                        }
                    }

                    echo "<hr>";
                    echo "<strong>Total: €" . number_format($total, 2) . "</strong>";
                } else {
                    echo "Your cart is empty!";
                }
                ?>
            </div>
        </div>

        <h2>Menu Items</h2>

        <div class="menu-items">
            <?php foreach ($menu_items as $item): ?>
                <div class="m-item">
                    <div>
                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    </div>
                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                    <h3>€<?php echo number_format($item['price'], 2); ?></h3>

                    <div class="quantity-btn screen-divide">
                        <div><h5>QTY: <span id="qty-<?php echo $item['item_id']; ?>"><?php echo isset($_SESSION['cart'][$item['item_id']]) ? $_SESSION['cart'][$item['item_id']] : 0; ?></span></h5></div>
                        <button type="button" class="button add-btn" data-id="<?php echo $item['item_id']; ?>">+</button>
                        <button type="button" class="button remove-btn" data-id="<?php echo $item['item_id']; ?>">-</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>

<footer>
    <h6>Ryan Pitman (C23741429) | Hungry Panda</h6>
</footer>

</body>
</html>
