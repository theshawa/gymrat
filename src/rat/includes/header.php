<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = null;
$pageStyles = [];

$need_auth = null;
$dont_need_active_subscription = null;
if (isset($pageConfig)) {
    $pageTitle = $pageConfig['title'] ?? null;
    $pageStyles = $pageConfig['styles'] ?? [];
    $need_auth = $pageConfig['need_auth'] ?? false;
    $dont_need_active_subscription = $pageConfig['dont_need_active_subscription'] ?? false;
}

require_once __DIR__ . "/../../auth-guards.php";

if ($need_auth) {
    auth_required_guard_with_role("rat", "/rat/login", !$dont_need_active_subscription);
} else {
    auth_not_required_guard_with_role("rat", "/rat", !$dont_need_active_subscription);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYMRAT<?php echo $pageTitle ? " | " . $pageTitle : "" ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/rat/styles/globals.css">
    <link rel="stylesheet" href="/rat/styles/components.css">
    <?php
    foreach ($pageStyles as $style) {
        echo "<link rel='stylesheet' href='$style'/>";
    }
    ?>
</head>

<body>
    <?php require_once __DIR__ . "/../../alerts/view.php" ?>