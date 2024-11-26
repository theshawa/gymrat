<?php

$pageTitle = "Manage Meals";
$sidebarActive = 4;

require_once "../pageconfig.php";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";

require_once "../../../auth-guards.php";
auth_required_guard_with_role("wnmp", "/staff/login");
?>

<main>
    Manage Meals
</main>

<?php require_once "../../includes/footer.php"; ?>