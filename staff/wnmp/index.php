<?php

$pageTitle = "Home";
$sidebarActive = 1;

require_once "./pageconfig.php";

require_once "../includes/header.php";
require_once "../includes/sidebar.php";
require_once "../../alerts/functions.php";

require_once "../../auth-guards.php";
auth_required_guard_with_role("wnmp", "/staff/login");
?>

<main>
    Home
</main>

<?php require_once "../includes/footer.php"; ?>