<?php
session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Workout.php";
require_once "../../../../db/models/Exercise.php";

$id = htmlspecialchars($_GET['id'] ?? null);

$workout = new Workout();
if (!isset($_SESSION['workout'])) {
    $exerciseModel = new Exercise();
    try {
        $workout->get_by_id($id);
        $workout->exercises = $exerciseModel->addExerciseTitles($workout->exercises);
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to fetch workout: " . $e->getMessage(), "/staff/wnmp/workouts");
    }
    $_SESSION['workout'] = serialize($workout);
} else {
    $workout = unserialize($_SESSION['workout']);
}


$sidebarActive = 3;
$menuBarConfig = [
    "title" => "Edit " . $workout->name,
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/workouts/view/index.php?id=$id",
    "useButton" => true,
    "options" => [
        ["title" => "Save Changes", "buttonType" => "submit", "type" => "secondary"],
        ["title" => "Revert Changes", "buttonType" => "submit", "formAction" => "revert_exercise.php", "type" => "destructive"]
    ]
];

require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../workouts.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";

require_once "../../../../auth-guards.php";
auth_required_guard_with_role("wnmp", "/staff/login");
?>

<main>
<!--    --><?php //var_dump($workout->exercises) ?>
    <div class="base-container">
        <div class="form">
            <form action="edit_workout.php" method="POST">
                <?php require_once "../../../includes/menubar.php"; ?>
                <div style="padding: 5px 10px;">
                    <h2><label for="edit-title">Title</label></h2>
                    <input type="text" id="edit-title" name="exercise_title"
                        class="staff-input-primary staff-input-long" value="<?= $workout->name ?>">
                    <h2><label for="edit-description">Description</label></h2>
                    <textarea id="edit-description" name="workout_desc"
                        class="staff-textarea-primary staff-textarea-large"
                        placeholder="Enter a workout description"><?= $workout->description ?></textarea>
                </div>
            </form>
            <div style="padding: 5px 10px;">
                <h2>Exercise</h2>
                <?php foreach ($workout->exercises as $exercise): ?>
                    <form action="edit_current_exercise.php" method="POST" class="edit-workout-row">
                        <input type="hidden" name="exercise_id" value="<?= $exercise['exercise_id'] ?>">
                        <input type="text" name="exercise_title" class="staff-input-primary staff-input-long"
                            value="<?= $exercise['title'] ?>">
                        <div class="edit-workout-input-reps-sets">
                            <label for="exercise_reps">Reps</label>
                            <input type="text" name="exercise_reps"
                                value="<?= $exercise['reps'] ?>" class="staff-input-primary staff-input-short">
                        </div>
                        <div class="edit-workout-input-reps-sets">
                            <label for="exercise_sets">Sets</label>
                            <input type="text" name="exercise_sets"
                                value="<?= $exercise['sets'] ?>" class="staff-input-primary staff-input-short">
                        </div>
                        <button type="submit" class="staff-btn-outline edit-workout-input-update">
                            Update
                        </button>
                        <button type="submit" class="staff-btn-outline edit-workout-input-delete"
                            formaction="delete_current_exercise.php">
                            Delete
                        </button>
                    </form>
                <?php endforeach; ?>
                <form action="add_exercise.php" method="POST">
                    <button type="submit" class="staff-btn-secondary-black edit-workout-add-exercise">
                        + Add Exercise
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>

