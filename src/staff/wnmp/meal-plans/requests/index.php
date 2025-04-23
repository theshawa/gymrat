<?php
require_once "../../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");

$setFilter = $_GET['filter'] ?? 0;

$pageTitle = "Meal Plan Requests";
$sidebarActive = 5;

require_once "../../../../db/models/MealPlanRequest.php";
require_once "../../../../alerts/functions.php";

$mealPlanRequests = [];
$mealPlanRequestModel = new MealPlanRequest();
try {
    $mealPlanRequests = $mealPlanRequestModel->get_all(-1, $setFilter) ?? [];
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch meal plan requests: " . $e->getMessage(), "/staff/wnmp");
    exit;
}

$pending_requests = null;
$mealPlanRequestModel = new MealPlanRequest();
try {
    $pending_requests = $mealPlanRequestModel->has_unreviewed_requests();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to access notification updates: " . $e->getMessage();
}


$menuBarConfig = [
    "title" => $pageTitle,
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/meal-plans/index.php",
    "useLink" => true,
    "options" => [
        ($setFilter == 1) ?
        ["title" => "Show All Requests", "href" => "/staff/wnmp/meal-plans/requests/index.php", "type" => "primary"] :
        [
            "title" => "Show Pending Requests",
            "href" => "/staff/wnmp/meal-plans/requests/index.php?filter=1",
            "type" => "primary",
            "setAttentionDot" => $pending_requests
        ],
    ]
];

$infoCardConfig = [
    "showImage" => false,
    "useListView" => true,
    "gridColumns" => 1,
    "showExtend" => true,
    "extendTo" => "/staff/wnmp/meal-plans/requests/view/index.php",
    "cards" => $mealPlanRequests,
    "showCreatedAt" => true,
    "defaultName" => "Meal Plan Request",
];

require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../meal-plans.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <div>
            <?php require_once "../../../includes/infocard.php"; ?>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>