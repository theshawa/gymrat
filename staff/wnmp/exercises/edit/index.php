<?php

session_start();

$id = $_GET['id'] ?? null;

$sidebarActive = 2;

require_once "../../../../db/models/Exercise.php";
require_once "../../../../alerts/functions.php";

if (!isset($_SESSION['exercise'])) {
    $exercise = new Exercise();
    try {
        $exercise->get_by_id($id);
//        $_SESSION['exercise'] = $exercise;
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to fetch exercise: " . $e->getMessage(), "/staff/wnmp");
    }
}
//$exercise = &$_SESSION['exercise'];

$menuBarConfig = [
    "title" => "Edit " . $exercise->name,
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/workouts/view/index.php?id=$id",
    "useButton" => true,
    "options" => [
        ["title" => "Save Changes", "buttonType" => "submit", "type" => "secondary"],
        ["title" => "Revert Changes", "buttonType" => "submit", "formAction" => "revert_exercise.php", "type" => "destructive"]
    ]
];
//$alertConfig = [
//    "status" => $_GET['status'] ?? null,
//    "error" => $_GET['err'] ?? null,
//    "message" => $_GET['msg'] ?? null
//];


require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../exercises.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <div class="form">
            <form action="edit_exercise.php" method="POST">
                <?php require_once "../../../includes/menubar.php"; ?>
                <div style="padding: 5px 10px;">
<!--                    --><?php //require_once "../../../includes/alert.php"; ?>
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-title">Title</label></h2>
                        <input type="text" id="edit-title" name="exercise_name"
                               class="staff-input-primary staff-input-long" value="<?= $exercise->name ?>">
                    </div>
                    <div style="margin: 10px 0px">
                        <h2><label for="edit-description">Description</label></h2>
                        <textarea id="edit-description" name="exercise_description"
                                  class="staff-textarea-primary staff-textarea-large"
                                  placeholder="Enter a exercise description"><?= $exercise->description ?></textarea>
                    </div>
                    <div style="margin: 10px 0px">
                        <h2><label for="edit-video_link">Video Link</label></h2>
                        <textarea id="edit-video_link" name="exercise_video_link"
                                  class="staff-textarea-primary staff-textarea-large"
                                  placeholder="Enter a exercise video link"><?= $exercise->video_link ?></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>


