<?php

$pageTitle = "Workouts";
$sidebarActive = 3;
$menuBarConfig = [
    "title" => $pageTitle,
    "showOptions" => true,
    "options" => [
        [ "title" => "Create Workout", "href" => "/staff/wnmp/workouts/create/index.php", "type" => "secondary" ]
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