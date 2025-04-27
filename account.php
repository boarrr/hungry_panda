<?php
session_start();
include 'php/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login-register.html");
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT phone_number, address FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$phone_number = $user['phone_number'];
$full_address = $user['address'] ?? '';

// Try splitting the address nicely
$address1 = $address2 = $eircode = '';
if (!empty($full_address)) {
    $parts = explode(',', $full_address);
    $address1 = trim($parts[0]);
    $address2 = isset($parts[1]) ? trim($parts[1]) : '';
    $eircode = isset($parts[2]) ? trim($parts[2]) : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Settings - Hungry Panda</title>
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
        <h2>Account Settings</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="custom-modal show" style="background-color: green; color: white; text-align: center; margin-bottom: 20px;">
                <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $_GET['success']))); ?>!
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="custom-modal show" style="background-color: red; color: white; text-align: center; margin-bottom: 20px;">
                <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $_GET['error']))); ?>.
            </div>
        <?php endif; ?>


        <div class="login-box">
            <h3>Update Phone Number</h3>
            <form action="php/account_action.php" method="POST">
                <input type="text" name="new_phone" placeholder="Enter new phone number" value="<?php echo htmlspecialchars($phone_number); ?>" required>
                <button type="submit" name="action" value="update_phone" class="button">Update</button>
            </form>
        </div>

        <div class="login-box">
            <h3>Change Password</h3>
            <form action="php/account_action.php" method="POST">
                <input type="password" name="current_password" placeholder="Current password" required>
                <input type="password" name="new_password" placeholder="New password" required>
                <input type="password" name="confirm_password" placeholder="Confirm new password" required>
                <button type="submit" name="action" value="update_password" class="button">Update</button>
            </form>
        </div>

        <div class="login-box">
            <h3>Update Address</h3>
            <form action="php/account_action.php" method="POST">
                <input type="text" name="address1" placeholder="Address Line 1" value="<?php echo htmlspecialchars($address1); ?>" required>
                <input type="text" name="address2" placeholder="Address Line 2" value="<?php echo htmlspecialchars($address2); ?>">
                <input type="text" name="eircode" placeholder="D01 XXXX" value="<?php echo htmlspecialchars($eircode); ?>" required>
                <button type="submit" name="action" value="update_address" class="button">Update</button>
            </form>
        </div>

        <div class="login-box" style="background-color: #ffe5e5;">
            <h3>Deactivate Account</h3>
            <form action="php/account_action.php" method="POST" onsubmit="return confirm('Are you sure you want to deactivate your account? This cannot be undone!');">
                <button type="submit" name="action" value="delete_account" class="button" style="background-color: red;">Deactivate</button>
            </form>
        </div>

    </div>
</div>

<footer>
    <h6>Ryan Pitman (C23741429) | Hungry Panda</h6>
</footer>

</body>
</html>
