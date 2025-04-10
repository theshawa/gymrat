<?php

$id = htmlspecialchars($_GET['id'] ?? null);

require_once "../../../../alerts/functions.php";

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
    "title" => 'Delete ' . $workout['title'],
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/workouts/view/index.php?id=<?= $id ?>"
];


require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../workouts.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <form action="delete_workout.php" method="post" class="form">
            <div class="staff-record-delete-div">
                <h2>Are you sure you want to delete "<?= $workout["title"] ?>"?</h2>
                <p>This action cannot be undone.</p>
                <button type="submit">Delete</button>
            </div>
        </form>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>