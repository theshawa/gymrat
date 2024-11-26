<?php

$pageTitle = "Manage Staff Credentials";
$sidebarActive = 3;

require_once "../pageconfig.php";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";

require_once "../../../auth-guards.php";
auth_required_guard_with_role("admin", "/staff/login");
?>

<main>
    Manage Staff Credentials
</main>

<?php require_once "../../includes/footer.php"; ?>