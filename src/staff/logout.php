<?php

require_once "../alerts/functions.php";

session_destroy();

redirect_with_success_alert("Logout Successful", "/staff/login");
