<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/alerts/functions.php";

function is_auth_valid(): bool
{
    if (!isset($_SESSION['auth'])) {
        return false;
    }

    $created_at = $_SESSION['auth']['session_started_at'];
    $now = time();
    $diff = $now - $created_at;

    require_once __DIR__ . "/constants.php";
    if ($diff > $SESSION_EXPIRE_TIME) {
        return false;
    }

    return true;
}

function auth_required_guard(string $redirect)
{
    if (!is_auth_valid()) {
        redirect_with_error_alert("You need to login first", $redirect);
    }
}

function auth_not_required_guard(string $redirect)
{
    if (is_auth_valid()) {
        redirect_with_error_alert("You are already logged in", $redirect);
    }
}
