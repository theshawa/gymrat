<?php

$pageTitle = "Manage Trainers";
$sidebarActive = 4;
$menuBarConfig = [
    "title" => $pageTitle,
];


require_once "../pageconfig.php";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";

require_once "../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
        
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>