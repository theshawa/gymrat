<?php
$id = $_GET['id'] ?? null;

$workout = [
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

$sidebarActive = 3;
$menuBarConfig = [
    "title" => "Edit " . $workout['title'],
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/workouts/view/index.php?id=<?= $id ?>",
    "useButton" => true,
    "options" => [
        [ "title" => "Save Changes", "buttonType" => "submit", "type" => "secondary" ],
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
            <form action="edit_workout.php" method="post" class="form">
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
                                <button type="button" class="edit-workout-input-delete" onclick="deleteExercise(<?= $exercise['id'] ?>)">Delete</button>
                            </div>
                        <?php endforeach; ?>
                        <div>
                            <button type="button" class="edit-workout-add-exercise" onclick="addExercise()">+ Add Exercise</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

<?php include_once "../../../includes/footer.php"; ?>