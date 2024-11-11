<?php

$pageTitle = "Exercises";
$sidebarActive = 2;
$menuBarConfig = [
    "title" => $pageTitle,
    "useLink" => true,
    "options" => [
        [ "title" => "Create Exercise", "href" => "/staff/wnmp/exercises/create/index.php", "type" => "secondary" ]
    ]
];
$infoCardConfig = [
    "showImage" => true,
    "showExtend" => true,
    "extendTo" => "/staff/wnmp/exercises/view/index.php",
    "cards" => [
        [
            "id" => 001,
            "title" => "Squats",
            "description" => "A compound lower-body exercise targeting quads, glutes, and hamstrings. Builds strength and stability.",
            "img" => null
        ],
        [
            "id" => 002,
            "title" => "Deadlifts",
            "description" => "A full-body exercise focusing on hamstrings, glutes, and lower back. Great for building strength.",
            "img" => null
        ],
        [
            "id" => 003,
            "title" => "Bench Press",
            "description" => "An upper-body exercise targeting chest, shoulders, and triceps. Ideal for developing pushing strength.",
            "img" => null
        ],
        [
            "id" => 004,
            "title" => "Pull-Ups",
            "description" => "A bodyweight exercise that strengthens the back and biceps, improving upper-body pulling power.",
            "img" => null
        ],
        [
            "id" => 005,
            "title" => "Overhead Press",
            "description" => "Focuses on shoulders and triceps. Great for building upper-body pressing strength.",
            "img" => null
        ],
        [
            "id" => 006,
            "title" => "Lunges",
            "description" => "A unilateral leg exercise targeting quads, glutes, and hamstrings, improving balance and coordination.",
            "img" => null
        ],
        [
            "id" => 007,
            "title" => "Dumbbell Rows",
            "description" => "Targets the lats, traps, and biceps, improving upper back strength and posture.",
            "img" => null
        ],
    ]
];


include_once "../pageconfig.php";

$pageConfig['styles'][] = "./exercises.css";

include_once "../../includes/header.php";
include_once "../../includes/sidebar.php";
?>

    <main>
        <div class="staff-base-container">
            <?php include_once "../../includes/menubar.php"; ?>
            <div>
                <?php include_once "../../includes/infocard.php"; ?>
            </div>
        </div>
    </main>

<?php include_once "../../includes/footer.php"; ?><?php
