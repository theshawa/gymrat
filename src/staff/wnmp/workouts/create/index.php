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
        ["title" => "Save Changes", "buttonType" => "submit", 
            "buttonName" => "action", "buttonValue" => "create", "type" => "secondary"],
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
                    </div>
                </div>
                <div style="padding: 5px 10px;">
                    <h2>Exercise</h2>
                    <?php foreach ($workout->exercises as $exercise): ?>
                        <div class="edit-workout-row">
                            <?php if (!$exercise['isDeleted']): ?>
                                <select name="exercise_title_<?= $exercise['edit_id'] ?>" class="staff-input-primary staff-input-long">
                                    <?php foreach ($exerciseTitles as $title): ?>
                                        <option value="<?= $title ?>" <?= $title == $exercise['title'] ? 'selected' : '' ?>>
                                            <?= $title ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="edit-workout-input-reps-sets">
                                    <label for="exercise_day_<?= $exercise['edit_id'] ?>">Day</label>
                                    <input type="text" name="exercise_day_<?= $exercise['edit_id'] ?>" pattern="\d+"
                                        value="<?= $exercise['day'] ?>" class="staff-input-primary staff-input-short">
                                </div>
                                <div class="edit-workout-input-reps-sets">
                                    <label for="exercise_sets_<?= $exercise['edit_id'] ?>">Sets</label>
                                    <input type="text" name="exercise_sets_<?= $exercise['edit_id'] ?>" pattern="\d+"
                                        value="<?= $exercise['sets'] ?>" class="staff-input-primary staff-input-short">
                                </div>
                                <div class="edit-workout-input-reps-sets">
                                    <label for="exercise_reps_<?= $exercise['edit_id'] ?>">Reps</label>
                                    <input type="text" name="exercise_reps_<?= $exercise['edit_id'] ?>" pattern="\d+"
                                        value="<?= $exercise['reps'] ?>" class="staff-input-primary staff-input-short">
                                </div>
                                <button type="submit" class="staff-btn-outline edit-workout-input-delete"
                                    name="delete_exercise" value="<?= $exercise['edit_id'] ?>">
                                    Delete
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" name="action" value="add" class="staff-btn-secondary-black edit-workout-add-exercise">
                        + Add Exercise
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>


<?php require_once "../../../includes/footer.php"; ?>