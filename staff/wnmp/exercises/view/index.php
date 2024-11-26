<?php
$id = $_GET['id'] ?? null;

$sidebarActive = 2;

require_once "../../../../db/models/Exercise.php";
require_once "../../../../alerts/functions.php";

$exercise = new Exercise();
try {
    $exercise->get_by_id($id);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch exercise: " . $e->getMessage(), "/staff/wnmp/exercises");
}
$_SESSION['exercise'] = $exercise;

$menuBarConfig = [
    "title" => $exercise->name,
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/exercises/index.php",
    "useLink" => true,
    "options" => [
        ["title" => "Edit Exercise", "href" => "/staff/wnmp/exercises/edit/index.php?id=$id", "type" => "secondary"],
        ["title" => "Delete Exercise", "href" => "/staff/wnmp/exercises/delete/index.php?id=$id", "type" => "destructive"]
    ]
];


require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../exercises.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <div class="base-sub-container">
            <div>
                <h2 style="margin-bottom: 10px;">
                    Description
                </h2>
                <p><?= $exercise->description?></p>
            </div>
            <div style="margin: 20px 0px;">
                <h2 style="margin: 10px 0px;">
                    Video Tutorial
                </h2>
                <?php if ($exercise->video_link): ?>
                    <iframe src="<?= $exercise->video_link ?>"
                            class="exercise-video-iframe"
                            allow="autoplay"
                            frameborder="0">
                    </iframe>
                <?php else: ?>
                    <p>No video tutorial available</p>
                <?php endif; ?>
            </div>

        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>
