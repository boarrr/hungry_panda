<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css"> 
    <script defer src="js/main.js"></script>
    <title>Hungry Panda - Contact Us</title>
</head>

<body>
<div class="menu screen-divide container-x">

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

    <a href="menu.php"><button class="button right-tp-corner">Order Now</button></a>

    <div class="bg-content contact-content">
        <div class="contact-sections">
            <div>
                <h2>Contact Us:</h2>
                <p>Email: contact@hungrypanda.com<br>
                Phone: 0123 456 789</p>
            </div>
            
            <div>
                <h2>Opening Hours</h2>
                <table class="opening-hours-table">
                    <tr>
                        <td>Mon–Fri</td>
                        <td>10:00 AM – 10:00 PM</td>
                    </tr>
                    <tr>
                        <td>Saturday</td>
                        <td>12:00 PM – 2:00 AM</td>
                    </tr>
                    <tr>
                        <td>Sunday</td>
                        <td>Closed</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</div>

<footer><h6>Ryan Pitman (C23741429) | Hungry Panda</h6></footer>

</body>
</html>
