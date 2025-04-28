<?php

require_once "../../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");

$setFilter = $_GET['filter'] ?? 0;

$pageTitle = "Workout Requests";
$sidebarActive = 3;

require_once "../../../../db/models/WorkoutRequest.php";
require_once "../../../../alerts/functions.php";

$workoutRequests = [];
$workoutRequestModel = new WorkoutRequest();
try {
    $workoutRequests = $workoutRequestModel->get_all(-1, $setFilter);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch workout requests: " . $e->getMessage(), "/staff/wnmp");
    exit;
}

$pending_requests = null;
$workoutRequestModel = new WorkoutRequest();
try {
    $pending_requests = $workoutRequestModel->has_unreviewed_requests();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to access notification updates: " . $e->getMessage();
}

$menuBarConfig = [
    "title" => $pageTitle,
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/workouts/index.php",
    "useLink" => true,
    "options" => [
        ($setFilter == 1) ? 
        ["title" => "Show All Requests", "href" => "/staff/wnmp/workouts/requests/index.php", "type" => "primary"] :
        ["title" => "Show Pending Requests", "href" => "/staff/wnmp/workouts/requests/index.php?filter=1", 
        "type" => "primary", "setAttentionDot" => $pending_requests],
    ]
];

$infoCardConfig = [
    "showImage" => false,
    "showDescription" => false,
    "useListView" => true,
    "gridColumns" => 1,
    "showExtend" => true,
    "extendTo" => "/staff/wnmp/workouts/requests/view/index.php",
    "cards" => $workoutRequests,
    "showCreatedAt" => true,
    "defaultName" => "Workout Request",
];

require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../workouts.css";

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
