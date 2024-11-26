<?php

$pageTitle = "Manage Meal Plans";
$sidebarActive = 5;

require_once "../pageconfig.php";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";

require_once "../../../auth-guards.php";
auth_required_guard_with_role("wnmp", "/staff/login");
?>

<main>
    Manage Meal Plans
</main>

<?php require_once "../../includes/footer.php"; ?>