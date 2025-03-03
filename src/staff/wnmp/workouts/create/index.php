<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Workout.php";
require_once "../../../../db/models/Exercise.php";

$workout = new Workout();
if (isset($_SESSION['workout'])) {
    $workout = unserialize($_SESSION['workout']);
} else {
    $workout->fill([]);
    $_SESSION['workout'] = serialize($workout);
}

if (!isset($_SESSION['exerciseTitles'])){    
    $exerciseModel = new Exercise();
    $exerciseTitles = $exerciseModel->get_all_titles();
    $_SESSION['exerciseTitles'] = $exerciseTitles;
} else {
    $exerciseTitles = $_SESSION['exerciseTitles'];
}


$sidebarActive = 3;
$menuBarConfig = [
    "title" => "Create Workout",
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/workouts/index.php",
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
auth_required_guard("wnmp", "/staff/login");
?>

<main>
    <div class="base-container">
        <div class="form">
            <form action="create_workout.php" method="POST">
                <?php require_once "../../../includes/menubar.php"; ?>
            </form>
            <form action="edit_workout_details.php" method="POST">
                <div style="padding: 5px 10px;">
                    <input type="hidden" name="workout_id" value="<?= $workout->id ?>">
                    <h2><label for="edit-title">Title</label></h2>
                    <input type="text" id="edit-title" name="workout_name"
                        class="staff-input-primary staff-input-long" value="<?= $workout->name ?>"
                        placeholder="Enter workout title">
                    <h2 style="padding-top: 5px;"><label for="edit-description">Description</label></h2>
                    <textarea id="edit-description" name="workout_description"
                        class="staff-textarea-primary staff-textarea-large"
                        placeholder="Enter a workout description"><?= $workout->description ?></textarea>
                    <div>
                        <h2><label for="edit-duration">Duration</label></h2>
                        <input type="text" id="edit-duration" name="workout_duration"
                            class="staff-input-primary staff-input-long" value="<?= $workout->duration ?>">
                        <button type="submit" class="staff-btn-secondary-black edit-workout-input-update-alt">
                            Update
                        </button>
                    </div>
                </div>
            </form>
            <div style="padding: 5px 10px;">
                <h2>Exercise</h2>
                <?php foreach ($workout->exercises as $exercise): ?>
                    <?php if (!$exercise['isDeleted']): ?>
                        <form action="edit_current_exercise.php" method="POST" class="edit-workout-row">
                            <input type="hidden" name="exercise_id" value="<?= $exercise['id'] ?>">
                            <input type="hidden" name="exercise_edit_id" value="<?= $exercise['edit_id'] ?>">
                            <select name="exercise_title" class="staff-input-primary staff-input-long">
                                <?php foreach ($exerciseTitles as $title): ?>
                                    <option value="<?= $title ?>" <?= $title == $exercise['title'] ? 'selected' : '' ?>>
                                        <?= $title ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="edit-workout-input-reps-sets">
                                <label for="exercise_sets">Sets</label>
                                <input type="text" name="exercise_sets"
                                    value="<?= $exercise['sets'] ?>" class="staff-input-primary staff-input-short">
                            </div>
                            <div class="edit-workout-input-reps-sets">
                                <label for="exercise_reps">Reps</label>
                                <input type="text" name="exercise_reps"
                                    value="<?= $exercise['reps'] ?>" class="staff-input-primary staff-input-short">
                            </div>
                            <button type="submit" class="staff-btn-outline edit-workout-input-update">
                                Update
                            </button>
                            <button type="submit" class="staff-btn-outline edit-workout-input-delete"
                                formaction="delete_current_exercise.php">
                                Delete
                            </button>
                        </form>
                    <?php endif; ?>
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