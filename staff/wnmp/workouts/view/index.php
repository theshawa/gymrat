<?php
$id = htmlspecialchars($_GET['id'] ?? null);

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
    "title" => $workout['title'],
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/workouts/index.php",
    "useLink" => true,
    "options" => [
        ["title" => "Edit Workout", "href" => "/staff/wnmp/workouts/edit/index.php?id=$id", "type" => "secondary"],
        ["title" => "Delete Workout", "href" => "/staff/wnmp/workouts/delete/index.php?id=$id", "type" => "destructive"]
    ]
];


require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../workouts.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <div class="view-workout-container">
            <div>
                <h2 style="margin-bottom: 20px;">
                    Exercises
                </h2>
                <?php foreach ($workout['exercise'] as $exercise): ?>
                    <div class="view-workout-exercise">
                        <p><?= $exercise['title'] ?></p>
                        <p class="alt"><?= $exercise['sets'] ?> x <?= $exercise['reps'] ?></p>
                    </div>
                    <hr>
                <?php endforeach; ?>
            </div>
            <div>
                <h2 style="margin-bottom: 20px;">
                    Description
                </h2>
                <p><?= $workout['description'] ?></p>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>