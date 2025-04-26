<?php

require_once "../alerts/functions.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$_SESSION = [];

session_destroy();

redirect_with_success_alert("Logout Successful", "/staff/login");
