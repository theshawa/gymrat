<?php
// session_start();

require_once "../../../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");

$id = $_GET['id'] ?? null;
$sidebarActive = 3;

require_once "../../../../../alerts/functions.php";
require_once "../../../../../db/models/Trainer.php";
require_once "../../../../../db/models/WorkoutRequest.php";
require_once "../../../../../db/models/Workout.php";

$workoutRequest = new WorkoutRequest();
try {
    $trainerModel = new Trainer();
    $workoutRequest->get_by_id($id);
    $workoutRequest->trainer = $trainerModel->get_username_by_id($workoutRequest->trainerId);
    // $_SESSION['workout_request'] = serialize($workoutRequest);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch workout request: " . $e->getMessage(), "/staff/wnmp/workouts/requests");
}


$workoutTitles = [];
try {
    $workoutModel = new Workout();
    $workoutTitles = $workoutModel->get_all_titles();
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to fetch workout titles: " . $e->getMessage();
}


$menuBarConfig = [
    "title" => "Workout Request #" . $workoutRequest->id,
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/workouts/requests/index.php?filter=1",
];

require_once "../../../pageconfig.php";

require_once "../../../../includes/header.php";
require_once "../../../../includes/sidebar.php";
?>

<main>
<div class="staff-base-container">
        <?php require_once "../../../../includes/menubar.php"; ?>
        <div class="staff-base-sub-container-alt">
            <div>
                <h2 style="margin-bottom: 10px;">
                    Description
                </h2>
                <p><?= $workoutRequest->description ?></p>
                <?php 
                $descriptionData = json_decode($workoutRequest->description, true); 
                if (json_last_error() === JSON_ERROR_NONE && $descriptionData): ?>
                    <p><strong>Name:</strong> <?= htmlspecialchars($descriptionData['name']) ?></p>
                    <p><strong>Type:</strong> <?= htmlspecialchars($descriptionData['type']) ?></p>
                    <p><strong>Duration:</strong> <?= htmlspecialchars($descriptionData['duration']) ?> days</p>
                    <p><strong>Priority:</strong> <?= htmlspecialchars($descriptionData['priority']) ?></p>
                    <p><strong>Description:</strong> <?= htmlspecialchars($descriptionData['description']) ?></p>
                    <h3>Recommended Exercises:</h3>
                    <ul>
                        <?php foreach ($descriptionData['exercises'] as $exercise): ?>
                            <li>
                                <?= "Exercise ID: {$exercise['id']} - Day {$exercise['day']}: {$exercise['sets']} sets of {$exercise['reps']} reps" ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Invalid description format or JSON parsing error.</p>
                    <p>Error: <?= json_last_error_msg() ?></p>
                <?php endif; ?>
                <div style="display: flex; flex-direction: row; margin-top: 20px; align-items: center; ">
                    <h2>
                        Trainer : 
                    </h2>
                    <p>&emsp;<?= $workoutRequest->trainer ?></p>
                </div>
            </div>
            <?php if ($workoutRequest->reviewed == 0): ?>
            <form action="confirm_request.php" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                <input type="hidden" name="id" value="<?= $workoutRequest->id ?>">
                <h2>
                    Acknowledge Request
                </h2>
                <p>Select relevant workout to confirm the successful creation of the requested workout</p>
                <div style = "display: flex; flex-direction: row; gap: 20px; align-items: center;">
                <select name="confirmation_workout" class="staff-input-primary staff-input-long">
                    <?php foreach ($workoutTitles as $title): ?>
                        <option value="<?= $title ?>">
                            <?= $title ?>
                        </option>
                    <?php endforeach; ?>
                </select>    
                <button type="submit" class="staff-button secondary" style="min-height: 38px; min-width:120px; margin-top: 5px;">Confirm</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once "../../../../includes/footer.php"; ?>
