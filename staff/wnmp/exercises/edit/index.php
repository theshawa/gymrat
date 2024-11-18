<?php

session_start();

$id = $_GET['id'] ?? null;
$_SESSION['exercise_id'] = $id;

$sidebarActive = 2;

require_once "../../../../db/models/Exercise.php";
require_once "../../../../alerts/functions.php";

$exercise = new Exercise();
if (!isset($_SESSION['exercise'])) {
    try {
        $exercise->get_by_id($id);
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to fetch exercise: " . $e->getMessage(), "/staff/wnmp");
    }
} else {
    $exercise = &$_SESSION['exercise'];
}

$menuBarConfig = [
    "title" => "Edit " . $exercise->name,
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/exercises/view/index.php?id=$id",
    "useButton" => true,
    "options" => [
        ["title" => "Save Changes", "buttonType" => "submit", "type" => "secondary"],
        ["title" => "Revert Changes", "buttonType" => "submit", "formAction" => "revert_exercise.php", "type" => "destructive"]
    ]
];


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
                    <input type="hidden" name="exercise_id" value="<?= $exercise->id?>">
                    <div style="margin-bottom: 10px">
                        <h2><label for="edit-title">Title</label></h2>
                        <input type="text" id="edit-title" name="exercise_name"
                               class="staff-input-primary staff-input-long" value="<?= $exercise->name ?>">
                    </div>
                    <div style="margin: 10px 0px">
                        <h2><label for="edit-description">Description</label></h2>
                        <textarea id="edit-description" name="exercise_description"
                                  class="staff-textarea-primary staff-textarea-large"
                                  placeholder="Enter a exercise description"><?= $exercise->description ?>
                        </textarea>
                    </div>
                    <div style="margin: 10px 0px">
                        <h2><label for="edit-video_link">Video Link</label></h2>
                        <textarea id="edit-video_link" name="exercise_video_link"
                                  class="staff-textarea-primary staff-textarea-large"
                                  placeholder="Enter a exercise video link"><?= $exercise->video_link ?>
                        </textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>


