<?php

require_once "../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");


$pageTitle = "Workouts";
$sidebarActive = 3;

require_once "../../../db/models/WorkoutRequest.php";
require_once "../../../db/models/Workout.php";
require_once "../../../alerts/functions.php";


$workoutRequestModel = new WorkoutRequest();
$hasUnreviewedRequests = $workoutRequestModel->has_unreviewed_requests();


$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => true,
    "options" => [
        [
            "title" => "Workout Requests", 
            "href" => "/staff/wnmp/workouts/requests/index.php?filter=1",
            "type" => "primary",
            "setAlert" => $hasUnreviewedRequests
        ],
        ["title" => "Create Workout", "href" => "/staff/wnmp/workouts/create/index.php", "type" => "secondary"]
    ]
];


$workout = [];
$workoutModel = new Workout();
try {
    $workout = $workoutModel->get_all();
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch workouts: " . $e->getMessage(), "/staff/wnmp");
}

$infoCardConfig = [
    "showImage" => true,
    "showExtend" => true,
    "extendTo" => "/staff/wnmp/workouts/view/index.php",
    "cards" => $workout,
    "showCreatedAt" => false
];


require_once "../pageconfig.php";

require_once "../../../alerts/functions.php";

$pageConfig['styles'][] = "./workouts.css";

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