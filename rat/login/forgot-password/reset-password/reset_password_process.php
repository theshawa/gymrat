<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("method not allowed");
}

session_start();

$password = $_POST['password'];
$repeat_password = $_POST['repeat_password'];

if ($password !== $repeat_password) {
    die("Passwords do not match");
}

echo "Password reset successfully!";
echo "<br/>This is a dummy message, reset logic is yet to be implemented.";
