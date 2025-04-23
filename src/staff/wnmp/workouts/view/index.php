<?php

require_once "../../../../auth-guards.php";
auth_required_guard("wnmp", "/staff/login");

$id = htmlspecialchars($_GET['id'] ?? null);

require_once "../../../../db/models/Workout.php";
require_once "../../../../db/models/Exercise.php";
require_once "../../../../alerts/functions.php";

$workout = new Workout();
$exerciseModel = new Exercise();
try {
    $workout->get_by_id($id);
    $workout->exercises = $exerciseModel->addExerciseTitles($workout->exercises);
    $_SESSION['workout'] = serialize($workout);
} catch (Exception $e) {
    redirect_with_error_alert("Failed to fetch workout: " . $e->getMessage(), "/staff/wnmp/workouts");
    exit;
}

$sidebarActive = 3;
$menuBarConfig = [
    "title" => $workout->name,
    "showBack" => true,
    "goBackTo" => "/staff/wnmp/workouts/index.php",
    "useLink" => true,
    "options" => [
        ["title" => "Edit Workout", "href" => "/staff/wnmp/workouts/edit/index.php?id=$id", "type" => "secondary"],
        ["title" => "Delete Workout", "href" => "/staff/wnmp/workouts/delete/index.php?id=$id", "type" => "destructive"]
    ]
];


require_once "../../pageconfig.php";

$pageConfig['styles'][] = "../workouts.css";

require_once "../../../includes/header.php";
require_once "../../../includes/sidebar.php";
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../../includes/menubar.php"; ?>
        <div class="view-workout-container">
            <div>
                <h2 style="margin-bottom: 20px;">
                    Exercises
                </h2>
                <?php foreach ($workout->exercises as $exercise): ?>
                    <div class="view-workout-exercise">
                        <p>[ Day <?= $exercise['day'] ?> ] - <?= $exercise['title'] ?></p>
                        <p class="alt"><?= $exercise['sets'] ?> x <?= $exercise['reps'] ?></p>
                    </div>
                    <hr>
                <?php endforeach; ?>
            </div>
            <div>
                <h2 style="margin-bottom: 20px;">
                    Description
                </h2>
                <p><?= $workout->description ?></p>
                <h2 style="margin-bottom: 10px; margin-top: 25px">
                    Duration
                </h2>
                <p style="font-size: 18px; font-weight: 400;"><?= $workout->duration ?> Days</p>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../../includes/footer.php"; ?>