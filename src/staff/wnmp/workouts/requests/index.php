<?php

require_once "../../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");

$pageTitle = "Workout Requests";
$sidebarActive = 3;
$menuBarConfig = [
    "title" => $pageTitle,
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/workouts/index.php",
];

require_once "../../../../db/models/WorkoutRequest.php";

require_once "../../../../alerts/functions.php";

$workoutRequests = [];
$workoutRequestModel = new WorkoutRequest();
try {
    $workoutRequests = $workoutRequestModel->get_all(-1);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch workout requests: " . $e->getMessage(), "/staff/wnmp");
}

$infoCardConfig = [
    "showImage" => false,
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
