<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Method not allowed");
}

// ----------------
$workout = &$_SESSION['workout'];
var_dump($workout);
echo "\n";
var_dump($_POST);
// ----------------