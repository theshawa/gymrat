<?php

require_once "../auth-guards.php";
require_once "../alerts/functions.php";

auth_required_guard("/staff/login");

session_destroy();

redirect_with_success_alert("Logout Successful", "/staff/login");
