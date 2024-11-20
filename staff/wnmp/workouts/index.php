<?php

$pageTitle = "Workouts";
$sidebarActive = 3;
$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => true,
    "options" => [
        ["title" => "Create Workout", "href" => "/staff/wnmp/workouts/create/index.php", "type" => "secondary"]
    ]
];
$infoCardConfig = [
    "showImage" => true,
    "showExtend" => true,
    "extendTo" => "/staff/wnmp/workouts/view/index.php",
    "cards" => [
        [
            "id" => 001,
            "title" => "Strength Training",
            "description" => "Squats, Deadlifts, Bench Press, Pull-Ups, Overhead Press, Lunges, Quads, Dumbbell Rows",
            "image" => null
        ],
        [
            "id" => 002,
            "title" => "Cardio",
            "description" => "Running, Cycling, Swimming, Rowing, Jump Rope, Stair Climbing, Hiking, Elliptical",
            "image" => null
        ],
        [
            "id" => 003,
            "title" => "Flexibility",
            "description" => "Stretching, Yoga, Pilates, Tai Chi, Foam Rolling, Dynamic Stretching, Static Stretching",
            "image" => null
        ]
    ],
    "isCardInList" => true
];


require_once "../pageconfig.php";

$pageConfig['styles'][] = "./workouts.css";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
        <div>
            <?php require_once "../../includes/infocard.php"; ?>
        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>