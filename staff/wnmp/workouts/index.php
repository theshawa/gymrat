<?php

$pageTitle = "Workouts";
$sidebarActive = 3;
$menuBarConfig = [
    "title" => $pageTitle,
    "showOptions" => true,
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/index.php",
    "options" => [
        [ "title" => "Edit Workout", "href" => "/staff/wnmp/workouts/edit/index.php", "type" => "primary" ],
        [ "title" => "Create Workout", "href" => "/staff/wnmp/workouts/create/index.php", "type" => "secondary" ],
        [ "title" => "Delete Workout", "href" => "/staff/wnmp/exercises/delete/index.php", "type" => "destructive" ]
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
        Manage Workouts
    </div>
</main>

<?php include_once "../../includes/footer.php"; ?>