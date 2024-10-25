<?php

$pageTitle = "Workouts";
$sidebarActive = 3;
$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => true,
    "options" => [
        [ "title" => "Create Workout", "href" => "/staff/wnmp/workouts/create/index.php", "type" => "secondary" ]
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
            "img" => null
        ],
        [
            "id" => 002,
            "title" => "Cardio",
            "description" => "Running, Cycling, Swimming, Rowing, Jump Rope, Stair Climbing, Hiking, Elliptical",
            "img" => null
        ],
        [
            "id" => 003,
            "title" => "Flexibility",
            "description" => "Stretching, Yoga, Pilates, Tai Chi, Foam Rolling, Dynamic Stretching, Static Stretching",
            "img" => null
        ]
    ]
];


include_once "../pageconfig.php";

$pageConfig['styles'][] = "./workouts.css";

include_once "../../includes/header.php";
include_once "../../includes/sidebar.php";
?>

<main>
    <div class="base-container">
        <?php include_once "../../includes/menubar.php"; ?>
        <div>
            <?php include_once "../../includes/infocard.php"; ?>
        </div>
    </div>
</main>

<?php include_once "../../includes/footer.php"; ?>