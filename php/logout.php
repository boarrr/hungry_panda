<?php
session_start();
session_destroy(); // Clears all session data
header("Location: ../login-register.html");
exit();
