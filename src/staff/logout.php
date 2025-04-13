<?php

require_once "../alerts/functions.php";

// THIS IS WRONG? Because this doesnt do anything to actually log one off?
if (session_status() == PHP_SESSION_ACTIVE) {
    $_SESSION = [];
    session_destroy();
}

redirect_with_success_alert("Logout Successful", "/staff/login");
