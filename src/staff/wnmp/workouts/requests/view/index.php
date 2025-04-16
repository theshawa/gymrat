<?php
$id = $_GET['id'] ?? null;

$sidebarActive = 3;

require_once "../../../../../db/models/WorkoutRequest.php";
require_once "../../../../../alerts/functions.php";

$workoutRequest = new WorkoutRequest();
try {
    $workoutRequest->get_by_id($id);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch workout request: " . $e->getMessage(), "/staff/wnmp/workouts/requests");
}
$_SESSION['workoutRequest'] = $workoutRequest;

$menuBarConfig = [
    "title" => "Workout Request #" . $workoutRequest->id,
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/workouts/requests/index.php",
];

require_once "../../../pageconfig.php";

require_once "../../../../includes/header.php";
require_once "../../../../includes/sidebar.php";

require_once "../../../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");
?>

<main>
<div class="staff-base-container">
        <?php require_once "../../../../includes/menubar.php"; ?>
        <div class="staff-base-sub-container">
            <div>
                <h2 style="margin-bottom: 10px;">
                    Description
                </h2>
                <p><?= $workoutRequest->description ?></p>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../../../includes/footer.php"; ?>
