<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/alerts/functions.php";

function is_auth_valid($check_activated = true): bool
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

    if ($check_activated && isset($_SESSION['auth']['activated']) && $_SESSION['auth']['activated'] === false) {
        return false;
    }

    return true;
}

function is_auth_valid_with_role(string $role, bool $check_activated = false): bool
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

    if (!isset($_SESSION['auth']['role']) || $_SESSION['auth']['role'] !== $role) {
        return false;
    }

    if ($check_activated && isset($_SESSION['auth']['activated']) && $_SESSION['auth']['activated'] === false) {
        return false;
    }

    return true;
}

function auth_required_guard(string $redirect, $check_activated = true)
{
    if (!is_auth_valid($check_activated)) {
        redirect_with_error_alert("You need to login first", $redirect);
    }
}

function auth_not_required_guard(string $redirect, $check_activated = true)
{
    if (is_auth_valid($check_activated)) {
        redirect_with_error_alert("You are already logged in", $redirect);
    }
}

function auth_required_guard_with_role(string $role, string $redirect, $check_activated = false)
{
    if (!is_auth_valid_with_role($role, $check_activated)) {
        redirect_with_error_alert("You need to login first", $redirect);
    }
}

function auth_not_required_guard_with_role(string $role, string $redirect, $check_activated = false)
{
    if (is_auth_valid_with_role($role, $check_activated)) {
        redirect_with_error_alert("You are already logged in", $redirect);
    }
}
