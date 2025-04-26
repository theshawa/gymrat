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
require_once "../../../../../db/models/Exercise.php";

$exerciseModel = new Exercise();
$workoutRequest = new WorkoutRequest();
try {
    $trainerModel = new Trainer();
    $workoutRequest->get_by_id($id);
    $workoutRequest->trainer = $trainerModel->get_username_by_id($workoutRequest->trainerId);
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

<style>
    .request-details-panel {
        background-color:solid var(--color-zinc-100);
        color: black;
        padding: 24px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .request-details-panel h2 {
        font-size: 22px;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--color-zinc-800);
        color: black;
    }

    .request-details-row {
        display: flex;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .request-details-label {
        width: 120px;
        font-weight: 600;
        flex-shrink: 0;
    }

    .request-details-value {
        flex-grow: 1;
    }

    .confirmation-section {
        margin-top: 40px;
    }

    .confirmation-section p {
        margin-bottom: 8px;
    }
</style>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../../includes/menubar.php"; ?>
        <div class="staff-base-sub-container-alt">
            <div class="request-details-panel">
                <h2>Workout Request Details</h2>

                <div class="request-details-row">
                    <div class="request-details-label">Description:</div>
                    <div class="request-details-value">
                        <?php
                        $desc = $workoutRequest->description;
                        $parsed = json_decode($desc, true);
                        if ($parsed && isset($parsed['name'])) {
                            echo "<strong>Name:</strong> " . htmlspecialchars($parsed['name']) . "<br>";
                            echo "<strong>Type:</strong> " . ucfirst($parsed['type']) . "<br>";
                            echo "<strong>Duration:</strong> " . htmlspecialchars($parsed['duration']) . " days<br>";
                            echo "<strong>Priority:</strong> " . ucfirst($parsed['priority']) . "<br><br>";

                            echo nl2br(htmlspecialchars($parsed['description'])) . "<br><br>";

                            echo "<strong>Recommended Exercises:</strong><br>";

                            echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width:100%; background: #2a2a2a; color: white;'>";
                            echo "<thead><tr><th>Exercise</th><th>Sets</th><th>Reps</th><th>Day</th></tr></thead><tbody>";
                            foreach ($parsed['exercises'] as $exercise) {
                                try {
                                    $exerciseTitle = $exerciseModel->get_title_by_id($exercise['id']);
                                    echo "<tr>
                        <td>" . htmlspecialchars($exerciseTitle) . "</td>
                        <td>" . htmlspecialchars($exercise['s']) . "</td>
                        <td>" . htmlspecialchars($exercise['r']) . "</td>
                        <td>Day " . htmlspecialchars($exercise['d']) . "</td>
                    </tr>";
                                } catch (Exception $e) {
                                    echo "<tr><td colspan='4'>Unknown Exercise ID {$exercise['id']}</td></tr>";
                                }
                            }
                            echo "</tbody></table>";
                        } else {
                            echo nl2br(htmlspecialchars($desc)); // fallback if not a JSON structure
                        }
                        ?>
                    </div>
                </div>

                <?php if ($workoutRequest->reviewed == 0): ?>
                    <div class="confirmation-section">
                        <h2>Acknowledge Request</h2>
                        <p>Select a relevant workout to confirm:</p>
                        <form action="confirm_request.php" method="POST"
                            style="display: flex; flex-direction: column; gap: 20px;">
                            <input type="hidden" name="id" value="<?= $workoutRequest->id ?>">
                            <div style="display: flex; flex-direction: row; gap: 20px; align-items: center;">
                                <select name="confirmation_workout" class="staff-input-primary staff-input-long">
                                    <?php foreach ($workoutTitles as $title): ?>
                                        <option value="<?= $title ?>"><?= $title ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="staff-button secondary"
                                    style="min-height: 38px; min-width:120px; margin-top: 5px;">Confirm</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../../../includes/footer.php"; ?>
