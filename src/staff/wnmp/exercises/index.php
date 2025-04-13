<?php

$pageTitle = "Exercises";
$sidebarActive = 2;
$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => true,
    "options" => [
        ["title" => "Create Exercise", "href" => "/staff/wnmp/exercises/create/index.php", "type" => "secondary"]
    ]
];

require_once "../../../db/models/Exercise.php";

require_once "../../../alerts/functions.php";

$exercise = [];
$exerciseModel = new Exercise();
try {
    $exercise = $exerciseModel->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch exercises: " . $e->getMessage(), "/staff/wnmp");
}

$infoCardConfig = [
    "showImage" => true,
    "showExtend" => true,
    "extendTo" => "/staff/wnmp/exercises/view/index.php",
    "cards" => $exercise,
    "showCreatedAt" => false
];


require_once "../pageconfig.php";

$pageConfig['styles'][] = "./exercises.css";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";

require_once "../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
        <div>
            <?php require_once "../../includes/infocard.php"; ?>
        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?><?php
