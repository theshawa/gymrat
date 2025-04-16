<?php

session_start();

require_once "../../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Method not allowed");
}

echo "Under construction";
