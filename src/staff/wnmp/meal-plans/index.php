<?php

require_once "../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");

require_once "../../../db/models/MealPlan.php";
require_once "../../../db/models/MealPlanRequest.php";
require_once "../../../alerts/functions.php";


$mealPlanRequestModel = new MealPlanRequest();
try {
    $hasUnreviewedRequests = $mealPlanRequestModel->has_unreviewed_requests();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to access meal plan requests: " . $e->getMessage();
}

$pageTitle = "Manage Meal Plans";
$sidebarActive = 5;
$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => true,
    "options" => [
        [
            "title" => "Meal Plan Requests", 
            "href" => "/staff/wnmp/meal-plans/requests/index.php?filter=1",
            "type" => "primary",
            "setAttentionDot" => $hasUnreviewedRequests
        ],
        ["title" => "Create Meal Plan", "href" => "/staff/wnmp/meal-plans/create/index.php", "type" => "secondary"]
    ]
];


$mealPlans = [];
$mealPlanModel = new MealPlan();
try {
    $mealPlans = $mealPlanModel->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch meal plans: " . $e->getMessage(), "/staff/wnmp");
    exit;
}

$infoCardConfig = [
    "showImage" => false,
    "showExtend" => true,
    "extendTo" => "/staff/wnmp/meal-plans/view/index.php",
    "cards" => $mealPlans,
    "showCreatedAt" => false
];

require_once "../pageconfig.php";

$pageConfig['styles'][] = "./meal-plans.css";

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