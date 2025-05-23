<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/alerts/functions.php";
require_once __DIR__ . "/config.php";

function is_auth_valid(string $role): bool
{
    if (!isset($_SESSION['auth'])) {
        return false;
    }

    $created_at = $_SESSION['auth']['session_started_at'];
    $now = time();
    $diff = $now - $created_at;

    if ($diff > SESSION_EXPIRE_TIME) {
        return false;
    }

    if (!isset($_SESSION['auth']['role']) || $_SESSION['auth']['role'] !== $role) {
        return false;
    }

    return true;
}

function auth_required_guard(string $role, string $redirect): bool
{
    $must_redirect = !is_auth_valid($role);
    if ($must_redirect) {
        redirect_with_error_alert("You need to login first", $redirect);
    }
    return $must_redirect;
}

function auth_not_required_guard(string $role, string $redirect): bool
{
    $must_redirect = is_auth_valid($role);
    if ($must_redirect) {
        redirect_with_error_alert("You are already logged in", $redirect);
    }
    return $must_redirect;
}
