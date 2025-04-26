<?php
require_once "../../../auth-guards.php";
if (auth_required_guard("rat", "/rat/login")) exit;

$id = $_GET['id'] ?? null;
if (!$id) {
    die("No exercise ID provided.");
}

require_once "../../../db/models/Exercise.php";
$exercise = new Exercise();
try {
    $exercise->get_by_id($id);
} catch (\Throwable $th) {
    die("Failed to get exercise: " . $th->getMessage());
}

$pageConfig = [
    "title" => "Exercise Info",
    "styles" => ["./exercise.css"],
    "titlebar" => [
        "back_url" => "../"
    ],
    "navbar_active" => 1
];

require_once "../../includes/header.php";
require_once "../../includes/titlebar.php";
?>

<main>
    <h1><?= $exercise->name ?></h1>
    <p class="description"><?= $exercise->description ?></p>
    <div class="facts">
        <div class="fact">
            <span class="title">Type</span>
            <p class="value"><?= $exercise->type ?></p>
        </div>
        <div class="fact">
            <span class="title">Difficulty</span>
            <p class="value"><?= $exercise->difficulty_level ?></p>
        </div>
        <div class="fact">
            <span class="title">Equipments</span>
            <p class="value"><?= $exercise->equipment_needed ?></p>
        </div>
        <div class="fact">
            <span class="title">Muscle Group</span>
            <p class="value"><?= $exercise->muscle_group ?></p>
        </div>
    </div>
    <?php require_once "../../../uploads.php";  ?>
    <img src="<?= get_file_url($exercise->image, "default-images/default_exercise.jpg") ?>" alt="Image of Bench Press" class="featured-image">
    <?php if ($exercise->video_link): ?>
        <div class="tutorial">
            <h3>TUTORIAL</h3>
            <iframe src="<?= $exercise->video_link ?>" frameborder="0"></iframe>
        </div>
    <?php endif; ?>
</main>

<?php require_once "../../includes/navbar.php" ?>
<?php require_once "../../includes/footer.php" ?>