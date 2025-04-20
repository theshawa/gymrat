<?php
require_once "../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");

$pageTitle = "Manage Trainers";
$sidebarActive = 4;

require_once "../../../db/models/Trainer.php";
require_once "../../../alerts/functions.php";

$trainerModel = new Trainer();
try {
    $trainers = $trainerModel->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch trainers: " . $e->getMessage(), "/staff/admin");
    exit;
}


$menuBarConfig = [
    "title" => $pageTitle,
];


$infoCardConfig = [
    "showImage" => true,
    "useAvatar" => true,
    "concatName" => true,
    "showExtend" => true,
    "extendTo" => "/staff/admin/trainers/view/index.php",
    "cards" => $trainers,
    "showCreatedAt" => false,
    "gridColumns" => 1
];



require_once "../pageconfig.php";
require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
        <div>
            <?php require_once "../../includes/infocard.php"; ?>
        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>