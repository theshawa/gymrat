<?php

session_start();

require_once "../../../../alerts/functions.php";
require_once "../../../../db/models/Workout.php";
require_once "../../../../db/models/Exercise.php";

$id = htmlspecialchars($_GET['id'] ?? null);
$_SESSION['workout_id'] = $id;

$workout = new Workout();
if (!isset($_SESSION['workout'])) {
    $exerciseModel = new Exercise();
    try {
        $workout->get_by_id($id);
        $workout->exercises = $exerciseModel->addExerciseTitles($workout->exercises);
    } catch (Exception $e) {
        redirect_with_error_alert("Failed to fetch workout: " . $e->getMessage(), "/staff/wnmp/workouts");
    }
    $_SESSION['workout'] = serialize($workout);
} else {
    $workout = unserialize($_SESSION['workout']);
}

$sidebarActive = 3;
$menuBarConfig = [
    "title" => 'Delete ' . $workout->name,
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/workouts/view/index.php?id=" . intval($id)
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
                <h2>Are you sure you want to delete "<?= $workout->name ?>"?</h2>
                <p>This action cannot be undone.</p>
                <button type="submit">Delete</button>
            </div>
        </form>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>