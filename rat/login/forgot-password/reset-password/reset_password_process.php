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

// TODO: Reset password logic here

header("Location: /rat/login?alert=Password reset successfully!");
