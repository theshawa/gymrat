<?php

session_start();

require_once "../../../alerts/functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_info_alert('Method not allowed', '/rat/register');
}

var_dump($_POST);
