<?php

require_once "../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");

$pageTitle = "Manage Meals";
$sidebarActive = 4;
$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => true,
    "options" => [
        ["title" => "Create Meal", "href" => "/staff/wnmp/meals/create/index.php", "type" => "secondary"]
    ]
];

require_once "../../../db/models/Meal.php";
require_once "../../../alerts/functions.php";

$meals = [];
$mealModel = new Meal();
try {
    $meals = $mealModel->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch meals: " . $e->getMessage(), "/staff/wnmp");
}

$infoCardConfig = [
    "showImage" => true,
    "showExtend" => true,
    "extendTo" => "/staff/wnmp/meals/view/index.php",
    "cards" => $meals,
    "showCreatedAt" => false
];

require_once "../pageconfig.php";

$pageConfig['styles'][] = "./meals.css";

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