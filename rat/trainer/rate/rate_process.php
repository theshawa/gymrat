<?php

session_start();

require_once "../../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    redirect_with_error_alert("Method not allowed", "../rate");
}

require_once "../../auth-guards.php";
auth_required_guard("/rat/login");

echo "Under construction";
