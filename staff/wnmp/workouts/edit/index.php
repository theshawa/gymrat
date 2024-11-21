<?php

// check for session and destroy
session_start();

$id = htmlspecialchars($_GET['id'] ?? null);

if (!isset($_SESSION['workout'])) {
    // REPLACE THIS WITH DATABASE QUERY
    $_SESSION['workout'] = [
        "id" => 001,
        "title" => "Strength Training",
        "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
        "exercise" => [
            [
                "id" => 001,
                "title" => "Squats",
                "sets" => 3,
                "reps" => 10
            ],
            [
                "id" => 002,
                "title" => "Deadlifts",
                "sets" => 3,
                "reps" => 10
            ],
            [
                "id" => 003,
                "title" => "Bench Press",
                "sets" => 3,
                "reps" => 10
            ],
            [
                "id" => 004,
                "title" => "Pull-Ups",
                "sets" => 3,
                "reps" => 10
            ],
            [
                "id" => 005,
                "title" => "Overhead Press",
                "sets" => 3,
                "reps" => 10
            ],
            [
                "id" => 006,
                "title" => "Quads",
                "sets" => 3,
                "reps" => 10
            ],
            [
                "id" => 007,
                "title" => "Dumbbell Rows",
                "sets" => 3,
                "reps" => 10
            ]
        ],
        "img" => null
    ];
    $_SESSION['workout_id'] = $_SESSION['workout']['id'];
}

$workout = &$_SESSION['workout'];
$sidebarActive = 3;
$menuBarConfig = [
    "title" => "Edit " . $workout['title'],
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/workouts/view/index.php?id=$id",
    "useButton" => true,
    "options" => [
        ["title" => "Save Changes", "buttonType" => "submit", "type" => "secondary"],
        ["title" => "Revert Changes", "buttonType" => "submit", "formAction" => "revert_exercise.php", "type" => "destructive"]
    ]
];
$alertConfig = [
    "status" => htmlspecialchars(
        $_GET['status'] ?? null
    ),
    "error" => htmlspecialchars(
        $_GET['err'] ?? null
    ),
    "message" => htmlspecialchars(
        $_GET['msg'] ?? null
    )
];


require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../workouts.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="base-container">
        <div class="form">
            <form action="edit_workout.php" method="POST">
                <?php require_once "../../../includes/menubar.php"; ?>
                <div style="padding: 5px 10px;">
                    <?php require_once "../../../includes/alert.php"; ?>
                    <h2><label for="edit-title">Title</label></h2>
                    <input type="text" id="edit-title" name="exercise_title"
                        class="staff-input-primary staff-input-long" value="<?= $workout['title'] ?>">
                    <h2><label for="edit-description">Description</label></h2>
                    <textarea id="edit-description" name="workout_desc"
                        class="staff-textarea-primary edit-workout-textarea"
                        placeholder="Enter a workout description"><?= $workout['description'] ?>
                        </textarea>
                </div>
            </form>
            <div style="padding: 5px 10px;">
                <h2>Exercise</h2>
                <?php foreach ($workout["exercise"] as $exercise): ?>
                    <form action="edit_current_exercise.php" method="POST" class="edit-workout-row">
                        <input type="hidden" name="exercise_id" value="<?= $exercise['id'] ?>">
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