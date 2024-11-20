<?php

$id = $_GET['id'] ?? null;

session_start();

$sidebarActive = 2;

require_once "../../../../alerts/functions.php";

$menuBarConfig = [
    "title" => "Delete Exercise",
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/exercises/view/index.php?id=$id",
];

require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../exercises.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <div class="form">
            <form action="delete_exercise.php" method="POST">
                <?php require_once "../../../includes/menubar.php"; ?>
                <div class="staff-record-delete-div">
                    <h2>Are you sure you want to delete this exercise?</h2>
                    <p>This action cannot be undone.</p>
                    <button type="submit">Delete</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>

