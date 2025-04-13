<?php

session_start();

// Array of session variables to keep
$keepVariables = ['auth', 'workout_id'];

foreach ($_SESSION as $key => $value) {
    if (!in_array($key, $keepVariables)) {
        unset($_SESSION[$key]);
    }
}

$redirect = isset($_GET['goBackTo']) ? htmlspecialchars($_GET['goBackTo']) : 'index.php';

header("Location: $redirect");
exit();
