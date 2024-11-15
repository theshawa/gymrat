<?php


require_once "../../../../alerts/functions.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_with_error_alert("Method not allowed", "/rat/login");
}

var_dump($_POST);
