<?php
$id = $_GET['id'] ?? null;

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
}
$workout = &$_SESSION['workout'];

$sidebarActive = 3;
$menuBarConfig = [
    "title" => "Edit " . $workout['title'],
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/workouts/view/index.php?id=<?= $id ?>",
    "useButton" => true,
    "options" => [
        [ "title" => "Save Changes", "buttonType" => "submit", "submitAction" => "saveExercise", "type" => "secondary" ],
        [ "title" => "Revert Changes", "buttonFunction" => "doRevertChanges", "type" => "destructive" ]
    ]
];


include_once "../../pageconfig.php";

$pageConfig['styles'][] = "../workouts.css";

include_once "../../../includes/header.php";
include_once "../../../includes/sidebar.php";
?>

    <main>
        <div class="base-container">
            <form action="edit_workout.php" method="POST" class="form">
                <?php include_once "../../../includes/menubar.php"; ?>
                <div style="padding: 0px 10px;">
                    <div>
                        <h2><label for="editDescription">Description</label></h2>
                        <textarea id="editDescription" name="description" class="edit-workout-textarea"
                                  placeholder="Enter a workout description"><?= $workout['description'] ?></textarea>
                    </div>
                    <div>
                        <h2>Exercise</h2>
                        <?php foreach ($workout["exercise"] as $exercise): ?>
                            <div class="edit-workout-row">
                                <input type="hidden" name="exercise-id" value="<?= $exercise['id'] ?>">
                                <input type="text" name="<?= $exercise['id'] ?>-title" class="edit-workout-input-title"
                                       value="<?= $exercise['title'] ?>">
                                <div class="edit-workout-input-reps-sets">
                                    <label for="<?= $exercise['id'] ?>-reps">Reps</label>
                                    <input type="text" name="exercise-<?= $exercise['id'] ?>-reps" value="<?= $exercise['reps'] ?>">
                                </div>
                                <div class="edit-workout-input-reps-sets">
                                    <label for="<?= $exercise['id'] ?>-sets">Sets</label>
                                    <input type="text" name="exercise-<?= $exercise['id'] ?>-sets" value="<?= $exercise['sets'] ?>">
                                </div>
                                <button type="submit" class="edit-workout-input-delete" name="deleteExercise">Delete</button>
                            </div>
                        <?php endforeach; ?>
                        <div>
                            <button type="submit" class="edit-workout-add-exercise" name="addExercise">+ Add Exercise</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

<?php include_once "../../../includes/footer.php"; ?>